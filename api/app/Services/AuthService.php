<?php

namespace App\Services;
use JWTAuth;
class AuthService
{
    public function getId(){
    	return JWTAuth::parseToken()->authenticate()->id;
    }
    public function getName(){
    	return JWTAuth::parseToken()->authenticate()->name;
    }
}
