<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('permissions_groups', function (Blueprint $table) {
			$table->integer('group_id');
			$table->integer('permission_id');
			$table->integer('company_id');

			$table->index(['group_id', 'permission_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('permissions_groups');
	}

}
