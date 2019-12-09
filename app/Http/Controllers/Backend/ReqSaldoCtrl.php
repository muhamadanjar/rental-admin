<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Rental\Models\ReqSaldo;
use App\Rental\Models\UserSaldo;

class ReqSaldoCtrl extends BackendCtrl
{
    public function index()
	{
		$data = ReqSaldo::all();
		return view('backend.reqsaldo.index',compact('data'));
	}

	public function konfirmasi(Request $request, $id)
	{
		$data = ReqSaldo::find($id);
		$reqUserId = $data->req_user_id;
		$data->status = $request->changeStatus;
		
		$user = UserSaldo::find($reqUserId);
		if($user === NULL){
			$user = new UserSaldo();
		}
		$userProfileId = $user->user_id;
		$wallet = $user->saldo;
		if($reqUserId == $userProfileId){
			$wallet2 = $wallet + $data->req_saldo;
			$user->saldo = $wallet2;
			$user->save();
			$data->save();
			return redirect()->route('backend.reqsaldo.index')->with('flash.success','data berhasil dikonfirmasi');
		}else{
			return redirect()->route('backend.reqsaldo.index');
		}
		
	}
}
