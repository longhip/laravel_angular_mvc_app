<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UploadReponsitory;

class UploadController extends Controller
{

    protected $reponsitory;

    public function __construct(UploadReponsitory $reponsitory){
        $this->middleware('jwt.auth');
        $this->reponsitory = $reponsitory;
    }

    public function postUpload(){
        return $this->reponsitory->upload();
    }
}
