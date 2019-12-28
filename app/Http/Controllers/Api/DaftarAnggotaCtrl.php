<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class DaftarAnggotaCtrl extends ApiCtrl
{
    public function __construct(){
        parent::__construct();
        $this->validation_messages = [
            'password.required' => 'Password is required.',
            'email.unique' => 'Email must be unique.'
        ];

    }
    public function init()
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'numeric' => 'The :attribute characters should be numbers.',
            'max' => 'The :attribute length is not valid.',
        ];

        $rules = [
            'daftar_anggota' => 'required|max:10',
            'no_whatsapp' => 'required|max:15',
            //'nama'              => 'required|max:50',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $this->validation_messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all(), 'code' => 404]);
        }

        $validate = UserDaftarAnggota::where('user_id', $this->auth->getResourceOwnerID())->first();
        if ($validate) {
            return response()->json(['status' => 'error', 'message' => 'User anda sudah terdaftar sebagai anggota!', 'code' => 404]);
        }

        $meta_ktp = UserMeta::where("meta_users", $this->auth->getResourceOwnerID())->where("meta_key", "DATA_DIRI_EKTP")->first();
        if(!$meta_ktp) {
            return response()->json(['status' => 'error', 'message' => 'Error accoured!', 'code' => 404]);
        }

        try {

            DB::beginTransaction();

            $model = new UserDaftarAnggota();
            $model->user_id = $this->auth->getResourceOwnerID();
            $model->daftar_anggota = $request->daftar_anggota;
            //$model->nama            = $request->nama;
            $model->no_ktp = $meta_ktp->meta_value;
            $model->no_whatsapp = $request->no_whatsapp;
            $model->created_by = 1;
            $model->updated_by = 1;
            $model->status = 0;
            $exec = $model->save();
            DB::commit();

            if (!$exec) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Error accoured!', 'code' => 404]);
            }

            return response()->json(['status' => 'success', 'message' => "Data berhasil disimpan", 'code' => 200]);


        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
    }

    public function SetPIN(Request $request)
    {

        if (!app()->make('request')->header('App-ID') == env('APP_ID') && !app()->make('request')->header('App-Key') == env('APP_KEY')) {
            return response()->json(['status' => 'error', 'message' => "Invalid Header Credential!", 'code' => 404]);
        }

        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute length is not valid.',
            'min' => 'The :attribute length is not valid.',
        ];

        $rules = [
            'pin_password' => 'required|max:6|min:6',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $this->validation_messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all(), 'code' => 404]);
        }

        try {

            $hasher = app()->make('hash');

            DB::beginTransaction();
            $exec = UserDaftarAnggota::where('user_id', $this->auth->getResourceOwnerID())->update(
                ["pin_password" => $hasher->make($request->pin_password)]
            );
            DB::commit();

            if (!$exec) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Error accoured!', 'code' => 404]);
            }

            return response()->json(['status' => 'success', 'message' => "Data berhasil disimpan", 'code' => 200]);


        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }

    }

    public function ValidatePIN(Request $request)
    {

        if (!app()->make('request')->header('App-ID') == env('APP_ID') && !app()->make('request')->header('App-Key') == env('APP_KEY')) {
            return response()->json(['status' => 'error', 'message' => "Invalid Header Credential!", 'code' => 404]);
        }

        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute length is not valid.',
            'min' => 'The :attribute length is not valid.',
        ];

        $rules = [
            'pin_password' => 'required|max:6|min:6',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $this->validation_messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all(), 'code' => 404]);
        }

        try {
            $hasher = app()->make('hash');
            $user = UserDaftarAnggota::where('user_id', $this->auth->getResourceOwnerID())->first();
            $exec = $hasher->check($request->pin_password, $user->pin_password);
            if ($exec) {
                return response()->json(['status' => 'success',
                    'message' => 'Password granted', 'code' => 200]);
            }

            return response()->json(['status' => 'error', 'message' => 'Invalid password', 'code' => 404]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
    }

    public function checkPin($pin)
    {
        try {

            $hasher = app()->make('hash');
            $user = UserDaftarAnggota::where('user_id', $this->auth->getResourceOwnerID())->first();
            $exec = $hasher->check($pin, $user->pin_password);
            if ($exec) {
                return true;
            }

            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }


    }

    public function updateData(Request $request)
    {

        if (!app()->make('request')->header('App-ID') == env('APP_ID') && !app()->make('request')->header('App-Key') == env('APP_KEY')) {
            return response()->json(['status' => 'error', 'message' => "Invalid Header Credential!", 'code' => 404]);
        }

        $messages = [
            'required' => 'The :attribute field is required.',
            'numeric' => 'The :attribute characters should be numbers.',
            'max' => 'The :attribute length is not valid.',
        ];

        $rules = [
            'daftar_anggota' => 'required|max:10',
            //'no_ktp' => 'required|max:20',
            'no_whatsapp' => 'required|max:15',
            //'nama'              => 'required|max:50',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $this->validation_messages);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all(), 'code' => 404]);
        }



        try {

            DB::beginTransaction();

            $meta_ktp = UserMeta::where("meta_users", $this->auth->getResourceOwnerID())->where("meta_key", "DATA_DIRI_EKTP")->first();
            if(!$meta_ktp) {
                return response()->json(['status' => 'error', 'message' => 'Error accoured!', 'code' => 404]);
            }

            $model =  UserDaftarAnggota::where('user_id', $this->auth->getResourceOwnerID())->first();
            $model->user_id = $this->auth->getResourceOwnerID();
            $model->daftar_anggota = $request->daftar_anggota;
            //$model->nama            = $request->nama;
            $model->no_ktp = $meta_ktp->meta_value;
            $model->no_whatsapp = $request->no_whatsapp;
            $model->created_by = 1;
            $model->updated_by = 1;
            $model->status = 0;
            $exec = $model->save();

            DB::commit();

            if (!$exec) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Error accoured!', 'code' => 404]);
            }

            return response()->json(['status' => 'success', 'message' => "Data berhasil disimpan", 'code' => 200]);


        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }

    }

    public function getStatus()
    {

        try {

            $statment = UserDaftarAnggota::where("user_id", $this->auth->getResourceOwnerID())->first();

            if (!$statment) {
                $data = array(
                    "status" => 5,
                    "pin" => false,
                    "whatsapp" => "",
                    "ktp" => ""
                );
                return response()->json(['status' => 'ok', 'message' => "ok", 'code' => 200, 'data' => $data]);
                // return response()->json(['status'=>'success', 'message'=> array("status" => 5), 'code'=>200]);
            }

            $pin = null;

            if ($statment->pin_password == null || empty($statment->pin_password)) {
                $pin = false;
            } else {
                $pin = true;
            }
            $data = array(
                "status" => $statment->status,
                "pin" => $pin,
                "ktp" => $statment->no_ktp,
                "whatsapp" => $statment->no_whatsapp
            );
            return response()->json(['status' => 'success', 'message' => "ok", 'code' => 200, 'data' => $data]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 'success', 'message' => array("status" => $e->getMessage()), 'code' => 200, 'data' => null]);
        }
    }
}
