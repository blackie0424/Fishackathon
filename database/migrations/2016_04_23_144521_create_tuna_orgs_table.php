<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTunaOrgsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tuna_orgs', function (Blueprint $table) {
			$table->string('vrmf_id')->default("");
			$table->string('source')->default("");
			$table->string('date_updated')->default("");
			$table->string('vessel_id')->default("");
			$table->string('tuvi')->default("");
			$table->string('vessel_name')->default("");
			$table->string('flag_code')->default("");
			$table->string('parent_vessel_type_code')->default("");
			$table->string('parent_vessel_type')->default("");
			$table->string('vessel_type_code')->default("");
			$table->string('vessel_type')->default("");
			$table->string('gear_type_code')->default("");
			$table->string('gear_type')->default("");
			$table->string('length')->default("");
			$table->string('length_type_code')->default("");
			$table->string('ircs')->default("");
			$table->string('nrn')->default("");
			$table->string('imo')->default("");
			$table->string('tonnage')->default("");
			$table->string('tonnage_type_code')->default("");
			$table->string('previous_name')->default("");
			$table->string('previous_name_date')->default("");
			$table->string('previous_flag_code')->default("");
			$table->string('previous_flag_code_date')->default("");
			$table->string('aut_status')->default("");
			$table->string('date_aut_start')->default("");
			$table->string('date_aut_end')->default("");
			$table->string('date_aut_term')->default("");
			$table->string('aut_term_code')->default("");
			$table->string('aut_term_description')->default("");
			$table->string('aut_term_reason')->default("");
			$table->string('url_id')->default("");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('tuna_orgs');
	}
}
