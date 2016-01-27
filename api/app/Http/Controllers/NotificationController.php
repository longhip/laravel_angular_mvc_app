<?php namespace App\Http\Controllers;
use App\Libraries\Auth;
use App\Models\Assignment\Assignment;
use App\Models\Notification;
use Illuminate\Support\Facades\Input;

/**
 * Monster Admin
 * Author: longnd@steed.vn
 */

class NotificationController extends Controller {

	public function getAll() {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;
		//if (Auth::HasPermission('permission.president.manager')) {
		$notificationModel = new Notification;
		$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
		$notifications = $notificationModel->where('deleted', 0)->where('isWatched', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();
		$total = $notificationModel->where('isWatched', 0)->where('deleted', 0)->count();
		if ($notifications) {
			foreach ($notifications as $notification) {
				$data[] = array(
					'id' => $notification->id,
					'from_user_name' => Auth::getName($notification->user_id),
					'from_user_avatar' => Auth::getAvatar($notification->user_id),
					'link' => $notification->link,
					'message' => $notification->name,
					'to_user_name' => Auth::getName($notification->to_user),
					'time_create' => $notification->time_create,
					'isWatched' => $notification->isWatched,
				);
			}
			$response = [
				'status' => true,
				'data' => $data,
				'total' => $total,
			];
		} else {
			$response = [
				'status' => false,
			];
		}
		return response()->json($response);
		//}
		if (Auth::HasPermission('permission.generalinchief.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$notifications = $notificationModel->where('deleted', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->where('department_id', Auth::getDepartmentID())->get();
			$total = $notificationModel->where('department_id', Auth::getDepartmentID())->where('deleted', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'data' => $data,
					'total' => $total,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		if (Auth::HasPermission('permission.deputy.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$notifications = $notificationModel->where('deleted', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->where('department_id', Auth::getDepartmentID())->get();
			$total = $notificationModel->where('department_id', Auth::getDepartmentID())->where('deleted', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'data' => $data,
					'total' => $total,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		if (Auth::HasPermission('permission.appraiser.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentModel = new Assignment;
			$assignmentModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentStaffMainCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_process', 0)->count();
			$assignmentDocumentCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_document', 0)->count();
			$notifications = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();
			$count = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'count' => $count,
					'data' => $data,
					'assignmentStaffMainCount' => $assignmentStaffMainCount,
					'assignmentDocumentCount' => $assignmentDocumentCount,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		if (Auth::HasPermission('permission.archival.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$notification = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();
			$total = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->count();
			if ($notification) {
				$response = [
					'status' => true,
					'total' => $total,
					'data' => $notification,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
	}

	// Chapter
	public function getCountOfNotification() {

		/* Giám đốc */
//		$data = [];
//		//if (Auth::HasPermission('permission.president.manager')) {
//		$notificationModel = new Notification();
//		$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
//		$notifications = $notificationModel->where('deleted', 0)->where('isWatched', 0)->orderBy('time_create', 'desc')->take(12)->get();
//		$count = $notificationModel->where('deleted', 0)->where('isWatched', 0)->count();
//		if (!$notifications->isEmpty()) {
//			foreach ($notifications as $notification) {
//				$data[] = array(
//					'id' => $notification->id,
//					'from_user_name' => Auth::getName($notification->user_id),
//					'from_user_avatar' => Auth::getAvatar($notification->user_id),
//					'link' => $notification->link,
//					'message' => $notification->name,
//					'to_user_name' => Auth::getName($notification->to_user),
//					'time_create' => $notification->time_create,
//					'isWatched' => $notification->isWatched,
//				);
//			}
//			$response = [
//				'status' => true,
//				'count' => $count,
//				'data' => $data,
//			];
//		} else {
//			$response = [
//				'status' => false,
//			];
//		}
		$response = [
			'status' => false,
		];
		return response()->json($response);
		//}
		/* Trưởng phòng */
		if (Auth::HasPermission('permission.generalinchief.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$notifications = $notificationModel->where('deleted', 0)->orderBy('time_create', 'desc')->where('department_id', Auth::getDepartmentID())->take(12)->get();
			$count = $notificationModel->where('deleted', 0)->where('department_id', Auth::getDepartmentID())->where('isWatched', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'count' => $count,
					'data' => $data,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		/* Phó phòng */
		if (Auth::HasPermission('permission.deputy.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$notifications = $notificationModel->where('deleted', 0)->orderBy('time_create', 'desc')->where('department_id', Auth::getDepartmentID())->take(12)->get();
			$count = $notificationModel->where('deleted', 0)->where('isWatched', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'count' => $count,
					'data' => $data,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		/* Giám định viên */
		if (Auth::HasPermission('permission.appraiser.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentModel = new Assignment;
			$assignmentModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentStaffMainCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_process', 0)->count();
			$assignmentDocumentCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_document', 0)->count();
			$notifications = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->orderBy('time_create', 'desc')->take(12)->get();
			$count = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->where('isWatched', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'count' => $count,
					'data' => $data,
					'assignmentStaffMainCount' => $assignmentStaffMainCount,
					'assignmentDocumentCount' => $assignmentDocumentCount,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		if (Auth::HasPermission('permission.archival.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentModel = new Assignment;
			$assignmentModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentStaffMainCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_process', 0)->count();
			$assignmentDocumentCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_document', 0)->count();
			$notification = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->orderBy('time_create', 'desc')->take(12)->get();
			$count = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->where('isWatched', 0)->count();
			if ($notification) {
				$response = [
					'status' => true,
					'count' => $count,
					'assignmentStaffMainCount' => $assignmentStaffMainCount,
					'assignmentDocumentCount' => $assignmentDocumentCount,
					'data' => $notification,

				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
	}
	public function getUpdate($notification_id) {
		$notificationModel = new Notification;
		$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
		$notification = $notificationModel->where('deleted', false)->where('id', $notification_id)->first();
		if ($notification->to_user == Auth::getId()) {
			$notification->isWatched = 1;
			$notification->save();
			if ($notification) {
				$response = [
					'status' => true,
				];
			}
		} else {
			$response = [
				'status' => true,
			];
		}
		return response()->json($response);
	}
//    public function getSearch($value){
	//        $currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
	//        $itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;
	//
	//        if(Auth::HasPermission('permission.president.manager')) {
	//            $notificationModel = new Notification;
	//            $notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
	//            $notifications = $notificationModel->where('name', 'LIKE', '%' . $value . '%')->where('deleted', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->where('isWatched',0)->get();
	//            $total = $notificationModel->where('deleted', 0)->where('name', 'LIKE', '%' . $value . '%')->where('isWatched',0)->count();
	//            $count = $notificationModel->where('deleted',0)->where('isWatched',0)->count();
	//            if($notifications){
	//                foreach($notifications as $notification){
	//                    $data[] = array(
	//                        'id'=>$notification->id,
	//                        'from_user_name'=>Auth::getName($notification->user_id),
	//                        'from_user_avatar'=>Auth::getAvatar($notification->user_id),
	//                        'message'=>$notification->name,
	//                        'to_user_name'=>Auth::getName($notification->to_user),
	//                        'time_create'=>$notification->time_create,
	//                        'isWatched'=>$notification->isWatched,
	//                    );
	//                }
	//                $response = [
	//                    'status'=>true,
	//                    'count'=> $count,
	//                    'total'=>$total,
	//                    'data'=>$data,
	//                ];
	//            }
	//            else{
	//                $response = [
	//                    'status'=>false,
	//                ];
	//            }
	//            return response()->json($response);
	//        }
	//    }
	public function getSort($value) {
		$currentPage = Input::has('currentPage') ? (int) Input::get('currentPage') : 1;
		$itemsPerPage = Input::has('itemsPerPage') ? (int) Input::get('itemsPerPage') : 20;
		//if (Auth::HasPermission('permission.president.manager')) {
		$notificationModel = new Notification;
		$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
		$notifications = $notificationModel->where('deleted', 0)->where('isWatched', 0)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();
		$count = $notificationModel->where('deleted', 0)->where('isWatched', 0)->count();
		if ($notifications) {
			foreach ($notifications as $notification) {
				$data[] = array(
					'id' => $notification->id,
					'from_user_name' => Auth::getName($notification->user_id),
					'from_user_avatar' => Auth::getAvatar($notification->user_id),
					'link' => $notification->link,
					'message' => $notification->name,
					'to_user_name' => Auth::getName($notification->to_user),
					'time_create' => $notification->time_create,
					'isWatched' => $notification->isWatched,
				);
			}
			$response = [
				'status' => true,
				'count' => $count,
				'data' => $data,
			];
		} else {
			$response = [
				'status' => false,
			];
		}
		return response()->json($response);
		//}
		if (Auth::HasPermission('permission.generalinchief.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$notifications = $notificationModel->where('deleted', 0)->where('isWatched', $value)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->where('department_id', Auth::getDepartmentID())->get();
			$total = $notificationModel->where('department_id', Auth::getDepartmentID())->where('isWatched', $value)->where('deleted', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'data' => $data,
					'total' => $total,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
		if (Auth::HasPermission('permission.appraiser.manager')) {
			$notificationModel = new Notification;
			$notificationModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentModel = new Assignment;
			$assignmentModel->setConnection(Auth::getCS())->where('company_id', Auth::getCompanyId());
			$assignmentStaffMainCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_process', 0)->count();
			$assignmentDocumentCount = $assignmentModel->where('staff_main_id', Auth::getId())->where('read_document', 0)->count();
			$notifications = $notificationModel->where('to_user', Auth::getId())->where('deleted', 0)->where('isWatched', $value)->orderBy('time_create', 'desc')->take($itemsPerPage)->skip($itemsPerPage * ($currentPage - 1))->get();
			$count = $notificationModel->where('to_user', Auth::getId())->where('isWatched', $value)->where('deleted', 0)->count();
			if ($notifications) {
				foreach ($notifications as $notification) {
					$data[] = array(
						'id' => $notification->id,
						'from_user_name' => Auth::getName($notification->user_id),
						'from_user_avatar' => Auth::getAvatar($notification->user_id),
						'link' => $notification->link,
						'message' => $notification->name,
						'to_user_name' => Auth::getName($notification->to_user),
						'time_create' => $notification->time_create,
						'isWatched' => $notification->isWatched,
					);
				}
				$response = [
					'status' => true,
					'count' => $count,
					'data' => $data,
					'assignmentStaffMainCount' => $assignmentStaffMainCount,
					'assignmentDocumentCount' => $assignmentDocumentCount,
				];
			} else {
				$response = [
					'status' => false,
				];
			}
			return response()->json($response);
		}
	}
}
