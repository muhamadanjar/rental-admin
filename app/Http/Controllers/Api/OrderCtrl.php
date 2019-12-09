<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rental\Models\Order;
use App\User;
class OrderCtrl extends Controller{
    public function orderReguler(Request $request){
        
    }

    public function updateTripStatus(Request $request){
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

    public function post_request_saldo(Request $request){
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

    public function post_upload_bukti(Request $request){
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


    private function set_notification($message, $user_id) {

        $insert['notif_date'] = date('Y-m-d H:i:s');
        $insert['notif_from'] = 'SYSTEM';
        $insert['message']    = $message;
        $insert['status']     = 0;
        $insert['user_id']    = $user_id;
        Notification::create($insert);
    }
}
