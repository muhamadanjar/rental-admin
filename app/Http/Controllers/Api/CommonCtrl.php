<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Setting;
use App\Rental\Models\Promo;
use App\Rental\Models\RentPackage;
use App\Rental\Models\CarType;
use App\Rental\Models\Review;
class CommonCtrl extends Controller{
    protected $successStatus = 200;
    public function getTypeCar(Request $request){
        $type = new CarType();
        if ($request->get('id') != null) {
            $id = $request->get('id');
            $data = $type->where('id',$id)->get();
        }else{
            $data = $type->get();
        }
        
        foreach($data as $k =>$v){
            $data[$k]->path_url = $v->imagePath;
        }
        return response()->json(['status'=>true,'data'=>$data]);
    }
    public function getRentPackage($id = null){
        $rent = new RentPackage();
        if ($id == NULL) {
            $data = $rent->active()->get();
        }else{
            $data = $rent->active()->where('rp_car_type',$id)->get();
        }
        foreach($data as $k =>$v){
            $data[$k]->path_url = $v->imagePath;
        }
        return response()->json(['status'=>true,'data'=>$data]);
    }

    public function getPromo($id = NULL){
        $response = [];
        $promo = Promo::where('tgl_mulai','>=',date('Y-m-d'))->orWhere('tgl_akhir','<=',date('Y-m-d'))->orderBy('tgl_mulai','DESC')->select()->get();
        $p = new Promo();
        foreach($promo as $key => $v){
            $v->image_path = $v->imagepath;
            $v->discount = ($v->discount === NULL) ?  0:$v->discount;
        }
        $response['status'] = true;
        $response['data'] = $promo;
        return response()->json($response,$this->successStatus);
    }

    public function getSettings(){
        $setting = Setting::pluck('setting_value', 'setting_key');
        return response()->json(['status'=>true,'data'=>$setting],200);
    }

    public function getServiceType(){
        $res = array();
        try {
            $st = ServiceType::orderBy('id')->get();
            $res['status'] = true;
            $res['data'] = $st;
            $res['message'] = "Mengambil data Service Type";
            return response()->json($res,200);
        } catch (\Throwable $th) {
            $res['status'] = false;
            $res['message'] = "Gagal Mengambil Service Type";
            return response()->json($res,500);
        }
    }

    public function getBank($id=NULL){
        $t = DB::table('sys_ms_bank');
        if ($id !== NULL) {
            $t->where('bank_code',$id);
        }
        $a = $t->get();
        return response()->json(['status'=>true,'data'=>$a]);
    }

    public function postReview(Request $request){
        $auth = $request->user('api');
        if ($auth) {
            Review::create([
                'trip_code'=>$request->trip_code,
                'user_id'=>$auth->id,
                'driver_id'=>$request->driver_id,
                'rate'=>$request->rate,
                'description'=>$request->description,
                'date' => Carbon::now()
            ]);
            return response()->json(array('message'=>'Review Berhasil ditambahkan'));
        }
    }
}
