<?php namespace App\Models;
use App\Libraries\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Group extends Model {

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	protected $table = 'groups';
	protected $primaryKey = 'id';

	protected $fillable = ['name', 'status', 'company_id'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:50',
	];

	public function users() {
		return $this->hasMany('App\Models\UserGroup', 'group_id');
	}

	public static function get_group_permissions(&$group) {
		if (!is_object($group)) {
			return;
		}

		$permission_array = array();

		// Grab our permissions for the role.
		$permissions = Permission::on(Auth::getCS())->where('status', 1)->where('company_id', Auth::getCompanyId())->get();

		// Permissions
		foreach ($permissions as $key => $permission) {
			$permission_array[$permission->name] = $permission;
		}

		$group->permissions = $permission_array;

		// Role Permissions
		$permission_array = array();
		$role_permissions = PermissionGroup::on(Auth::getCS())->where('group_id', $group->id)->where('company_id', Auth::getCompanyId())->get();

		if (!$role_permissions->isEmpty()) {
			foreach ($role_permissions as $key => $permission) {
				$permission_array[$permission->permission_id] = $permission->permission_id;
			}
		}

		$group->group_permissions = $permission_array;
		unset($permission_array);

	}

	public function set_group_permission($groupID, $group_permissions) {
		PermissionGroup::on(Auth::getCS())->where('group_id', $groupID)->delete();

		$permission_data = array();
		if (!empty($group_permissions)) {
			foreach ($group_permissions as $permission_id) {
				if ((int) $permission_id > 0) {
					$permission_data[] = [
						'group_id' => $groupID,
						'permission_id' => $permission_id,
						'company_id' => Auth::getCompanyId(),
					];
				}
			}
			if (!empty($permission_data)) {
				PermissionGroup::on(Auth::getCS())->insert($permission_data);
			}
		}
	}

	public static function getName($groupID = false) {
		if ($groupID) {
			return Group::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $groupID)->pluck('name');
		} else {
			return '';
		}
	}
}