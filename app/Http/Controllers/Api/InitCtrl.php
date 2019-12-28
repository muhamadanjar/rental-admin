<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Hash;
class InitCtrl extends ApiCtrl{
    public function init(Request $request){
        $check1 = User::where('email', $request->phonenumber)->first();
        if($check1) {
            return response()->json(['status'=>'error', 'message'=> 'Phonenumber is already exists', 'code'=>404]);
        }

        $check2 = User::where('phonenumber', $request->email)->first();
        if($check2) {
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
            
            $hasher = app()->make('hash');
            $user = new User();
            $user->email = $email;
            $user->phonenumber = $request->phonenumber;
            $user->password = Hash::make($request->password);
            $user->is_admin = 0;
            $user->status = 0;
            $exec = $user->save();
            if(!$exec) {
                return response()->json(['status'=>'error', 'message'=>'Failed to register', 'code'=>200]);
            }

            return response()->json(['status'=>'success', 'message'=>'Data successfully saved', 'code'=>200]);

        }
        catch (PDOException $e) {
            return response()->json(['status'=>'error', 'message'=> $e->getMessage(), 'code'=>404]);
        }
    
    }
}
