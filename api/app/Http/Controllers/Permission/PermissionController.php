<?php namespace App\Http\Controllers\Permission;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Permission;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class PermissionController extends Controller {

	public function getIndex() {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;

		$permissionModel = new Permission();
		$permissionModel = $permissionModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId());

		$total = $permissionModel->count();
		$listPermission = $permissionModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listPermission->isEmpty()) {
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listPermission,
			];
		} else {
			$response = [
				'status' => false,
			];
		}
		return Response::json($response);
	}

	public function postCreate() {

		if ($this->_validate()) {
			$permission = new Permission();
			$permission->setConnection(Auth::getCS());
			$permission->fill(Input::all());
			$permission->user_id = Auth::getId();
			$permission->time_create = $permission->time_update = time();
			$permission->status = Input::get('status');
			$permission->company_id = Auth::getCompanyId();
			$permission->save();

			//update group if has permission
			$response = [
				'status' => true,
				'message' => 'MESSAGE.CREATE_SUCCESS',
				'id' => $permission->id,
			];
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate()) {
			$permission = Permission::on(Auth::getCS())->find($id);
			$permission->setConnection(Auth::getCS());
			$permission->fill(Input::all());
			$permission->user_id = Auth::getId();
			$permission->time_update = time();
			$permission->status = Input::get('status');
			$permission->company_id = Auth::getCompanyId();

			$permission->save();

			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
				'id' => $permission->id,
			];
		} else {
			$response = [
				'status' => false,
				'message' => $this->getMessage(),
			];
		}
		return Response::json($response);
	}

	private function _validate() {

		$rules = Permission::$rules;
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$permission = Permission::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($permission)) {
			$response = [
				'status' => true,
				'data' => $permission,
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

		$permission = Permission::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $id)->delete();
		if ($permission) {
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