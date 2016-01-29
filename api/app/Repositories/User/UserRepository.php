<?php

namespace App\Repositories\User;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\AuthService;
use App\Services\ResponseService;
use App\Interfaces\UserInterface;
class UserRepository implements UserInterface
{

	protected $auth;

    protected $user;

    protected $response;

    protected $request;

    public function __construct(AuthService $auth,User $user,ResponseService $response,Request $request){
        $this->auth = $auth;
        $this->user = $user;
        $this->response = $response;
        $this->request = $request;
    }
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->all();
        if(count($users) > 0){
        	return $this->response->json(true,$users,'');
        }
        else{
        	return $this->response->json(false,'','MESSAGE.SOMETHING_WENT_WRONG');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $user = $this->user->create($this->request->all());
        if($user){
            return $this->response->json(true,$user,'MESSAGE.CREATE_SUCCESS');
        }
        else{
            return $this->response->json(false,'','MESSAGE.SOMETHING_WENT_WRONG');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
