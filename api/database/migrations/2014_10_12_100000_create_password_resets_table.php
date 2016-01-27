<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('password_resets', function (Blueprint $table) {
			$table->increments('id');
			$table->string('email')->index();
			$table->string('token')->index();
			$table->integer('company_id');
			$table->integer('time_create');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('password_resets');
	}

}
