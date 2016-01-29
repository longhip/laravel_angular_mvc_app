<?php

namespace App\Services;

class ResponseService
{
    public function json($status,$data,$message){
    	return response()->json([
    		'status'=>$status,
    		'data'=>$data,
    		'message'=>$message
    	]);
    }
}
