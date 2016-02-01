<?php

namespace App\Repositories;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\ResponseService;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class UploadReponsitory
{


    protected $response;

    protected $request;

    protected $str;

    public function __construct(ResponseService $response,Request $request,Str $str){
        $this->response = $response;
        $this->request = $request;
        $this->str = $str;
    }
	/**
     * Upload file
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $file =  $this->request->file('file');
        $file_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); // getting file extensions
        $file_name_in_folder = $this->str->slug($file_name).'-'.time().'.'.$extension; // file name in folder
        $file_size = $file->getSize(); // getting file size

        $destinationPath = 'uploads'.'/'.date("Y").'/'.date("m"); // path default

        // if folder exists then create new folder
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $data = [
            'file_name'=>$file_name,
            'file_url'=>$destinationPath.'/'.$file_name_in_folder,
            'file_size'=>$file_size,
            'file_type'=>$extension,

        ];
        $file->move($destinationPath, $file_name_in_folder);
        if($data){
            return $this->response->json(true,$data,'MESSAGE.UPLOAD_SUCCESS');
        }
        else{
            return $this->response->json(false,'','MESSAGE.UPLOAD_FAILED');
        }
    }
}
