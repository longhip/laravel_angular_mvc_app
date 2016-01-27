<?php namespace App\Models;
use App\Libraries\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class UserGroup extends Model {

	protected $table = 'user_groups';

	public $timestamps = false;
	public function user() {
		return $this->belongsTo('App\Models\User', 'id')->where('status', Auth::USER_STATUS_ACTIVE);
	}

	public function group() {
		return $this->belongsTo('App\Models\Group', 'group_id')->where('status', Group::STATUS_ACTIVE);
	}
}