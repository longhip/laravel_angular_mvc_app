<?php namespace App\Http\Controllers\Position;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Position\Position;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class PositionController extends Controller {

	public function getAll() {
		$PositionModel = new Position();

		$Position = $PositionModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0)->get();

		if (!$Position->isEmpty()) {
			$response = [
				'status' => true,
				'data' => $Position,
			];
		} else {
			$response = [
				'status' => false,
			];
		}
		return Response::json($response);
	}

	public function getIndex() {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;

		$PositionModel = new Position();
		$PositionModel = $PositionModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0);

		$total = $PositionModel->count();
		$listPosition = $PositionModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listPosition->isEmpty()) {
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listPosition,
			];
		} else {
			$response = [
				'status' => false,
			];
		}
		return Response::json($response);
	}

	public function postCreate() {

		if ($this->_validate() === true) {
			$Position = new Position();
			$Position->setConnection(Auth::getCS());
			$Position->fill(Input::all());
			$Position->user_id = Auth::getId();
			$Position->company_id = Auth::getCompanyId();
			$Position->time_create = $Position->time_update = time();
			$Position->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.CREATE_SUCCESS',
				'id' => $Position->id,
			];
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate() === true) {
			$Position = Position::on(Auth::getCS())->find($id);
			$Position->setConnection(Auth::getCS());
			$Position->fill(Input::all());
			$Position->company_id = Auth::getCompanyId();
			$Position->user_id = Auth::getId();
			$Position->time_update = time();
			$Position->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
				'id' => $Position->id,
			];
			return Response::json($response);
		}
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = Position::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$Position = Position::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($Position)) {
			$response = [
				'status' => true,
				'data' => $Position,
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
		$Position = Position::on(Auth::getCS())->find($id);
		$Position->setConnection(Auth::getCS());
		$Position->user_id = Auth::getId();
		$Position->time_update = time();
		$Position->deleted = 1;
		$Position->save();

		//$Position = Position::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $id)->delete();
		if ($Position) {
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