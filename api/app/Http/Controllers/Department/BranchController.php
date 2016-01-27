<?php namespace App\Http\Controllers\Department;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Department\Branch;
use App\Models\Department\Department;
use App\Models\Position\Position;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class BranchController extends Controller {

	public function getList() {
		$userNotIn = Input::get('user_not_in');
		$type = Input::get('type');		
		$branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0)->get();
		if($type == 'assignment') {
			$department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('select_assignment',1)->get();
		}else {
			$department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
		}		
		
		$listDepartmentByBranch = [];
		if (!$department->isEmpty()) {
			foreach ($department as $oneDepartment) {
				$oneDepartment->opened = false;
				$listDepartmentByBranch[$oneDepartment->branch_id][] = $oneDepartment;
			}
		}

		$userModel = User::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0);
		if (!empty($userNotIn)) {
			$userModel = $userModel->whereNotIn('id', $userNotIn);
		}
		$user = $userModel->get();
		$position = Position::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
		$listPosition = [];
		if (!$position->isEmpty()) {
			foreach ($position as $onePosition) {
				$listPosition[$onePosition->id] = $onePosition;
			}
		}
		$listUser = [];
		if (!$user->isEmpty()) {
			foreach ($user as $oneUser) {
				if (isset($listPosition[$oneUser->position_id])) {
					$oneUser->position = $listPosition[$oneUser->position_id];
				}
				if (!isset($listUser[$oneUser->department_id])) {
					$listUser[$oneUser->department_id] = [];
				}
				$listUser[$oneUser->department_id][] = $oneUser;
			}
		}
		if (!$branch->isEmpty()) {
			foreach ($branch as $k => $oneBranch) {
				$department = [];
				if (!empty($listDepartmentByBranch[$oneBranch->id])) {
					$department = isset($listDepartmentByBranch[$oneBranch->id]) ? $listDepartmentByBranch[$oneBranch->id] : [];
					foreach ($department as $j => $oneDepartment) {
						$department[$j]->users = isset($listUser[$oneDepartment->id]) ? $listUser[$oneDepartment->id] : [];
					}
				}
				$branch[$k]->department = $department;
			}
			$response = [
				'status' => true,
				'data' => $branch,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_IS_EMPTY',
			];
		}
		return Response::json($response);
	}

	public function getAll() {
		$branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0)->get();

		if (!$branch->isEmpty()) {
			$response = [
				'status' => true,
				'data' => $branch,
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

		$branchModel = new Branch();
		$branchModel = $branchModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0);

		$total = $branchModel->count();
		$listBranch = $branchModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listBranch->isEmpty()) {
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listBranch,
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
			$branch = new Branch();
			$branch->setConnection(Auth::getCS());
			$branch->fill(Input::all());
			$branch->user_id = Auth::getId();
			$branch->company_id = Auth::getCompanyId();
			$branch->time_create = $branch->time_update = time();
			$branch->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.CREATE_SUCCESS',
				'id' => $branch->id,
			];
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate() === true) {
			$branch = Branch::on(Auth::getCS())->find($id);
			$branch->setConnection(Auth::getCS());
			$branch->fill(Input::all());
			$branch->company_id = Auth::getCompanyId();
			$branch->user_id = Auth::getId();
			$branch->time_update = time();
			$branch->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
				'id' => $branch->id,
			];
			return Response::json($response);
		}
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = Branch::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($branch)) {
			$response = [
				'status' => true,
				'data' => $branch,
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
		$branch = Branch::on(Auth::getCS())->find($id);
		$branch->setConnection(Auth::getCS());
		$branch->user_id = Auth::getId();
		$branch->time_update = time();
		$branch->deleted = 1;
		$branch->save();

		if ($branch) {
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