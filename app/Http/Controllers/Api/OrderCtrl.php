<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rental\Models\Order;
use App\User;
use App\Rental\Models\User as UserAdmin;
use Carbon\Carbon;
class OrderCtrl extends Controller{
    public function postOrder(Request $request){
        $auth = auth('api')->user();
        try {
            DB::beginTransaction();
            $orderCode = substr(number_format(time() * rand(),0,'',''),0,10);
            $model = new Order();
            $model->order_code = $orderCode;
            $model->order_user_id = $auth->id;
            $model->order_address_origin = $request->order_address_origin;
            $model->order_address_origin_lat = $request->order_address_origin_lat;
            $model->order_address_origin_lng = $request->order_address_origin_lng;

            $model->destination_address_origin = $request->destination_address_origin;
            $model->destination_address_origin_lat = $request->destination_address_origin_lat;
            $model->destination_address_origin_lng = $request->destination_address_origin_lng;

            $model->order_jenis = $request->order_jenis;
            $model->order_nominal = $request->order_nominal;
            $model->order_tgl_pesanan = Carbon::now();
            $model->order_keterangan = $request->order_keterangan;
            $model->order_status = 1;
            $exec = $model->save();
            DB::commit();
            if ($exec) {
                $admin = UserAdmin::first();
                $this->set_notification($message,$admin->id_user);
            }

            if(!$exec){
                DB::rollBack();
                return response()->json(['status'=>'error', 'message'=>'Error Accoured', 'code'=>404]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function postUpdateOrder(Request $request){
        try {
            $t = Trip::find($request->trip_id);
            if ($t == NULL) { return response()->json(['status'=>false,'message'=>'Data Trip tidak di temukan']);}
            $t->trip_status = $request->status;
            $t->save();
            return response()->json(['status'=>true,'message'=>'Status Trip {$t->trip_code}']);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
        
    }

    public function postTopUpSaldo(Request $request){
        try {
            $auth = Auth::guard('api')->user();
            if($auth){
                $kode = DB::table('request_saldo')->max('id');
                $noUrut = (int) substr($kode, 6, 3);
                $noUrut++;
                $char = "SLD";
                $kode = $char .date('His'). sprintf("%06s", $noUrut);
                DB::table('request_saldo')->insert(
                    ['req_from'=>$request->req_from,'req_code' => $kode, 'req_saldo' => $request->req_saldo, 'req_user_id' => $auth->id]
                );
                $data = DB::table('request_saldo')->where('req_code',$kode)->first();
                return response()->json(['status'=>true,'data'=>$data]);
            }else{
                return response()->json(['status'=>false]);
            }
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
    }

    public function postUploadBukti(Request $request){
        try {
            $auth = Auth::guard('api')->user();
            if($auth){
                $kode = DB::table('request_saldo')->max('id');
                $noUrut = (int) substr($kode, 6, 3);
                $noUrut++;
                $char = "SLD";
                $kode = $char .date('His'). sprintf("%06s", $noUrut);
                
                $image = $request->image;
                $name = md5($request->name.date('His'));
                $realImage = base64_decode($image);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $realImage, FILEINFO_MIME_TYPE);
                $filename = $name.'.jpg';

                DB::table('request_saldo')->insert(
                    ['req_file'=>$filename,'req_from'=>$request->req_from,'req_code' => $kode, 'req_saldo' => $request->req_saldo, 'req_user_id' => $auth->id,'status'=>0,'req_norek'=>$request->req_norek]
                );

                file_put_contents(public_path('files/uploads/bukti').DIRECTORY_SEPARATOR.$filename, $realImage);
                return response()->json(['status'=>true,'message'=>'Image Uploaded Successfully.']);    
            }

            
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
    }

    public function getDriverNearby(Request $request){
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius  = ($request->get('radius') == NULL ? 25: $request->get('radius') );
        $errors = [];
        
        if ($latitude != null && $longitude != null) {
            $location = UserLocation::select(DB::raw('id, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();
        }else{
            if ($latitude == null) {
                $errors['latitude'] = 'Latitude is Required';
            }
            if ($longitude == null) {
                $errors['longitude'] = 'Longitude is Required';
            }
        }
        
        if ($errors) {
            return response()->json(['status'=>false,'message'=>implode($errors,',')]);
        }
        return response()->json(['status'=>true,'data'=>$location]);
    }

    public function getHistoryOrderByUser($type = 1){
        try {
            $auth = Auth::guard('api');
            $user = $auth->user();
            if($user->isRole('customer')){
                $trip = Trip::where('trip_bookby',$user->id)->orderBy('trip_date','DESC')->get();
                $trip_count = Trip::where('trip_bookby',$user->id)->where('trip_type',$type)->orderBy('trip_date','DESC')->count();
            }else if($user->isRole('driver')){
                $trip = Trip::where('trip_driver',$user->id)->orderBy('trip_date','DESC')->get();
                $trip_count = Trip::where('trip_driver',$user->id)->where('trip_type',$type)->orderBy('trip_date','DESC')->count();
            }
            
            if ($trip_count<=0) {
                $message = 'Anda Belum Memiliki Transaksi';
            }
            return response()->json(['status'=>true,'data'=>$trip,'message'=>$message]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function getOrder(Request $request){
        $res = array();
        $status = 500;
        try {
            $auth = Auth::guard('api')->user();
            $message = "User tidak di temukan";
            if ($auth !== NULL) {
                $profile = $auth->profile;
                $message = "";
                $query = Trip::where(function($q) use ($auth){
                    if ($auth->isRole('driver')) {
                        $q->where('trip_driver',$auth->id);
                    }
                    $q->where('trip_status','<',Trip::STATUS_DECLINE);
                })->join('trip_detail','trip.trip_id','trip_detail.trip_id')
                ->join('tm_customer','trip_bookby','tm_customer.id')
                ->select('trip.*','tm_customer.name as trip_customer','trip_detail.*')
                ->orderBy('trip_date','DESC');
                $sql = $query->toSql();
                $bindings = $query->getBindings();
                $cp = $query->first();
                // dd($bindings);
                $message = ($cp===NULL) ? 'Tidak ada Transaksi Perjalanan' : 'Perjalanan dengan code '.$cp->trip_code; ;
                $res['data'] = $cp;
                $res['status'] = true;
                $status = $this->successStatus;
            }
            $res['message'] = $message;
            
            
        } catch (\Exception $e) {
            $status = 400;
            $res['error'] = true;
            $res['message'] = $e->getMessage();
        }
        return response()->json($res,$status);
    }


    private function set_notification($message, $user_id) {
        $insert['notif_date'] = date('Y-m-d H:i:s');
        $insert['notif_from'] = 'USER';
        $insert['message']    = $message;
        $insert['status']     = 0;
        $insert['user_id']    = $user_id;
        Notification::create($insert);
    }
}
