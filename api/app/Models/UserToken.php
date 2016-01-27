<?php namespace App\Models;
use App\Libraries\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class UserToken extends Model {

	protected $table = 'users_tokens';
	public $timestamps = false;
	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id')->where('status', Auth::USER_STATUS_ACTIVE);
	}
}