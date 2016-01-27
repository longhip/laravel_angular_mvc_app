<?php namespace App\Http\Controllers\Address;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class CityController extends Controller {

	public function getAll() {
		$city = City::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('active', City::STATUS_ACTIVE)->get();

		if (!$city->isEmpty()) {
			$response = [
				'status' => true,
				'data' => $city,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_IS_EMPTY',
			];
		}
		return Response::json($response);
	}

	public function getAddress() {
		$city = City::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('active', City::STATUS_ACTIVE)->get();
		if (!$city->isEmpty()) {
			$listCity = [];
			$district = District::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('active', District::STATUS_ACTIVE)->get();
			$listDistrict = [];
			if (!$district->isEmpty()) {
				foreach ($district as $oneDistrict) {
					$listDistrict[$oneDistrict->city_id][] = $oneDistrict;
				}
			}
			foreach ($city as $k => $oneCity) {
				$listCity[$k] = $oneCity;
				$listCity[$k]->district = $listDistrict[$oneCity->id];
			}
			$response = [
				'status' => true,
				'data' => $listCity,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_IS_EMPTY',
			];
		}
		return Response::json($response);
	}

	public function getIndex() {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;

		$cityModel = new City();
		$cityModel = $cityModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId());

		$total = $cityModel->count();
		$listCity = $cityModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listCity->isEmpty()) {
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listCity,
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
			$city = new City();
			$city->setConnection(Auth::getCS());
			$city->fill(Input::all());
			$city->user_id = Auth::getId();
			$city->company_id = Auth::getCompanyId();
			$city->time_create = $city->time_update = time();
			$city->active = Input::get('active') ? City::STATUS_ACTIVE : City::STATUS_DEACTIVE;
			$city->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.CREATE_SUCCESS',
				'id' => $city->id,
			];
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate() === true) {
			$city = City::on(Auth::getCS())->find($id);
			$city->setConnection(Auth::getCS());
			$city->fill(Input::all());
			$city->company_id = Auth::getCompanyId();
			$city->user_id = Auth::getId();
			$city->time_update = time();
			$city->active = Input::get('active') ? City::STATUS_ACTIVE : City::STATUS_DEACTIVE;
			$city->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
				'id' => $city->id,
			];
			return Response::json($response);
		}
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = City::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$city = City::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($city)) {
			$response = [
				'status' => true,
				'data' => $city,
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
		$city = City::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $id)->delete();
		if ($city) {
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