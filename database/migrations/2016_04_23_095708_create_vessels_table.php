<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVesselsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('vessels', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('country');
			$table->string('imo');
			$table->string('mmsi');
			$table->string('image');
			$table->string('icrs');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('vessels');
	}
}
