<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\User as UserMobile;
use Carbon\Carbon;
use App\Rental\Models\Notification;
class BackendCtrl extends Controller
{
    public function __construct(){
        Auth::user();
    }

    public function getNotificationByUser(){
        $user = Auth::user();
        $data = Notification::whereUserId($user->id_user)->orderBy('notif_date','DESC')->limit(10)->get();
        $count = Notification::whereUserId($user->id_user)->count();
        foreach ($data as $key => $value) {
            $data[$key]->notifHuman = Carbon::createFromTimeStamp(strtotime($value->notif_date))->diffForHumans();
        }
        // $data = array();
        // foreach ($user->unreadNotifications as $notification) {
        //     // echo $notification->type();
        //     $data[] = $notification->type();
        // }
        return response()->json(['data'=>$data,'count'=>$count,'message'=>'Anda Memiliki '.$count.' Pemberitahuan'],200);
    }

    public function getUserLocation(){
        $data  = array();
        $ul = UserMobile::orderBy('id')->where('isanggota',2)->get();
        $countAktif = UserMobile::orderBy('id')->where('isanggota',2)->where('isavail',1)->count();
        $countOrdered = UserMobile::orderBy('id')->where('isanggota',2)->where('isavail',2)->count();
        $countOffline = UserMobile::orderBy('id')->where('isanggota',2)->where('isavail',0)->count();
        $countUser = array($countAktif,$countOrdered,$countOffline);
        foreach ($ul as $k => $v) {
            $data[$k]['users'] = $v;
            $data[$k]['meta'] = $v->user_metas;
            $data[$k]['mobil'] = $v->mobil;
        }
        return response()->json(['status'=>true,'data'=>$data,'count'=>$countUser],200);
    }
}
