<?php namespace App\Http\Controllers\Address;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\District;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class DistrictController extends Controller {

	public function getAll() {

		$district = District::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('active', District::STATUS_ACTIVE)->get();

		if (!$district->isEmpty()) {
			$response = [
				'status' => true,
				'data' => $district,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'DATA_IS_EMPTY',
			];
		}
		return Response::json($response);
	}

	public function getIndex() {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;

		$districtModel = new District();
		$districtModel = $districtModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId());

		$total = $districtModel->count();
		$listDistrict = $districtModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listDistrict->isEmpty()) {
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listDistrict,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_IS_EMPTY',
			];
		}
		return Response::json($response);
	}

	public function postCreate() {

		if ($this->_validate() === true) {
			$district = new District();
			$district->setConnection(Auth::getCS());
			$district->fill(Input::all());
			$district->user_id = Auth::getId();
			$district->company_id = Auth::getCompanyId();
			$district->time_create = $district->time_update = time();
			$district->active = Input::get('active') ? District::STATUS_ACTIVE : District::STATUS_DEACTIVE;
			$district->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.CREATE_SUCCESS',
				'id' => $district->id,
			];
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate() === true) {
			$district = District::on(Auth::getCS())->find($id);
			$district->setConnection(Auth::getCS());
			$district->fill(Input::all());
			$district->user_id = Auth::getId();
			$district->company_id = Auth::getCompanyId();
			$district->time_update = time();
			$district->active = Input::get('active') ? District::STATUS_ACTIVE : District::STATUS_DEACTIVE;
			$district->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
				'id' => $district->id,
			];
			return Response::json($response);
		}
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = District::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);

		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$district = District::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($district)) {
			$response = [
				'status' => true,
				'data' => $district,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_NOT_FOUND',
			];
		}
		return Response::json($response);
	}

	public function deleteItem($id) {
		$district = District::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $id)->delete();
		if ($district) {
			$response = [
				'status' => true,
				'message' => 'MESSAGE.DELETE_SUCCESS',
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DELETE_FAILED',
			];
		}
		return Response::json($response);
	}
}