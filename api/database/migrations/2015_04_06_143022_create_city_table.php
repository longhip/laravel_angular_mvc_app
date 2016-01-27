<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('city', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('code', 100);
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
		Schema::drop('city');
	}

}
