<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class LanguageController extends Controller {

	public function getIndex() {
		
		$lang = Input::get('lang');
		$folders = File::files(base_path('resources/lang/' . $lang));
		$data = array();
		if (!empty($folders)) {
			foreach ($folders as $folder) {
				$folderName = strtoupper(basename($folder, ".php"));
				$lang = File::getRequire($folder);
				$data[$folderName] = $lang;
			}
		}

		return Response::json($data);
	}
}
