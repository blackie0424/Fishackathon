<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model {
	public $table = 'vessels';
	public $fillable = ['name', 'country', 'imo', 'mmsi', 'image', 'ircs'];

	public function licence() {
		return $this->hasMany('App\Licence', 'imo', 'imo');
	}

	public function blacklist() {
		return $this->hasMany('App\Blacklist', 'imo', 'imo');
	}
}
