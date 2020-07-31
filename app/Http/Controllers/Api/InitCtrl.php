<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Hash;
use App\User;
use App\Rental\Models\UserMeta;
use App\Rental\Models\Notification;
use Validator;
use DB;
use Mail;
use Carbon\Carbon;
class InitCtrl extends ApiCtrl{
    public function init(Request $request){
        $check1 = User::where('email', $request->email)->first();
        if($check1) {
            return response()->json(['status'=>'error', 'message'=> 'Email is already exists', 'code'=>404]);
        }

        $check3 = UserMeta::where('meta_key', 'EMAIL')->where('meta_value', $request->email)->first();
        if($check3) {
            return response()->json(['status'=>'error', 'message'=> 'Email is already exists', 'code'=>404]);
        }

        $messages = [
            'required' => 'The :attribute field is required.',
            'unique'   => 'The :attribute is already exist',
            'max'      => 'The :attribute character length does not match',
            'min'      => 'The :attribute character length does not match',
            'email'    => 'The :attribute is not valid address',
        ];

        $validator = Validator::make($request->all(), [
            'phonenumber'   => 'required|unique:users|max:15|min:10',
            'email'         => 'required|unique:users|email|max:225',
            'password'      => 'required|max:60'
        ], $messages );

        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=> implode(",",$validator->messages()->all()), 'code'=>404]);
        }

        $email = (!empty($request->input('email')) ? $request->input('email') : $request->phonenumber );

        try{
          DB::beginTransaction();
            $password = Hash::make($request->password);
            $user = new User();
            $user->email = $email;
            $user->username = $email;
            $user->phonenumber = $request->phonenumber;
            $user->password = $password;
            $user->isadmin = 0;
            $user->isactived = 0;
            $user->isanggota = 1;
            $exec = $user->save();
            if(!$exec) {
                return response()->json(['status'=>'error', 'message'=>'Failed to register', 'code'=>200]);
            }

            $message = 'Ada User yang baru saja mendaftar';
            $insert['notif_date'] = date('Y-m-d H:i:s');
            $insert['notif_from'] = 'USER';
            $insert['message']    = $message;
            $insert['status']     = 0;
            $insert['user_id']    = 1;
            //$insert['jenis']    = 'REGISTER';
            $notif = Notification::insert($insert);

            $verification_code = Hash::make(str_random(30)); //Generate verification code
            DB::table('m_password')->insert(['user_id'=>$user->id,'password'=>$password,'token'=>$verification_code,'expired_at'=>Carbon::now()->addWeeks(2)]);
            $subject = "Please verify your email address.";
            $email = $user->email;
            $name = $user->email;
            //Mail::send('emails.verify', ['email' => $email,'name' => $name, 'verification_code' => $verification_code],
            //    function($mail) use ($email, $name, $subject){
            //        $mail->from(getenv('MAIL_USERNAME'), "Trans Utama");
            //        $mail->to($email, $name);
            //        $mail->subject($subject);
            //});
            DB::commit();
            return response()->json(['status'=>'success', 'message'=>'Data berhasil di simpan, Tolong cek email untuk aktifasi data.', 'code'=>200]);

        }
        catch (PDOException $e) {
            DB::rollback();
            return response()->json(['status'=>'error', 'message'=> $e->getMessage(), 'code'=>404]);
        }
    
    }
}
