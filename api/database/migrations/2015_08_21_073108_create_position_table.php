<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePositionTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('position', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('user_id');
			$table->integer('time_create')->default(0);
			$table->integer('time_update')->default(0);
			$table->integer('deleted')->default(0);
			$table->integer('company_id');
			$table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('position');
	}

}
