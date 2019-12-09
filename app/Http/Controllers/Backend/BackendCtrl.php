<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
class BackendCtrl extends Controller
{
    public function __construct(){
        Auth::user();
    }

    public function getNotificationByUser(){
        $user = Auth::user();
        $data = array();
        foreach ($user->unreadNotifications as $notification) {
            echo $notification->type();
            // $data[] = $notification->type();
        }
        // return response()->json(['data'=>$data]);
    }
}
