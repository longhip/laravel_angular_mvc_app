<?php namespace App\Http\Controllers\Department;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Department\Branch;
use App\Models\Department\Department;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class DepartmentController extends Controller {

	public function getAll() {
		$branchID = Input::has('branchID') ? Input::get('branchID') : 0;
		$type = Input::has('type') ? Input::get('type') : '';
		if($type == 'assignment') {
			$departmentModel = $departmentModel->where('select_assignment', 1);
		}

		$departmentModel = new Department();
		if ($branchID > 0) {
			$departmentModel = $departmentModel->where('branch_id', $branchID);
		}
		$department = $departmentModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0)->get();
		$branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
		$listBranch = [];
		if (!$branch->isEmpty()) {
			foreach ($branch as $oneBranch) {
				$listBranch[$oneBranch->id] = $oneBranch;
			}
		}
		if (!$department->isEmpty()) {
			foreach ($department as $k => $oneDepartment) {
				$department[$k]->branch = isset($listBranch[$oneDepartment->branch_id]) ? $listBranch[$oneDepartment->branch_id] : '';
			}
			$response = [
				'status' => true,
				'data' => $department,
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

		$departmentModel = new Department();
		$departmentModel = $departmentModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0);

		$total = $departmentModel->count();
		$listDepartment = $departmentModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listDepartment->isEmpty()) {
			$listBranch = $listBranchID = [];
			foreach ($listDepartment as $oneDepartment) {
				$listBranchID[] = $oneDepartment->branch_id;
			}
			if (!empty($listBranchID)) {
				$branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->whereIn('id', $listBranchID)->get();
				if (!$branch->isEmpty()) {
					foreach ($branch as $oneBranch) {
						$listBranch[$oneBranch->id] = $oneBranch;
					}
				}
			}
			foreach ($listDepartment as $k => $oneDepartment) {
				$listDepartment[$k]->branch = isset($listBranch[$oneDepartment->branch_id]) ? $listBranch[$oneDepartment->branch_id] : '';
			}
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listDepartment,
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
			if(!Input::has('manager_id')){
                $department = new Department();
                $department->setConnection(Auth::getCS());
                $department->fill(Input::all());
                $department->user_id = Auth::getId();
                $department->company_id = Auth::getCompanyId();
                $department->time_create = $department->time_update = time();
                $department->branch_id = Input::get('branch_id');
                $department->save();
                $response = [
                    'status' => true,
                    'message' => 'MESSAGE.CREATE_SUCCESS',
                    'id' => $department->id,
                ];
            }
            /* longnd@steed.vn Badinco Department */
            if(Input::has('manager_id')){
                $department = new Department();
                $department->setConnection(Auth::getCS());
                $department->fill(Input::all());
                $department->user_id = Auth::getId();
                $department->company_id = Auth::getCompanyId();
                $department->manager_id = Input::get('manager_id');
                $department->time_create = $department->time_update = time();
                $department->branch_id = Input::get('branch_id');
                $department->save();
                $response = [
                    'status' => true,
                    'message' => 'MESSAGE.CREATE_SUCCESS',
                    'id' => $department->id,
                ];
            }
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate() === true) {
			$department = Department::on(Auth::getCS())->find($id);
			$department->setConnection(Auth::getCS());
			$department->fill(Input::all());
			$department->company_id = Auth::getCompanyId();
			$department->user_id = Auth::getId();
			$department->time_update = time();
			$department->branch_id = Input::get('branch_id');
			$department->save();
			$response = [
				'status' => true,
				'message' => 'MESSAGE.UPDATE_SUCCESS',
				'id' => $department->id,
			];
			return Response::json($response);
		}
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = Department::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($department)) {
			$response = [
				'status' => true,
				'data' => $department,
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
		$department = Department::on(Auth::getCS())->find($id);
		$department->setConnection(Auth::getCS());
		$department->user_id = Auth::getId();
		$department->time_update = time();
		$department->deleted = 1;
		$department->save();

		//$department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $id)->delete();
		if ($department) {
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

    public static  function getDepartmentID($manager_id){
        $department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('manager_id',$manager_id)->first();
        return $department->id;
    }
}