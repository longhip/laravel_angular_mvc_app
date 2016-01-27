<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDistrictTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('district', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('city_id');
			$table->integer('user_id');
			$table->integer('time_create');
			$table->integer('time_update');
			$table->tinyInteger('active')->default(1);
			$table->integer('company_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('district');
	}

}
