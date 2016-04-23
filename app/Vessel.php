<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model {
	public $table = 'vessels';
	public $fillable = ['name', 'country', 'imo', 'mmsi', 'image', 'ircs'];
}
