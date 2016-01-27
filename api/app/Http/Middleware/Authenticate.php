<?php namespace App\Http\Middleware;

use App\Libraries\Auth;
use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Authenticate {

    public function handle($request, Closure $next) {
        $token = Input::get('token');
        $code = strtolower(Input::get('cs_code'));
        if(Auth::isLogged($code, $token)) {
            return $next($request);
        } else {
            return Response::json([
                'status'    =>  false,
                'message'   =>  'MESSAGE.YOU_NEED_LOGIN_TO_DO_THIS_ACTION',
                'error_token' => true
            ]);
        }
    }
}
