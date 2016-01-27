<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class PermissionGroup extends Model {

	protected $table = 'permissions_groups';

	public $timestamps = false;
	public function group() {
		return $this->belongsTo('App\Models\Group', 'group_id')->where('status', Group::STATUS_ACTIVE);
	}
	public function permission() {
		return $this->belongsTo('App\Models\Permission', 'permission_id')->where('status', Permission::STATUS_ACTIVE);
	}
}