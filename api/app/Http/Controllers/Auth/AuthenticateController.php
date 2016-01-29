<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login and Logout
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'postLogin']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Login 
     *
     * @param  object  $credential
     * @return User
     */
    public function postLogin(Request $request)
    {
        $user = User::where('email',$request->input('email'))->first();
        if($user->active == 0){
            return response()->json(['status'=>false,'message' => 'MESSAGE.USER_IS_NOT_ACTIVE']);
        }
        else{
            $credentials = $request->only('email', 'password');
            try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['status'=>false,'message' => 'MESSAGE.EMAIL_OR_PASSWORD_IS_INCORRECT']);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['status'=>false,'message' => 'MESSAGE.SOME_THING_WENT_WRONG']);
            }   
            $data = [
                'user'=>$user,
                'token'=>$token
            ];
            return response()->json(['status'=>true,'message'=>'MESSAGE.LOGIN_SUCCESS','data'=>$data]);
        }
    }
    /**
     * Logout 
     *
     * @return response with message
     */
    public function getLogout()
    {
        $token = JWTAuth::getToken();
        if ($token) {
            JWTAuth::setToken($token)->invalidate();
        }
        return response()->json(['status'=>true,'message'=>'MESSAGE.LOGOUT_SUCCESS']);
    }
}
