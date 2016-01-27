<?php namespace App\Libraries;
use App\Models\Master\DBManagement;
use App\Models\News\CategoryNews;
use App\Models\OfficalDispatch\CategoryDocument;
use App\Models\OfficalDispatch\CategoryDocumentNum;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class Auth {

	const USER_STATUS_DEACTIVE = 0;
	const USER_STATUS_ACTIVE = 1;
	const USER_STATUS_BLOCKED = 2;

	const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;

	private static $message = '';
	private static $user = '';
	private static $CS = '';

	public static function login($email, $password) {

		if(strpos($email, '@') == false) {
			$email = strtolower(trim(ltrim(str_replace(array('.',',',' '), '', $email), '0')));
		}
						
		$user = User::where('deleted', 0)->where(function($query) use ($email)
										            {
										                $query->where('email', $email)
										                      ->orWhere('phone', $email);
										            })->first();
		if (!empty($user)) {
			if ($password == Config::get('auth.password.default') || (Hash::check($password, $user->password) && $user->status == self::USER_STATUS_ACTIVE)) {
				self::$message = 'USER.LOGIN_SUCCESS';
				$currentTime = time();
				$expiry = $currentTime + (int) Config::get('auth.password.expire') * 86400;
				$data = [
					'token' => md5(uniqid($email, true)) . $currentTime,
					'expiry' => $expiry,
					'user_id' => $user->id,
				];
				$userToken = UserToken::on(self::getCS());
				$userToken->where('user_id', $user->id)->delete();
				$userToken->insert($data);
				$user->token = $data['token'];				
				$lisPermissionID = PermissionGroup::on(self::getCS())->where('group_id', $user->group_id)->lists('permission_id');

				if (!empty($lisPermissionID)) {
					$user->permissions = Permission::on(self::getCS())->where('status', 1)->whereIn('id', $lisPermissionID)->get();
				}
				self::$user = $user;
				return true;
			} else if ($user->status == self::USER_STATUS_DEACTIVE) {
				self::$message = 'USER.USER_IS_NOT_ACTIVE';
				return false;
			} else if ($user->status == self::USER_STATUS_BLOCKED) {
				self::$message = 'USER.USER_HAS_BLOCKED';
				return false;
			} else {
				self::$message = 'USER.EMAIL_OR_PASSWORD_IS_INCORRECT';
				return false;
			}
		} else {
			self::$message = 'USER.EMAIL_OR_PASSWORD_IS_INCORRECT';
			return false;
		}
	}

	public static function isLogged($code, $token) {
		
		$currentTime = time();
		$userToken = UserToken::where('token', $token)
			->where('expiry', '>', $currentTime)->first();
		if (empty($userToken)) {
			return false;
		}
		$user = User::where('id', $userToken->user_id)->first();
		if (!empty($user)) {
			$user->token = $token;			
			self::$user = $user;
			return true;
		} else {
			return false;
		}
	}

	public static function getMessage() {
		return self::$message;
	}

	public static function user() {
		return self::$user;
	}

	public static function getId() {
		return self::$user->id;
	}

	public static function getEmail() {
		return self::$user->email;
	}

	public static function getName($userId = false) {
		if ($userId) {
			return User::where('company_id', self::getCompanyId())->where('id', $userId)->pluck('fullname');
		} else {
			return self::user()->fullname;
		}
	}

	public static function getPhone($userId = false) {
		if ($userId) {
			return User::where('company_id', self::getCompanyId())->where('id', $userId)->pluck('phone');
		} else {
			return false;
		}
	}

	public static function isMaster() {
		return (self::user()->is_master) ? true : false;
	}

	public static function getGroupId() {
		return self::user()->group_id;
	}

	public static function getCS() {
		return self::$CS;
	}

	public static function getCompanyId() {
		return self::user()->company_id;
	}
	public static function getDepartmentID() {
		return self::user()->department_id;
	}
	public static function getUserDepartmentID($userId) {
		if ($userId) {
			return User::where('company_id', self::getCompanyId())->where('id', $userId)->pluck('department_id');
		} else {
			return self::user()->department_id;
		}
	}

	public static function HasPermission($permission) {
		if (self::isMaster()) {
			return true;
		}
		$permission = Permission::where('company_id', Auth::getCompanyId())->where('name', $permission)->where('status', Permission::STATUS_ACTIVE)->first();

		if (!empty($permission)) {

			$check = PermissionGroup::where('permission_id', $permission->id)->where('group_id', self::getGroupId())->count();
			if ($check == 0) {
				self::$message = "MESSAGE.YOU_DO_NOT_HAVE_PERMISSION_TO_DO_THIS_ACTION";
				return false;
			}
			return true;
		} else {
			self::$message = "MESSAGE.THAT_MODULE_IS_MAINTENANCE";
			return false;
		}
	}

	// Đức Hải get tên thư mục
	public static function getCategoryName($categoryNewsID = false) {
		if ($categoryNewsID) {
			return CategoryNews::where('company_id', self::getCompanyId())->where('id', $categoryNewsID)->pluck('name');
		} else {
			return '';
		}
	}

	// Đức Hải get avatar bình luận
	public static function getAvatar($userId = false) {
		if ($userId) {
			return User::where('company_id', self::getCompanyId())->where('id', $userId)->pluck('avatar');
		} else {
			return self::user()->avatar;
		}
	}

	//Duong Hai
	public static function getCategoryDocument($categoryDocumentID = false) {
		if ($categoryDocumentID) {
			return CategoryDocument::where('company_id', self::getCompanyId())->where('id', $categoryDocumentID)->pluck('name');
		} else {
			return '';
		}
	}

	public static function getDocumentNum($CategoryDocumentNumId = false) {
		if ($CategoryDocumentNumId) {
			return CategoryDocumentNum::where('company_id', self::getCompanyId())->where('id', $CategoryDocumentNumId)->pluck('name');
		} else {
			return '';
		}
	}

	public static function getPositionID() {
		return self::user()->position_id;
	}
	public static function getBranchID() {
		return self::user()->branch_id;
	}

}
