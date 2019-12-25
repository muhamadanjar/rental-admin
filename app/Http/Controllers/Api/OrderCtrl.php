<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Rental\Models\Order;
use App\Rental\Models\ReqSaldo;
use App\Rental\Models\User as UserAdmin;
use App\Rental\Models\Notification;
use App\User;
use Carbon\Carbon;
use Config;
class OrderCtrl extends Controller{
    public function postOrder(Request $request){
        $auth = auth('api')->user();
        try {
            
            if (!$auth) {
                return response()->json(array('message'=>trans('auth.not_found')));
            }

            if ($auth->isavail > 1) {
                return response()->json(array('message'=>trans('order.intransaction')));
            }
            DB::beginTransaction();
            $noRef = substr(number_format(time() * rand(),0,'',''),0,10);
            $orderCode = substr(number_format(time() * rand(),0,'',''),0,10);
            $model = new Order();
            $model->order_code = $orderCode;
            $model->order_user_id = $auth->id;
            $model->order_address_origin = $request->order_address_origin;
            $model->order_address_origin_lat = $request->order_address_origin_lat;
            $model->order_address_origin_lng = $request->order_address_origin_lng;

            $model->order_address_destination = $request->order_address_destination;
            $model->order_address_destination_lat = $request->order_address_destination_lat;
            $model->order_address_destination_lng = $request->order_address_destination_lng;

            $model->order_jenis = $request->order_jenis;
            $model->order_nominal = $request->order_nominal;
            $model->order_tgl_pesanan = Carbon::now();
            $model->order_keterangan = $request->order_keterangan;
            $model->order_status = 0;
            $exec = $model->save();
            if ($exec) {
                User::where('id',$auth->id)->update(['isavail'=>2]);
                $admin = UserAdmin::first();
                $message = 'Ada Pemesanan Baru';
                $this->set_notification($message,$admin->id_user);    
            }

            if(!$exec){
                DB::rollBack();
                return response()->json(['status'=>'error', 'message'=>'Error Accoured', 'code'=>404]);
            }
            
            DB::commit();
            return response()->json(array('message'=>trans('order.success')));
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
    }

    public function postUpdateOrder(Request $request){
        try {
            $t = Order::find($request->order_id);
            $auth = $request->user('api');
            if ($t == NULL) { return response()->json(['status'=>false,'message'=>trans('not_found')]); }
            if ($t->order_status > $request->status) {
                return response()->json(['message'=>'status tidak bisa di ulang']);
            }
            if($auth->isanggota != Config::get('app.user_driver')){
                return response()->json(['message'=>trans('order.not_driver')]);
            }
            if ($t->order_driver_id != $auth->id) {
                return response()->json(['message'=>trans('order.not_allowed')]);
            }
            $t->order_status = $request->status;
            $t->save();
            if ($request->status == 6) {
                $user  = User::find($request->user('api')->id);
                $user->isavail = 1;
                $user->save();
            }
            return response()->json(['status'=>true,'message'=>"Status Trip {$t->order_code}"]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
        
    }

    public function postTopUpSaldo(Request $request){
        try {
            $auth = Auth::guard('api')->user();
            if($auth){
                $kode = ReqSaldo::max('id');
                $noUrut = (int) substr($kode, 6, 3);
                $noUrut++;
                $char = "SLD";
                $kode = $char .date('His'). sprintf("%06s", $noUrut);
                ReqSaldo::insert(
                    ['req_from'=>$request->req_from,'req_code' => $kode, 'req_saldo' => $request->req_saldo, 'req_user_id' => $auth->id]
                );
                $data = ReqSaldo::where('req_code',$kode)->first();
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
                $kode = ReqSaldo::max('id');
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

                ReqSaldo::insert(
                    ['req_file'=>$filename,'req_from'=>$request->req_from,'req_code' => $kode, 'req_saldo' => $request->req_saldo, 'req_user_id' => $auth->id,'status'=>0,'req_norek'=>$request->req_norek]
                );

                file_put_contents(public_path('storage/uploads/bukti').DIRECTORY_SEPARATOR.$filename, $realImage);
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


    public function set_notification($message, $user_id) {
        try {
            $insert['notif_date'] = date('Y-m-d H:i:s');
            $insert['notif_from'] = 'USER';
            $insert['message']    = $message;
            $insert['status']     = 0;
            $insert['user_id']    = $user_id;
            $insert['jenis']    = 'ORDER';
            $notif = Notification::insert($insert);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
        
        // dd($notif);


    }
}
