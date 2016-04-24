<?php

namespace App\Console\Commands;

use App\Page;
use App\Vessel;
use GuzzleHttp\Client;
use Htmldom;
use Illuminate\Console\Command;

class ParseVessels extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'parse:vessels';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run parse vessels';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		//test 1000
		$maxPage = 1000;
		$current = Page::orderBy('id', 'desc')->first();
		if (!empty($current->page)) {
			$page = $current->page;
		} else {
			$page = 0;
		}
		\Log::info('current page is:' . $page);

		if ($page <= $maxPage) {
			$this->parseVessels($page);
			\Log::info('page is:' . $page);
		}

	}

	private function parseVessels($page) {
		$client = new Client();

		//total page =2908 when perpage is 50
		//total page =7270 when perpage is 20
		$current = 1 + $page;
		$maxPage = 20 + $page;
		for ($i = $current; $i <= $maxPage; $i++) {
			//ship_type = 0 :undeine ship , ship_type= 2 :Fishing
			$res = $client->request('GET', 'http://www.marinetraffic.com/en/ais/index/ships/all/per_page:20/page:' . $i . '/ship_type:2', [
				'headers' => [
					'User-Agent' => 'testing/1.0',
				],
			]);
			$html = new \Htmldom($res->getBody());

			$page = new Page;
			$page->page = $i;
			$page->save();

			$trs = $html->find('tr');
			foreach ($trs as $key => $tr) {
				if (0 !== $key) {
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

	private function get_string_between($string, $start, $end) {
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) {
			return '';
		}

		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
}