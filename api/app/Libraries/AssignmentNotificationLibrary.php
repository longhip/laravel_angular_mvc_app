<?php
/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 10/12/2015
 * Time: 2:56 PM
 */

namespace App\Libraries;

use App\Models\Assignment\AssignmentNotification;


class AssignmentNotificationLibrary {

    public static function create($assignment_id,$listData){
        foreach($listData as $data) {
            $notification = new AssignmentNotification();
            $notification->setConnection(Auth::getCS());
            $notification->assignment_id = $assignment_id;
            $notification->company_id = $data['company_id'];
            $notification->user_id = $data['to_user'];
            $notification->save();
        }
    }
    public static function update($assignment_id,$value){
        $notificationModel = AssignmentNotification::on(Auth::getCS())->where('company_id',Auth::getCompanyId());
        $notifications = $notificationModel->where('assignment_id',$assignment_id)->get();
        foreach($notifications as $notification){
            $notification->$value += 1;
            $notification->save();
        }
    }
    public static function getAllUser($assignment_id){
        $notificationModel = AssignmentNotification::on(Auth::getCS())->where('company_id',Auth::getCompanyId());
        $notifications = $notificationModel->where('assignment_id',$assignment_id)->lists('user_id');
        return $notifications;

    }
} 