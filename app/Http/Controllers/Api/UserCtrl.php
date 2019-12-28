<?php namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\User as UserMobile;
use Illuminate\Http\Request;
use Auth;
use App\Rental\Models\UserSaldo;
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
            $user = $request->user('api');
            $up = UserSaldo::where('user_id',$user->id)->first();
            if($up != null){
                $up->saldo = $request->wallet;
                $up->save();
            }else{
                $up =  new UserSaldo();
                $up->user_id = $user->id;
                $up->saldo = $request->saldo;
                $up->save();
            }
            return response()->json(['status'=>true,'message'=>'Anda Berhasil Menambah Dana']);    
        } catch (\Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);    
        }
        
    }
    public function postChangeStatusOnline(Request $request){
        try {
            $user = $request->user('api');
            $user->isavail = ($user->isavail == 1) ? 0 : 1 ;
            $user->save();
            return response()->json(['status'=>true,'data'=>'','message'=>'Data Online Berhasil di ubah']);
        } catch (\Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function changeUsersPassword(Request $request)
    {
        if(app()->make('request')->header('App-ID') == env('APP_ID') && app()->make('request')->header('App-Key') == env('APP_KEY')) {

        	$messages = [
    			'required' => 'The :attribute field is required.',
    		];

    		$validator = Validator::make($request->all(), [
    			'old_password'		=> 'required|max:225',
    			'password'			=> 'required|max:225',
    			'confirm_password'	=> 'required|max:225'
    		], $messages );

    		if ($validator->fails()) {
    			return response()->json(['status'=>'error', 'message'=> $validator->errors()->all(), 'code'=>404]);
    		}

    		if($request->password == $request->confirm_password)
    		{
    	    	try{

    		    	$hasher = app()->make('hash');
    		    	$user   = User::find($this->auth->getResourceOwnerID());
    		    	$exec   = $hasher->check($request->old_password, $user->getAuthPassword());
    		    	if($exec)
    		    	{
    	    			$user = User::find($this->auth->getResourceOwnerID());
    	    			$user->password = $hasher->make($request->password);
    	    			$save = $user->save();
    	    			if($save) {
    	    				return response()->json(['status'=>'success', 'message'=>'Password successfully changed', 'code'=>200]);
    	    			}

    	    			return response()->json(['status'=>'error', 'message'=>'Error occurred', 'code'=>404]);
    		    	}

    		    	return response()->json(['status'=>'error', 'message'=>'The old password did not match', 'code'=>404]);
    	    	}
    	    	catch (\Illuminate\Database\QueryException $e) {
                    \Log::error($e);
    	    		return response()->json(['status'=>'error', 'message'=>'System Error', 'code'=>404]);
    			}
    		}

    		return response()->json(['status'=>'error', 'message'=>'Password confirmation does not match', 'code'=>404]);
        }

        return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>404]);
    }


    public function checkPassword(Request $request)
    {
        if(app()->make('request')->header('App-ID') == env('APP_ID') && app()->make('request')->header('App-Key') == env('APP_KEY')) {
            try{
                $hasher = app()->make('hash');
                $user   = User::find($this->auth->getResourceOwnerID());
                $exec   = $hasher->check($request->password, $user->getAuthPassword());
                if($exec)  {
                    return response()->json(['status'=>'success',
                        'message'=>'Password granted', 'code'=>200]);
                }

                return response()->json(['status'=>'error', 'message'=>'Error occurred', 'code'=>404]);
            }
            catch (\Illuminate\Database\QueryException $e) {
                \Log::error($e);
                return response()->json(['status'=>'error', 'message'=>'System Error', 'code'=>404]);
            }
        }

        return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>404]);
    }

    public function userNotification(Request $request)
    {
    	if(app()->make('request')->header('App-ID') == env('APP_ID') && app()->make('request')->header('App-Key') == env('APP_KEY')) {
            try {

                $user = User::select('id')->find($this->auth->getResourceOwnerID());

        		if($request->userid == $user->id) {
        			return response()->json(['status'=>'success', 'message'=>$request->message, 'code'=>200]);
        		}

        		return response()->json(['status'=>'error', 'message'=>'Error occurred', 'code'=>404]);

        	}catch (PDOException $e) {
                \Log::error($e);
        		return response()->json(['status'=>'error', 'message'=>'System Error', 'code'=>404]);
    		}
        }

        return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>404]);
    }

    public function ChangeEmail(Request $request)
    {
        if(app()->make('request')->header('App-ID') != env('APP_ID') && app()->make('request')->header('App-Key') != env('APP_KEY')) {
            return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>200]);
        }

        $messages = [
            'required' => 'The :attribute field is required.',
            'email'    => 'The :attribute is not valid email address',
            'max'      => 'The :attribute character length does not match'
        ];

        $validator = Validator::make($request->all(), [
            'email'   => 'required|max:100|email',
        ], $messages );

        if ($validator->fails()) {
            return response()->json(['status'=>'error', 'message'=> $validator->errors()->all(), 'code'=>200]);
        }

        $res = User::where("id", $this->auth->getResourceOwnerID())->update([
            'phonenumber' => $request->email,
            'verified_email' => '0'
        ]);

        if (!$res) {
            return response()->json(['status'=>'error', 'message'=> 'System error', 'code'=>200]);
        }

        return response()->json(['status'=>'success', 'message'=> 'Email berhasil disimpan', 'code'=>200]);

    }

    public function GenerateEmailVerification(Request $request)
    {
        if(app()->make('request')->header('App-ID') != env('APP_ID') && app()->make('request')->header('App-Key') != env('APP_KEY')) {
            return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>200]);
        }

        $email = User::where("id", $this->auth->getResourceOwnerID())->first();

        if (!$email) {
            return response()->json(['status'=>'error', 'message'=>'User not found!', 'code'=>200]);
        }

        if ($email->verified_email == '2') {
            return response()->json(['status'=>'error', 'message'=>'Email anda sudah terverifikasi!', 'code'=>200]);
        }

        $hasher = app()->make('hash');
        $nextid = $hasher->make('6471k4p4N41KuD4HmuL4173nU!-!'.$email->phonenumber);
        $created_at = Carbon::now('Asia/Jakarta');
        $expired_at = Carbon::now('Asia/Jakarta')->addDay(1);

        $content['email'] = $email->phonenumber;
        $content['expired_at'] = $expired_at;
        $content['link']  = url('users/email/verification?app='.env('APP_EMAIL_ID').'&key='.env('APP_EMAIL_KEY_ID').'&tknEmail='.$nextid);
        $Mail = Mail::send('emailverif', $content, 
            function($message) use ($content) {
                $message->to($content['email'], 'noreply')->subject('PIKO - SB (EMAIL VERIFICATION)');
            }
        ); 

        if(!$Mail) {
            return response()->json(['status'=>'error', 'message'=>'Error accoured '.$Mail, 'code'=>200]);
        }

        try {

            SetPassword::where('users', $email->id)->where('status', '1')->delete();
            User::where('id', $email->id)->update(['verified_email' => '1']);

            DB::beginTransaction();
                $model = new SetPassword();
                $model->id          = $nextid;
                $model->users       = $email->id;
                $model->email_users = $email->phonenumber;
                $model->created_at  = $created_at;
                $model->expired_at  = $expired_at;
                $exec = $model->save();
            DB::commit();

            if(!$exec){
                DB::rollBack();
                return response()->json(['status'=>'error', 'message'=>'Error Accoured', 'code'=>200]);
            }

            return response()->json(['status'=>'success', 'message'=>'Email terkirim, mohon untuk cek kembali email anda!', 
                'code'=>200]);

        } catch (PDOException $e) {
            DB::rollBack();
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'code'=>200]);
        }

    }

    public function EmailVerification($request)
    {
        if(!app()->make('request')->get('tknEmail')) {
            return response()->json(['status'=>'error', 'message'=>"The page you're looking is not found", 'code'=>200]);
        }

        $validate_email = SetPassword::where('id', app()->make('request')->get('tknEmail'))->first();
        if (!$validate_email) {
            return response()->json(['status'=>'error', 'message'=>'The Email Token is not found!', 'code'=>200]);
        }

        $current_time   = Carbon::now('Asia/Jakarta');
        $validated_time = Carbon::parse($validate_email->expired_at)->gt($current_time);

        if (!$validated_time) {
            return response()->json(['status'=>'error', 'message'=>"The Email Token has been expired!", 'code'=>200]);
        }

        $exec = User::where('id', $validate_email->users)->update(['verified_email' => '2']);
        if (!$exec) {
            return response()->json(['status'=>'error', 'message'=>"Verify email invalid!", 'code'=>200]);
        }

        $data = User::where("id", $validate_email->users)->first();
        return view('emailsuccess', array("verified_email" => $data->verified_email));
    }

    public function EmailStatus()
    {
        if(app()->make('request')->header('App-ID') != env('APP_ID') && app()->make('request')->header('App-Key') != env('APP_KEY')) {
            return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>200]);
        }

        $user = User::select('verified_email')->where('id', $this->auth->getResourceOwnerID())->first();
        if (!$user) {
            return response()->json(['status'=>'error', 'message'=>"Invalid user email!", 'code'=>200]);
        }

        if ($user->verified_email == '0' || $user->verified_email == '1') {
            return response()->json(['status'=>'success', 'message'=>0, 'code'=>200]);
        }

        if ($user->verified_email == '2') {
            return response()->json(['status'=>'success', 'message'=>1, 'code'=>200]);
        }

        return response()->json(['status'=>'error', 'message'=>'System Error!', 'code'=>200]);
    }

    public function getUserData()
    {
        if(app()->make('request')->header('App-ID') == env('APP_ID') && app()->make('request')->header('App-Key') == env('APP_KEY')) {
            try{
                $data = User::where("id", $this->auth->getResourceOwnerID())->select('id','fbaccount','phonenumber','email','status')->first();


                return response()->json(['status'=>'ok', 'message'=>'ok', 'code'=>200,'data'=>$data]);
            }
            catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status'=>'error', 'message'=>'System error', 'code'=>404,'data'=>null]);
            }
        }

        return response()->json(['status'=>'error', 'message'=>'Invalid Credential Header!', 'code'=>404,'data'=>null]);
    }

    
}
