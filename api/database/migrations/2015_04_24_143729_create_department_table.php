<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create("department", function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->tinyInteger('status');
			$table->integer('branch_id');
			$table->integer('company_id');
			$table->integer('user_id');
			$table->integer('time_create');
			$table->integer('time_update');
			$table->integer('deleted')->default(0);
			//31/10/2015 add row
			$table->integer("is_business")->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop("department");
	}

}
