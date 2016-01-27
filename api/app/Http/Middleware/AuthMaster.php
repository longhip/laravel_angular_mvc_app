<?php namespace App\Http\Middleware;
/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */
use App\Libraries\Master\Auth;
use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AuthMaster {

	public function handle($request, Closure $next) {
		$token = Input::get('token');
		if (Auth::isLogged($token)) {
			return $next($request);
		} else {
			return Response::json([
				'status' => false,
				'message' => 'MESSAGE.YOU_NEED_LOGIN_TO_DO_THIS_ACTION',
				'error_token' => true,
			]);
		}
	}
}
