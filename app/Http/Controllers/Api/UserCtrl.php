<?php namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\User as UserMobile;
class UserCtrl extends Controller
{
    public function getUserLocation(){
        $data  = array();
        $ul = UserMobile::orderBy('id')->where('isanggota',1)->get();
        foreach ($ul as $k => $v) {
            $data[$k]['users'] = $v;
            $data[$k]['meta'] = $v->user_metas;
            $data[$k]['mobil'] = $v->mobil;
        }
        return response()->json(['status'=>true,'data'=>$data],200);
    }

    

    public function postUpdateLocation(Request $request){
        try {
            $user = Auth::guard('api')->user();
            if($user){
                $this->moderatorrepo->updateUserLocation($user->id,$request);
                return response()->json(['status'=>true,'data'=>$user,'message'=>'driver telah update lokasi'],200);
            }else{
                return response()->json(['status'=>false,'message'=>'User tidak di temukan']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
        
    }
    public function userTopUpWallet(Request $request){
        try {
            $user = Auth::guard('api')->user();
            $up = UserProfile::where('user_id',$user->id)->first();
            if($up != null){
                $up->wallet = $request->wallet;
                $up->save();
            }else{
                $up =  new UserProfile();
                $up->user_id = $user->id;
                $up->wallet = $request->wallet;
                $up->save();
            }
            return response()->json(['status'=>true,'message'=>'Anda Berhasil Menambah Dana']);    
        } catch (\Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);    
        }
        
    }

    public function postChangeStatusOnline(){
        try {
            $user = Auth::guard('api')->user();
            $profile = UserProfile::where('user_id',$user->id)->first();
            if($profile !== null){
                $isonline = $user->profile;
                $profile = DB::table('user_profile')
                ->where('user_id',$user->id)
                ->update(['isonline'=>!$isonline->isonline]);    
            }else{
                $p = new UserProfile();
                $p->user_id = $user->id;
                $p->isonline = 1;
                $p->save();
            }
            return response()->json(['status'=>true,'data'=>$profile,'message'=>'Data Online Berhasil di ubah']);
        } catch (\Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }

    
}
