<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model {
	public $table = 'blacklist';

	public function vessel() {
		return $this->belongsTo('App\Vessel', 'imo', 'imo');
	}
}