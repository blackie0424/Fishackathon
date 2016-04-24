<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlacklistTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('blacklist', function (Blueprint $table) {
			$table->string('name');
			$table->string('country');
			$table->string('imo');
			$table->string('vessel_type');
			$table->string('organisation');
			$table->string('lat');
			$table->string('lng');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('blacklist');
	}
}
