<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		// database steed

		$Permission = new \App\Models\Permission();
		$Permission->name = "Permission.Group.Manager";
		$Permission->title = "Quản lý nhóm quyền";
		$Permission->status = 1;
		$Permission2 = clone $Permission;
		$Permission->save();
		$Permission2->name = "Permission.User.Manager";
		$Permission2->title = "Quản lý người dùng";
		$Permission2->save();

		/**
		 * Tạo nhóm quyền
		 */
		$group = new \App\Models\Group();
		$group->name = "Administrator";
		$group->company_id = 1;
		$group->save();

		$group1 = new \App\Models\Group();
		$group1->name = "Mod";
		$group->company_id = 1;
		$group1->save();

		/**
		 * Quyền và map nhóm với quyền
		 */
		$PermissionGroup = new \App\Models\PermissionGroup();
		$PermissionGroup->company_id = 1;
		$PermissionGroup->permission_id = $Permission->id;
		$PermissionGroup->group_id = $group->id;
		$PermissionGroup2 = clone $PermissionGroup;
		$PermissionGroup->save();
		$PermissionGroup2->permission_id = $Permission2->id;
		$PermissionGroup2->save();

		/**
		 * Tạo user
		 */
		$user = new \App\Models\User();
		$user->email = "admin@steed.vn";
		$user->password = \Illuminate\Support\Facades\Hash::make("123456");
		$user->fullname = "Admin";
		$user->company_id = 1;
		$user->group_id = $group->id;
		$user->save();
	}

}
