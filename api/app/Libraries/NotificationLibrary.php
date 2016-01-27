<?php
/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 10/12/2015
 * Time: 2:59 PM
 */

namespace App\Libraries;
use App\Models\Notification;

class NotificationLibrary {

    public static function create($data){
        $notification = new Notification;
        $notification->setConnection(Auth::getCS());
        $notification->insert($data);
    }

} 