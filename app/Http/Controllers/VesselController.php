<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Htmldom;
use Illuminate\Http\Request;

class VesselController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return 'vesel list';
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

		//ship_type = 0 :undeine ship , ship_type= 2 :Fishing
		$res = $client->request('GET', 'http://www.marinetraffic.com/en/ais/index/ships/all/per_page:10/page:1/ship_type:2', [
			'headers' => [
				'User-Agent' => 'testing/1.0',
			],
		]);
		$html = new \Htmldom($res->getBody());
		$trs = $html->find('tr');
		foreach ($trs as $key => $tr) {
			if (0 !== $key) {
				// $td = $tr->children(0);
				// $counrty = $td->find('img')[0]->title;
				$td = $tr->children(1);
				$imo = $td->innertext;
				// $td = $tr->children(2);
				// $mmsi = $td->innertext;
				// $td = $tr->children(3);
				// $vesselName = $td->children(0)->innertext;
				// $td = $tr->children(4);
				// $image = (isset($td->find('img')[0])) ? $td->find('img')[0]->src : "";
				dump($imo);

			}

		}

	}
}