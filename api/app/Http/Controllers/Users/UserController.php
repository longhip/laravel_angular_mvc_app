<?php namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Department\Branch;
use App\Models\Department\Department;
use App\Models\Group;
use App\Models\Position\Position;
use App\Models\User;
use App\Models\Company;
use App\Models\UserToken;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Master\DBManagement;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class UserController extends Controller {

	public function postLogin() {
		$email = Input::get('email');
		$password = Input::get('password');		
		if (Auth::login($email, $password)) {
			$user = Auth::user();
			$user->avatar = ($user->avatar) ? url($user->avatar) : '';
			$user->position_name = ($user->position_id) ? Position::getName($user->position_id) : '';
			$user->department_name = ($user->department_id) ? Department::getName($user->department_id) : '';
			$user->branch_name = ($user->branch_id) ? Branch::getName($user->branch_id) : '';
			$response = [
				'status' => true,
				'message' => Auth::getMessage(),
				'data' => $user,
			];
		} else {
			$response = [
				'status' => false,
				'message' => Auth::getMessage(),
			];
		}

		return Response::json($response);
	}

	public function getCheckToken() {
		$response = [
			'status' => true,
		];
		return Response::json($response);
	}

	public function getLogout() {
		UserToken::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('token', Input::get('token'))->delete();
		$response = [
			'status' => true,
			'message' => 'MESSAGE.LOGOUT_SUCCESSFUL',
		];
		return Response::json($response);
	}

    public function getIndex() {
        $data = [];
        $departments = Department::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('deleted',0)->get();
        foreach($departments as $department){
            $data[] = array(
                'id'=>$department->id,
                'name'=>$department->name,
                'users'=>$this->getUserForDepartment($department->id)
            );
        }
        if(!Empty($data)){
            $response = [
                'status'=>true,
                'data'=>$data
            ];
        }
        else{
            $response = [
                'status'=>false,
                'data'=>$data
            ];
        }
        return response()->json($response);
    }

    public function getDetail($userID) {
        $user = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->find($userID);
        if (!empty($user)) {
            $data = [
                'id'=>$user->id,
                'fullname'=>$user->fullname,
                'email'=>$user->email,
                'phone'=>$user->phone,
                'gender'=>$user->gender,
                'avatar'=>$user->avatar,
                'department_id'=>$user->department_id,
                'position_id'=>$user->position_id,
                'birthday'=>timeToDate($user->birthday),
                'address'=>$user->address,
                'signature'=>$user->signature,
                'department_name'=>Department::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$user->department_id)->first()->name,
                'position_name'=>Position::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$user->position_id)->first()->name,
            ];
            $response = [
                'status'=>true,
                'data'=>$data
            ];
        } else {
            $response = [
                'status'=>false,
            ];
        }

        return Response::json($response);
    }


    public function postCreate() {
        if ($this->_validate()) {
            $user = new User();
            $user->setConnection(Auth::getCS());
            $user->fill(Input::all());
            $user->fullname = Input::get('fullname');
            $user->birthday = dateToTime(Input::get('birthday'));
            $user->gender = Input::get('gender');
            $user->company_id = Auth::getCompanyId();
            $user->department_id = Input::get('department_id');
            $user->branch_id = Input::get('branch_id');
            $user->position_id = Input::get('position_id');
            $user->avatar = (Input::get('avatar') == '') ? 'avatar.jpg' : Input::get('avatar');
            $user->password = Hash::make(Input::get('password'));
            $user->time_create = $user->time_update = time();
            $user->status = Auth::STATUS_ACTIVE;
            //update group if has permission

            $group = Group::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id', Input::get('group_id'))->first();
            if (!empty($group)) {
                $user->group_id = $group->id;
            }

            $user->save();
            $response = [
                'status' => true,
                'message' => 'MESSAGE.CREATE_SUCCESS',
                'id' => $user->id,
                'position_name' => Position::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$user->position_id)->first()->name,
            ];
        } else {
            $response = [
                'status' => false,
                'message' => $this->getMessage(),
            ];
        }
        return Response::json($response);
    }

    /* User Detail Update */
    public function postUpdate($userID) {
        $type = Input::has('type') ? Input::get('type'):'';
        $user = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$userID)->first();
        if($type === 'birthday'){
            $user->birthday = dateToTime(Input::get('birthday'));
            $user->save();
            $response = [
                'status'=>true,
            ];
            return response()->json($response);
        }
        else{
            $user->$type = Input::get($type);
            $user->save();
            $response = [
                'status'=>true,
            ];
        }
        return response()->json($response);
    }

    public function postUpdatePassword($userID){
        $user = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$userID)->first();
        if(Hash::check(Input::get('old_password'),$user->password)){
            $user->password = Hash::make(Input::get('new_password'));
            $user->save();
            $response = [
                'status'=>true,
                'message'=>'MESSAGE.UPDATE_SUCCESS'
            ];
            return Response::json($response);
        }
        else{
            $response = [
                'status'=>false,
                'message'=>'MESSAGE.PASSWORD_OLD_NOT_INCORRECT'
            ];
        }
        return Response::json($response);

    }

    public function postUpdateAvatar($userID){
        $user = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$userID)->first();
        $user->avatar = Input::get('avatar_preview');
        $user->save();
        $response = [
            'status'=>true,
            'message'=>'MESSAGE.UPDATE_SUCCESS',
            'data'=>$user->avatar
        ];
        return Response::json($response);
    }
    public function postUpdateSignature($userID){
        $user = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('id',$userID)->first();
        $user->signature = Input::get('signature_preview');
        $user->save();
        $response = [
            'status'=>true,
            'message'=>'MESSAGE.UPDATE_SUCCESS',
            'data'=>$user->signature
        ];
        return response()->json($response);


    }
    /* User Detail Update */

    /* Delete User */
    public function deleteItem($id) {
        $user = User::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('id', $id)->first();
        $user->deleted = 1;
        $user->save();
        if ($user) {
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
    /* Delete User */

    /* Validate */
    private function _validate() {

        $verifier = App::make('validation.presence');

        $rules = User::$rules;
        $validator = Validator::make(Input::all(), $rules);
        $validator->setPresenceVerifier($verifier);
        if ($validator->fails()) {
            $this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
            return false;
        }
        return true;
    }
    /* Validate */






    /* Services */
    public function getUserForDepartment($department_id){
        $data = [];
        $users = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('deleted',0)->where('department_id',$department_id)->get();
        $list_position = Position::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('deleted',0)->get();
        foreach($list_position as $position){
            $list_position[$position->id] = $position;
        }
        foreach($users as $user){
            $data[] = array(
                'id'=>$user->id,
                'fullname'=>$user->fullname,
                'avatar'=>$user->avatar,
                'email'=>$user->email,
                'phone'=>$user->phone,
                'position_id'=>$user->position_id,
                'position_name'=>$list_position[$user->position_id]['name']
            );
        }
        return $data;
    }


	public function checkPermission($permission = '') {
		if (!empty($permission)) {
			if (Auth::HasPermission($permission)) {
				$response = [
					'status' => true,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
		} else {
			$response = [
				'status' => false,
			];
		}

		return Response::json($response);
	}

    public function getList() {
        $groupID = Input::has('groupID') ? (int) Input::get('groupID') : 0;
        $departmentID = Input::has('departmentID') ? (int) Input::get('departmentID') : 0;
        $branchID = Input::has('branchID') ? (int) Input::get('branchID') : 0;
        $positionID = Input::has('positionID') ? (int) Input::get('positionID') : 0;
        $keyword = Input::has('keyword') ? Input::get('keyword') : '';
        $str_seach = Input::has('str_seach') ? Input::get('str_seach') : '';
        $type = Input::has('type') ? Input::get('type') : '';
 
        $userModel = new User();
        $userModel = $userModel->on(Auth::getCS());
        if ($groupID > 0) {
            $group = Group::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($groupID);
            if (!empty($group)) {
                $userModel = $userModel->where('group_id', $groupID);
            }
        }
        if ($departmentID > 0) {
            $department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($departmentID);
            if (!empty($department)) {
                $userModel = $userModel->where('department_id', $departmentID);
            }
        }
        if ($branchID > 0) {
            $branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($branchID);
            if (!empty($branch)) {
                $userModel = $userModel->where('branch_id', $branchID);
            }
        }
        if ($positionID > 0) {
            $position = Position::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($positionID);
            if (!empty($position)) {
                $userModel = $userModel->where('position_id', $positionID);
            }
        }
        if (!empty($keyword)) {
            if (filter_var($keyword, FILTER_VALIDATE_EMAIL)) {
                $userModel = $userModel->where('email', $keyword);
            } else {
                $userModel = $userModel->where('phone', $keyword);
            }
        }
 
        if (!empty($str_seach)) {
            $userModel = $userModel->where('fullname', 'like', '%' . $str_seach . '%');
        }
 
        if($type == 'assignment') {
            $userModel = $userModel->whereIn('position_id', array('3','4','5')); 
        }
 
        if($type == 'document') {
            $userModel = $userModel->where('position_id', '7');
        }
 
        $user = $userModel->where('status', Auth::USER_STATUS_ACTIVE)->where('type', 0)->where('deleted', 0)->get();
        if (!$user->isEmpty()) {
            $group = Group::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
            $department = Department::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
            $branch = Branch::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
            $position = Position::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->get();
            $listGroup = $listDepartment = $listBranch = $listPosition = [];
            if (!$group->isEmpty()) {
                foreach ($group as $oneGroup) {
                 $listGroup[$oneGroup->id] = $oneGroup;
                }
            }   
            if (!$department->isEmpty()) {
                foreach ($department as $oneDepartment) {
                 $listDepartment[$oneDepartment->id] = $oneDepartment;
                }
            }
            if (!$branch->isEmpty()) {
                foreach ($branch as $oneBranch) {
                    $listBranch[$oneBranch->id] = $oneBranch;
                }
            }
            if (!$position->isEmpty()) {
                foreach ($position as $onePosition) {
                    $listPosition[$onePosition->id] = $onePosition;
                }
            }
            foreach ($user as $k => $oneUser) {
                $user[$k]->group = isset($listGroup[$oneUser->group_id]) ? $listGroup[$oneUser->group_id] : '';
                $user[$k]->department = isset($listDepartment[$oneUser->department_id]) ? $listDepartment[$oneUser->department_id] : '';
                $user[$k]->branch = isset($listBranch[$oneUser->branch_id]) ? $listBranch[$oneUser->branch_id] : '';
                $user[$k]->position = isset($listPosition[$oneUser->position_id]) ? $listPosition[$oneUser->position_id] : '';
                $user[$k]->avatar = ($oneUser->avatar) ? url($oneUser->avatar) : '';
            }
            $response = [
                'status' => true,
                'data' => $user,
            ];
        } else {
            $response = [
                'status' => false,
            ];
        }
        return Response::json($response);
    }
    public function index() {
        $users = User::on(Auth::getCS())->where('company_id',Auth::getCompanyId())->where('deleted',0)->get();
        if(!$users->isEmpty()){
            $response = [
                'status'=>true,
                'data'=>$users
            ];
        }
        else{
            $response = [
                'status'=>false,
                'data'=>[]
            ];
        }
        return response()->json($response);
    }
};
