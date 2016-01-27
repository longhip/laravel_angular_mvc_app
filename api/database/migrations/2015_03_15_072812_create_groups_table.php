<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('groups', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->tinyInteger('status')->default(1);
			$table->integer('user_id')->default(0);
			$table->integer('time_create');
			$table->integer('time_update');
			$table->integer('company_id');
			$table->tinyInteger('can_remove')->default(1);
			$table->integer('deleted')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('groups');
	}

}
