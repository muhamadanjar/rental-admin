<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Rental\Models\ReqSaldo;

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
		
		$user = userprofile::find($reqUserId);
		$userProfileId = $user->user_id;
		$wallet = $user->wallet;
		if($reqUserId == $userProfileId){
			$wallet2 = $wallet + $data->req_saldo;
			$user->wallet = $wallet2;
			$user->save();
			$data->save();
			return redirect()->route('backend.reqsaldo.index')->with('flash.success','data berhasil dikonfirmasi');
		}else{
			return redirect()->route('backend.reqsaldo.index');
		}
		
	}

	public function destroy($id)
    {
    	reqsaldo::findOrFail($id)->delete();
    	return redirect()->route('backend.reqsaldo.index')->with('flash.success','data berhasil dihapus');

    }
}
