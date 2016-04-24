<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::get('/', function () {
	return view('welcome');
});

Route::resource('vessel', 'VesselController');
Route::get('parse', 'VesselController@parse');

Route::get('vessel/mmsi/{mmsi}', 'VesselController@getVesselByMMSI');
Route::get('vessel/imo/{imo}', 'VesselController@getVesselByIMO');
Route::get('vessel/limit/{limit}/offset/{offset}', 'VesselController@getVesselByOffset');

Route::get('blacklist', 'VesselController@blacklist');