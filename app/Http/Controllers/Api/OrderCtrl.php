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
use Auth;
class OrderCtrl extends Controller{

    public function __construct(Request $request)
    {
        $this->auth = $request->user('api');
    }
    public function postOrder(Request $request){
        $auth = auth('api')->user();
        try {
            
            if (!$auth) {
                return response()->json(array('status'=>'error','code'=>400,'message'=>trans('auth.not_found')));
            }

            if ($auth->isavail > 1) {
                return response()->json(array('status'=>'error','code'=>400,'message'=>trans('order.intransaction')));
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
            return response()->json(array('status'=>'success','message'=>trans('order.success'),'code'=>200));
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
    }

    public function postCheckOrder(Request $request)
    {
        $order = Order::where('order_user_id',$this->auth->id)->where('order_status','<=',4)->first();
        if ($order) {
            return response()->json(['status'=>'success','data'=>$order,'code'=>200]);
        }

    }

    public function postUpdateOrder(Request $request){
        try {
            $t = Order::find($request->order_id);
            $auth = $this->auth;

            if (!$auth) {
                return response()->json(array('status'=>'error','code'=>400,'message'=>trans('auth.not_found')));
            }
            
            if ($t == NULL) { return response()->json(['status'=>false,'message'=>trans('not_found')]); }
            if ($t->order_status > $request->status) {
                return response()->json(['status'=>'error','message'=>'status tidak bisa di ulang']);
            }elseif($t->order_status == $request->status){
                return response()->json(['status'=>'error','message'=>trans('order.status_same')]);
            }elseif($t->order_status == 4 || $t->order_status == 5 || $t->order_status == 6){
                return response()->json(['status'=>'error','message'=>trans('order.status_cant_change')]);
            }
            if($auth->isanggota != Config::get('app.user_driver')){
                return response()->json(['status'=>'error','message'=>trans('order.not_driver')]);
            }
            if ($t->order_driver_id != $auth->id) {
                return response()->json(['status'=>'error','message'=>trans('order.not_allowed')]);
            }
            
            $t->order_status = $request->status;
            $t->save();
            if ($request->status == 6) {
                $user  = User::find($request->user('api')->id);
                $user->isavail = 1;
                $user->save();
            }
            return response()->json(['status'=>'success','message'=>"Status Trip {$t->order_code}"]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>'success','message'=>$th->getMessage()]);
        }
        
    }

    public function postTopUpSaldo(Request $request){
        try {
            $auth = $this->auth;
            if($auth){
                $kode = ReqSaldo::max('id');
                $noUrut = (int) substr($kode, 6, 3);
                $noUrut++;
                $char = "SLD";
                $kode = $char .date('His'). sprintf("%06s", $noUrut);
                ReqSaldo::insert(
                    ['req_from'=>$request->req_from,'req_code' => $kode, 'req_saldo' => $request->req_saldo, 'req_user_id' => $auth->id]
                );
                $message = 'Ada User Meminta Top up Saldo';
                $insert['notif_date'] = date('Y-m-d H:i:s');
                $insert['notif_from'] = 'USER';
                $insert['message']    = $message;
                $insert['status']     = 0;
                $insert['user_id']    = $auth->id;
                $insert['jenis']    = 'TOPUPSALDO';
                $notif = Notification::insert($insert);
                $data = ReqSaldo::where('req_code',$kode)->first();
                return response()->json(['status'=>true,'data'=>$data]);
            }else{
                return response()->json(['status'=>false,'message'=>trans('auth.not_found')]);
            }
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'message'=>$th->getMessage()]);
        }
    }

    public function postUploadBukti(Request $request){
        try {
            $auth = Auth::guard('api')->user();
            $targetDir = public_path('storage/uploads/bukti');
            if($auth){
                $kode = ReqSaldo::max('id');
                $noUrut = (int) substr($kode, 6, 3);
                $noUrut++;
                $char = "SLD";
                $kode = $char .date('His'). sprintf("%06s", $noUrut);
                $filename ='noimage.jpg';
                if($request->hasfile('images')){
                    
                    $fs = $request->file('images');
                    $image_name = $fs->getClientOriginalName();
                    $size = $fs->getSize();
                    $type = $fs->getMimeType();
                    $tmp_name = $fs->path();
                    $ext = $fs->clientExtension();

                    $filename = $kode.'_'.substr(number_format(time() * rand(),0,'',''),0,10).'.'.$ext;
                    $targetFilePath = $targetDir;
                    $fileFullPath = $targetFilePath . DIRECTORY_SEPARATOR. $filename;
                    $fs->move($targetFilePath, $filename);  

                    $images_arr = array(
                        'target'=>$targetFilePath,
                        'origin_name' => $image_name,
                        'filename' => $filename,
                        'tmp_name' => $tmp_name,
                        'type' => $type,
                        'targetDir' => $targetDir,
                        'fileFullPath' => $fileFullPath,
                    ); 
                }
                // $image = $request->image;
                // $name = md5($request->name.date('His'));
                // $realImage = base64_decode($image);
                // $f = finfo_open();
                // $mime_type = finfo_buffer($f, $realImage, FILEINFO_MIME_TYPE);
                // $filename = $name.'.jpg';

                ReqSaldo::insert(
                    ['req_file'=>$filename,'req_from'=>$request->req_from,'req_code' => $kode, 'req_saldo' => $request->req_saldo, 'req_user_id' => $auth->id,'status'=>0,'req_norek'=>$request->req_norek,'req_date'=>Carbon::now()]
                );

                $message = 'Ada User Meminta Top up Saldo';
                $admin = UserAdmin::first();
                $insert['notif_date'] = date('Y-m-d H:i:s');
                $insert['notif_from'] = 'USER';
                $insert['message']    = $message;
                $insert['status']     = 0;
                $insert['url']     = route('customer.request_saldo');
                $insert['data'] = json_encode(array('url'=>route('customer.request_saldo'),'code'=>$kode,));
                $insert['user_id']    = $admin->id_user;
                $insert['jenis']    = 'TOPUPSALDO';
                $notif = Notification::insert($insert);


                // file_put_contents(public_path('storage/uploads/bukti').DIRECTORY_SEPARATOR.$filename, $realImage);
                return response()->json(['status'=>true,'message'=>'Image Uploaded Successfully.']);    
            }else{
                return response()->json(['status'=>'error', 'message'=>trans('auth.not_found'), 'code'=>404]);
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
            if (!$this->auth) {
                return response()->json(['status' => 'error', 'message' => "Invalid Header Credential!", 'code' => 404]);
            }
            $message = 'Mengambil data';
            if($user->isanggota == config('app.user_customer')){
                $trip = Order::where('order_user_id',$user->id)->orderBy('order_tgl_pesanan','DESC')->get();
                $trip_count = Order::where('order_user_id',$user->id)->where('order_jenis',$type)->orderBy('order_tgl_pesanan','DESC')->count();
            }else if($user->isanggota == config('app.user_driver')){
                $trip = Order::where('order_driver_id',$user->id)->orderBy('order_tgl_pesanan','DESC')->get();
                $trip_count = Order::where('order_driver_id',$user->id)->where('order_jenis',$type)->orderBy('order_tgl_pesanan','DESC')->count();
            }
            
            if ($trip_count<=0) {
                $message = 'Anda Belum Memiliki Transaksi';
            }
            return response()->json(['status'=>'success','data'=>$trip,'message'=>$message]);
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
