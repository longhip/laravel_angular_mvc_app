<?php namespace App\Models;

use App\Models\Company;
use App\Models\Department\Department;
use App\Models\Master\DBManagement;
use App\Models\Position\Position;
use Illuminate\Database\Eloquent\Model;

class User extends Model {

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['fullname', 'email', 'phone', 'contract', 'avatar'];
	protected $guarded = ['password'];

	public static $rules = [
		'email' => 'required|max:250',
		'phone' => 'required',
	];

	protected $hidden = ['password', 'remember_token', 'user_id'];

	public static function getId($accessToken) {
		$userID = UserToken::where('token', $accessToken)->where('expiry', ' >', $accessToken)->pluck('user_id');
		if (is_int($userID)) {
			return $userID;
		} else {
			return false;
		}
	}

	public function groups() {
		return $this->hasMany('App\Models\UserGroup', 'user_id');
	}

	public function position() {
		return $this->belongsTo('App\Models\Position', 'id');
	}

	public function company() {
		return $this->belongsTo('App\Models\Company', 'id');
	}

	public function token() {
		return $this->hasMany('App\Models\UserToken', 'user_id');
	}

	public static function getInfo($code, $userID, $company_id) {
		$connectionString = DBManagement::where('code', $code)->first();
		if (!empty($connectionString)) {
			if ($connectionString->active == self::STATUS_DEACTIVE) {
				return false;
			}
		} else {
			return false;
		}
		$data = [];
		$user = User::on($connectionString->connect_string)->where('company_id', $company_id)->find($userID);
		if (!empty($user)) {
			$data = [
				'id' => $user->id,
				'fullname' => $user->fullname,
				'email' => $user->email,
				'phone' => $user->phone,
				'gender' => $user->gender,
				'avatar' => url($user->avatar),
				'department_id' => $user->department_id,
				'position_id' => $user->position_id,
				'birthday' => timeToDate($user->birthday),
				'address' => $user->address,
				'signature' => $user->signature,
				'department_name' => Department::on($connectionString->connect_string)->where('company_id', $company_id)->where('id', $user->department_id)->pluck('name'),
				'position_name' => Position::on($connectionString->connect_string)->where('company_id', $company_id)->where('id', $user->position_id)->pluck('name'),
				'company_name' => Company::on($connectionString->connect_string)->where('id', $company_id)->pluck('name'),
			];
		}

		return $data;
	}

}
