<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request as httpRequest;
use App\Rental\Contract\IAdministrasiUserRepository as currentRepo;
use DB;
use App\User;
use Laracasts\Flash\Flash;
use App\Http\Controllers\MainCtrl;
use Yajra\DataTables\Facades\DataTables;
use Hash;
use Session;
use Auth;
use Crypt;
use Gate;

class CustomerCtrl extends MainCtrl{

	public function __construct(httpRequest $request, currentRepo $repository){
		parent::__construct($request, $repository);
		$this->param = array(
            'view' =>"backend.customer.view",
            'view_show' => "backend.customer.show",
        );
	}
	public function view(){
		return \Response::view($this->param['view']);
	}
	public function data()
	{
		$buffer = $this->repository->getUserData('customer');
		return Datatables::of($buffer)
        ->addColumn('action', function ($d) {
            $content = '<div class="btn-group">';
            $content .= '<a href="'.route('admin-booking-view',[$d->order_id]).'" class="btn btn-xs btn-primary btn-edit"><i class="fas fa-paper-plane"></i> </a>';
            $content .= '<a href="#" class="btn btn-xs btn-primary btn-detail"><i class="fa fa-map"></i></a>';
            $content .= '</div>';
            return $content;
        })
            ->make(true);
	}

	public function post(Request $request)
	{
		$data = Customer::create($request->all());
		//dd($data);
		return redirect()->route('backend.customer.index')->with('flash.success','berhasil');
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
