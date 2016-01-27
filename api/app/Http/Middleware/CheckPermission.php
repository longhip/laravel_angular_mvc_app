<?php namespace App\Http\Middleware;

use App\Libraries\Auth;
use Closure;
use Illuminate\Support\Facades\Response;

class CheckPermission {

    public function handle($request, Closure $next) {
        if(Auth::HasPermission("quanly")) {
            return $next($request);
        } else {
            return Response::json([
                'status'    =>  false,
                'message'   =>  'MESSAGE.YOU_DO_NOT_HAVE_PERMISSION_TO_DO_THIS_ACTION'
            ]);
        }
    }
}
