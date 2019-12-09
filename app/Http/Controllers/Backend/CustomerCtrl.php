<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Customer;
use App\Kabupaten;
use App\UserProfile;
use DB;
use App\User;
use Laracasts\Flash\Flash;
class CustomerCtrl extends BackendCtrl{
	public function index()
	{
		// $data = Customer::all();
		$data = DB::table('tm_customer')->get();
		return view('backend.customer.index',compact('data'));
	}

	public function create()
	{
		$kota = Kabupaten::orderBy('nama_kabupaten','asc')->get();
		return view('backend.customer.form', compact('kota'));
	}

	public function post(Request $request)
	{
		$data = Customer::create($request->all());
		//dd($data);
		return redirect()->route('backend.customer.index')->with('flash.success','berhasil');
	}

	public function edit($id){
		$data = User::join('user_profile','id','user_id')->where('user_id',$id)
		->select('users.*','user_profile.wallet','user_profile.no_telepon','user_profile.address','user_profile.tgl_lahir')
		->first();
		
		// $data = Customer::findOrFail($id);
		$kota = Kabupaten::orderBy('nama_kabupaten','asc')->get();
		return view('backend.customer.edit',compact('data','kota'));
	}

	public function update(Request $request, $id)
	{
		try {
			$user = User::find($id);
			$user->isactived = $request->status;
			$user->isverified = $request->status;
			$c = UserProfile::where('user_id',$request->id)->first();
			$user->name = $request->name;
			$c->tgl_lahir = $request->tgl_lahir;
			$c->no_telepon = $request->no_telepon;
			// $c->address = $request->address;
			// $c->pendidikan = $request->pendidikan;
			// $c->city_id = $request->city_id;

			if($request->oldpassword == $request->password){
                $user->password = $request->oldpassword;        
            }else{
                $user->password = bcrypt($request->password);           
            }
			$c->save();
			$user->save();
			return redirect()->route('backend.customer.index')->with('flash.success','Customer berhasil di update');
		} catch (\Throwable $th) {
			return redirect()->route('backend.customer.index')->with('flash.error',$th->getMessage());
		}
		

		
	}
    
    public function destroy($id)
    {
    	User::findOrFail($id)->delete();
    	return redirect()->route('backend.customer.index');

	}
	
	public function add_saldo(Request $request){
		$c = UserProfile::where('user_id',$request->user_id)->first();
		$c->wallet += $request->wallet;
		$c->save();
		
		Flash::success('Saldo Berhasil di tambahkan');
		return redirect()->route('backend.customer.index');

	}
	public function request_saldo(){
		$data = DB::table('request_saldo')->join('users','users.id','req_user_id')
		->select("request_saldo.*","users.name")
		->orderBy('request_saldo.id','DESC')->get();
		return view('backend.customer.requestsaldo')->with(['rs'=>$data]);
	}

	public function accept_request_saldo(Request $request){
		$table =DB::table('request_saldo');
		$select = $table->where('id',$request->id);
		$data = $select->first();
		$select->update(['status'=>1]);
		

		$user = UserProfile::where('user_id',$data->req_user_id)->first();
		$user->wallet += $data->req_saldo;
		$user->save();
		return redirect()->route('backend.customer.request_saldo')->with('flash.success','Saldo Berhasil ditambahkan');
	}
}
