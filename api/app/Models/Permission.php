<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Permission extends Model {

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	protected $table = 'permissions';
	protected $primaryKey = 'id';

	protected $fillable = ['name', 'title', 'status'];

	public $timestamps = false;
	public static $rules = [
		'name' => 'required|max:50|unique:permissions',
	];

	public function groups() {
		return $this->hasMany('App\Models\PermissionGroup', 'permission_id');
	}

}