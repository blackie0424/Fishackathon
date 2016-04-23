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

		//total page =2908
		$current = 1 + $page;
		$maxPage = 20 + $page;
		for ($i = $current; $i <= $maxPage; $i++) {
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

			$trs = $html->find('tr');
			foreach ($trs as $key => $tr) {
				if (0 !== $key) {
					$td = $tr->children(0);
					$country = (isset($td->find('img')[0]->title)) ? $td->find('img')[0]->title : "";
					$td = $tr->children(1);
					$imo = (isset($td->innertext)) ? $this->get_string_between($td->innertext, "IMO: ", " <") : "";
					$td = $tr->children(2);
					$mmsi = (isset($td->innertext)) ? trim($td->innertext) : "";
					$td = $tr->children(3);
					$name = (count($td->children(0)->innertext)) ? $td->children(0)->innertext : "";
					$td = $tr->children(4);
					$image = (isset($td->find('img')[0])) ? "http:" . $td->find('img')[0]->src : "";
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