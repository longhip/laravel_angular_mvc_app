<?php namespace App\Http\Controllers;
use App\Libraries\Auth;
use App\Models\Files;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Upload\Storage\FileSystem;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class UploaderController extends Controller {

	private $link_upload = "uploads";
	public function postIndex() {

		$type = Input::has('type') ? Input::get('type') : '';
		$id = Input::has('id') ? (int) Input::get('id') : 0;
		$folder = Input::has('folder') ? Input::get('folder') : 'other';
		$LinkUpload = $this->Createfolder($folder, Hash::make(Auth::getEmail()));

		$storage = new FileSystem($LinkUpload['uploadPath']);
		$file = new \Upload\File('file', $storage);
		// Optionally you can rename the file on upload
		$new_filename = $this->Encrypt32(Auth::getEmail() . Auth::getId() . time());
		$old_name = $file->getNameWithExtension();
		$file->setName($new_filename);
		// Validate file upload
		$file->addValidations(array(
			new Mimetype(array('application/octet-stream', 'image/jpeg', 'image/png', 'image/jpg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/vnd.ms-office', 'application/vnd.ms-excel', 'application/excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/pdf')),
			new Size('5M'),
		));
		$file->upload();
		//insert file
		$size = $file->getSize();
		$upload = new Files();
		$upload->setConnection(Auth::getCS());
		$upload->file_url = $LinkUpload['linkPath'] . '/' . $file->getNameWithExtension();
		$upload->name = $old_name;
		$upload->extension = $file->getExtension();
		$upload->user_id = Auth::getId();
		$upload->file_name = $file->getNameWithExtension();
		$upload->file_size = $size;
		$upload->time_create = $upload->time_update = time();
		$upload->company_id = Auth::getCompanyId();
		$upload->save();
		return Response::json([
			'status' => true,
			'data' => $upload,
		]);

	}

	function createFolder($folder_name = 'other', $item = '') {

		$uploadPath = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . Auth::getCompanyId() . DIRECTORY_SEPARATOR . $folder_name;
		$linkPath = $this->link_upload . '/' . Auth::getCompanyId() . '/' . $folder_name;

		if (!empty($item)) {
			$item = str_split(preg_replace('/(\W)/', '', (string) $item));

			for ($i = 0; $i < 5; $i++) {
				if (isset($item[$i]) && $item[$i] != '') {
					$uploadPath .= DIRECTORY_SEPARATOR . $item[$i];
					$linkPath .= '/' . $item[$i];
				}
			}

			if (!file_exists($uploadPath)) {
				File::makeDirectory($uploadPath, 0777, true, true);
			}
		}

		return array(
			'uploadPath' => $uploadPath,
			'linkPath' => $linkPath,
		);
	}

	public function Encrypt32($str) {
		if (!empty($str)) {
			return md5(Crypt::encrypt($str));
		}
		return false;
	}
	public function getFileType($file_name) {
		return substr($file_name, strrpos($file_name, '.') + 1);
	}
}