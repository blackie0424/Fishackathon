<?php

namespace App\Http\Controllers;

use App\BlackList;
use App\Http\Controllers\Controller;
use App\Page;
use App\Vessel;
use DB;
use GuzzleHttp\Client;
use Htmldom;
use Illuminate\Http\Request;
use Log;

class VesselController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		Log::info('vessel list');
		return 'vessel list';
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	public function parse() {
		$client = new Client();

		//total page =2908
		//

		for ($i = 1; $i <= 55; $i++) {
			//ship_type = 0 :undeine ship , ship_type= 2 :Fishing
			$res = $client->request('GET', 'http://www.marinetraffic.com/en/ais/index/ships/all/per_page:50/page:' . $i . '/ship_type:2', [
				'headers' => [
					'User-Agent' => 'testing/1.0',
				],
			]);
			$html = new \Htmldom($res->getBody());

			$page = new Page;
			$page->page = $i;
			$page->save();
			echo $res->getBody();
			$trs = $html->find('tr');
			foreach ($trs as $key => $tr) {
				if (0 !== $key && $tr !== null) {
					$td1 = $tr->children(0);
					$country = (isset($td1->find('img')[0]->title)) ? $td1->find('img')[0]->title : "";
					$td2 = $tr->children(1);
					$imo = (isset($td2->innertext)) ? $this->get_string_between($td2->innertext, "IMO: ", " <") : "";
					$td3 = $tr->children(2);
					$mmsi = (isset($td3->innertext)) ? trim($td3->innertext) : "";
					$td4 = $tr->children(3);
					$name = (isset($td4->find("a")[0]->innertext)) ? $td4->find("a")[0]->innertext : "";
					$td5 = $tr->children(4);
					$image = (isset($td5->find('img')[0])) ? "http:" . $td5->find('img')[0]->src : "";

					$vessel = new Vessel;
					$vessel->name = $name;
					$vessel->country = $country;
					$vessel->imo = $imo;
					$vessel->mmsi = $mmsi;
					$vessel->image = $image;
					$vessel->save();
				}
			}
		}
	}

	public function get_string_between($string, $start, $end) {
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) {
			return '';
		}

		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}

	public function getVesselByMMSI($mmsi) {
		$vessel = Vessel::where('mmsi', $mmsi)->with(['licence' => function ($query) {
			$query->where('aut_status', 'Authorized')->get();
		}])->first();
		return response()->json($vessel);
	}

	public function getVesselByIMO($imo) {
		$vessel = Vessel::where('imo', $imo)->with(['licence' => function ($query) {
			$query->where('aut_status', 'Authorized')->get();
		}])->first();
		return response()->json($vessel);
	}

	public function blacklist() {
		$blacklist = DB::table('blacklist')->where('imo', '!=', "")->get();
		return response()->json($blacklist);
	}

	public function getVesselByOffset($limit, $offset) {
		$vessels = Vessel::skip($offset)->take($limit)->get();
		foreach ($vessels as $vessel) {
			if ($vessel) {
				$vessel->licence;
			}
		}
		return response()->json($vessels);
	}

}