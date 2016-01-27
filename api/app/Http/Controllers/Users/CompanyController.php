<?php namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class CompanyController extends Controller {

	public function putUpdate() {
		if ($this->_validate() === true) {
			$company = Company::on(Auth::getCS())->find(Auth::getCompanyId());
			$company->setConnection(Auth::getCS());
			$company->fill(Input::all());
			$company->user_id = Auth::getId();
			$company->time_update = time();
			$company->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
			];
			return Response::json($response);
		}
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = Company::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView() {
		$company = Company::on(Auth::getCS())->find(Auth::getCompanyId());
		if (!empty($company)) {
			$response = [
				'status' => true,
				'data' => $company,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_NOT_FOUND',
			];
		}
		return Response::json($response);
	}
}