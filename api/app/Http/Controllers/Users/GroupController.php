<?php namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Group;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Monster Admin
 * Author: tuanda@steed.vn
 */

class GroupController extends Controller {

	public function getAll() {
		$listGroup = Group::on(Auth::getCS())->where('status', Group::STATUS_ACTIVE)->where('company_id', Auth::getCompanyId())->get();

		if (!$listGroup->isEmpty()) {
			$response = [
				'status' => true,
				'data' => $listGroup,
			];
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.DATA_NOT_FOUND',
			];
		}
		return Response::json($response);
	}

	public function getIndex() {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;

		$groupModel = new Group();
		$groupModel = $groupModel->on(Auth::getCS())->where('company_id', Auth::getCompanyId())->where('deleted', 0);

		$total = $groupModel->count();
		$listGroup = $groupModel->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();

		if (!$listGroup->isEmpty()) {
			$response = [
				'status' => true,
				'total' => $total,
				'data' => $listGroup,
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
			$group = new Group();
			$group->setConnection(Auth::getCS());
			$group->fill(Input::all());
			$group->user_id = Auth::getId();
			$group->company_id = Auth::getCompanyId();
			$group->time_create = $group->time_update = time();
			$group->status = Input::get('status') ? Group::STATUS_ACTIVE : Group::STATUS_DEACTIVE;
			$group->save();

			$group->set_group_permission($group->id, Input::get('group_permissions'));
			$response = [
				'status' => true,
				'message' => 'MESSAGE.CREATE_SUCCESS',
				'id' => $group->id,
			];
			return Response::json($response);
		}
	}

	public function putUpdate($id) {
		if ($this->_validate()) {
			$group = Group::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);

			if ($group->exists) {

				$group->setConnection(Auth::getCS());
				$group->fill(Input::all());
				$group->user_id = Auth::getId();
				$group->company_id = Auth::getCompanyId();
				$group->time_update = time();
				$group->status = Input::get('status') ? Group::STATUS_ACTIVE : Group::STATUS_DEACTIVE;
				$group->set_group_permission($id, Input::get('group_permissions'));

				$group->save();
				$response = [
					'status' => true,
					'message' => 'MESSAGE.UPDATE_SUCCESS',
					'id' => $group->id,
				];
			} else {
				$response = [
					'status' => false,
					'message' => 'MESSAGE.YOU_DO_NOT_HAVE_PERMISSION_TO_DO_THIS_ACTION',
				];
			}
		} else {
			$response = [
				'status' => false,
				'message' => 'MESSAGE.UPDATE_FAILED',
			];
		}
		return Response::json($response);
	}

	private function _validate() {

		$verifier = App::make('validation.presence');
		$verifier->setConnection(Auth::getCS());

		$rules = Group::$rules;
		$validator = Validator::make(Input::all(), $rules);
		$validator->setPresenceVerifier($verifier);
		if ($validator->fails()) {
			$this->setMessage(implode(" ", $validator->messages()->all('<p>:message</p>')));
			return false;
		}
		return true;
	}

	public function getView($id) {
		$group = Group::on(Auth::getCS())->where('company_id', Auth::getCompanyId())->find($id);
		if (!empty($group)) {

			Group::get_group_permissions($group);

			$permissions_full = $group->permissions;
			$group_permissions = $group->group_permissions;
			$template = array();
			if (!empty($permissions_full)) {
				foreach ($permissions_full as $perm) {
					$template[$perm->name]['perm_id'] = $perm->id;
					$template[$perm->name]['value'] = 0;
					$template[$perm->name]['title'] = $perm->title;
					if (isset($group_permissions[$perm->id])) {
						$template[$perm->name]['value'] = 1;
					}
				}
			}

			$domains = array();
			if (!empty($template)) {
				foreach ($template as $key => $value) {
					list($domain, $name, $action) = @explode('.', $key);
					if ($domain != "" && $name != "" && $action != "") {
						if (!empty($domain) && !array_key_exists($domain, $domains)) {
							$domains[$domain] = array();
						}
						if (!isset($domains[$domain][$name])) {
							$domains[$domain][$name] = array(
								$action => $value,
								'title' => $value["title"],
							);
						} else {
							$domains[$domain][$name][$action] = $value;
							$domains[$domain][$name]['title'] = $value["title"];
						}

						// Store the actions separately for building the table header
						if (!isset($domains[$domain]['actions'])) {
							$domains[$domain]['actions'] = array();
						}

						if (!in_array($action, $domains[$domain]['actions'])) {
							$domains[$domain]['actions'][] = $action;
						}
					}

				} //end foreach
			}

			$group->domains = $domains;
			$response = [
				'status' => true,
				'data' => $group,
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
		$group = Group::on(Auth::getCS())->find($id);
		$group->setConnection(Auth::getCS());
		$group->user_id = Auth::getId();
		$group->time_update = time();
		$group->deleted = 1;
		$group->save();

		if ($group) {
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