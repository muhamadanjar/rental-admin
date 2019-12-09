<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Rental\Models\Notification;
class BackendCtrl extends Controller
{
    public function __construct(){
        Auth::user();
    }

    public function getNotificationByUser(){
        $user = Auth::user();
        $data = Notification::whereUserId($user->id_user)->get();
        // $data = array();
        // foreach ($user->unreadNotifications as $notification) {
        //     // echo $notification->type();
        //     $data[] = $notification->type();
        // }
        return response()->json(['data'=>$data],200);
    }
}
