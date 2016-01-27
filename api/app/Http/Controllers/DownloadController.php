<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class DownloadController extends Controller {

	public function getDownload() {
		$file = $_SERVER["DOCUMENT_ROOT"] . '/api/public/' . Input::get('file');

		//$file = $_SERVER["DOCUMENT_ROOT"] . '/steedoffice/api/public/uploads/other/2/y/1/0/0/0d4841650915be608a413bf9cca4e8ba.jpg';
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
		exit;
	}
}
