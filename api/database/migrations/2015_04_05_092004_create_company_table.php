<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('company', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('mobile', 15);
			$table->string('fax', 15);
			$table->string('address');
			$table->string('email');
			$table->integer('city_id');
			$table->integer('district_id');
			$table->integer('user_own_id');

			$table->integer('time_create');
			$table->integer('time_update');
			$table->integer('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('company');
	}

}
