<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('username')->unique();
			$table->string('email');
			$table->string('fullname');
			$table->string('phone');
			$table->string('avatar');
			$table->integer('birthday');
			$table->string('password', 60);
			$table->string('gender', 10);
			$table->rememberToken();
			$table->integer('time_create')->default(0);
			$table->integer('time_update')->default(0);
			$table->integer('last_login')->default(0);
			$table->integer('deleted')->default(0);
			$table->tinyInteger('status')->default(0);
			$table->integer('group_id')->default(0);
			$table->integer('company_id')->default(0);
			$table->integer('type')->default(0);
			$table->integer('department_id');
			$table->integer('branch_id');
			$table->integer('position_id');
			$table->index('email');
			$table->index('phone');
			$table->index('username');
			$table->index('company_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('users');
	}

}
