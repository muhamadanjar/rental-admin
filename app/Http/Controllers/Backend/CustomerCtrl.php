<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request as httpRequest;
use App\Rental\Contract\IAdministrasiUserRepository as currentRepo;
use DB;
use App\User;
use App\Rental\Models\UserSaldo;
use Laracasts\Flash\Flash;
use App\Http\Controllers\MainCtrl;
use Yajra\DataTables\Facades\DataTables;
use Hash;
use Session;
use Auth;
use Crypt;
use Gate;
use Response;

class CustomerCtrl extends MainCtrl{

	public function __construct(httpRequest $request, currentRepo $repository){
		parent::__construct($request, $repository);
		$this->param = array(
            'view' =>"backend.customer.view",
            'view_show' => "backend.customer.show",
        );
	}
	public function view(){
		return Response::view($this->param['view']);
	}
	public function data()
	{
		$buffer = $this->repository->getUserData('customer');
		return Datatables::of($buffer)
		->addColumn('status',function($e){
			return $e->isactived == 1 ? '<i class="fa fa-check text-green"/>':'<i class="fas fa-times text-red"></i>';
		})
		->addColumn('saldo',function($e){
			$saldo = 0;
			if (isset($e->saldo)) {
				$saldo = $e->saldo->saldo;
			}else{
				$saldo = '0';
			}
			return 'Rp '. number_format($saldo,2,',',".");

		})
        ->addColumn('action', function ($v) {
			$content = '<div class="input-group-prepend">';
			$content .=	'<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i></button>';
			$content .=	'<div class="dropdown-menu">';
			$content .= 		'<a class="dropdown-item" href="'.route('backend.customer.edit',[$v->id]).'">Edit</a>';
			$content .= 		'<a class="dropdown-item" href="#">Hapus</a>';
			$content .= '<form action="'.route('backend.customer.destroy', array($v->id) ).'" method="post" style="display:none" id="frmaktif-'.$v->id.'">';
			$content .= '	<input type="hidden" name="_method" value="delete">';
			$content .= csrf_field();
			$content .= '</form>';
			$content .= 		'<div class="dropdown-divider"></div>';
			$content .= 		'<a data-userId="'.$v->id.'" class="dropdown-item formConfirmSaldo" href="#">Isi Saldo</a>';
			$content .= 	'</div>';
			$content .= '</div>';
            return $content;
		})
		->rawColumns(['action','status','saldo'])
            ->make(true);
	}

	public function post(httpRequest $request){
		$data = User::create($request->all());
		return redirect()->route('backend.customer.index')->with('flash.success','berhasil');
	}

	
	
	public function add_saldo(httpRequest $request){
		$userId = $request->user_id;
		$c = UserSaldo::where('user_id',$request->user_id)->first();
		if ($c == NULL) $c = new UserSaldo();
		$user = User::find($userId);
		if(!$user->isactived){
			Flash::info('User tidak aktif');
			return redirect()->route('customer');	
		}
		$c->user_id = $request->user_id;
		$c->saldo += $request->wallet;
		$c->save();
		Flash::success('Saldo Berhasil di tambahkan');
		return redirect()->route('customer');

	}
	public function request_saldo(){
		$data = DB::table('request_saldo')->join('users','users.id','req_user_id')
		->select("request_saldo.*","users.name")
		->orderBy('request_saldo.id','DESC')->get();
		return view('backend.customer.requestsaldo')->with(['rs'=>$data]);
	}

	public function accept_request_saldo(httpRequest $request){
		$table =DB::table('request_saldo');
		$select = $table->where('id',$request->id);
		$data = $select->first();
		$select->update(['status'=>1]);
		

		$user = UserSaldo::where('user_id',$data->req_user_id)->first();
		$user->wallet += $data->req_saldo;
		$user->save();
		return redirect()->route('backend.customer.request_saldo')->with('flash.success','Saldo Berhasil ditambahkan');
	}

	public function destroy($id){
		
	}
	public function edit($id){
		session(['aksi'=>'edit']);
		$user = User::find($id)->where('isanggota',1);
		return view('backend.customer.form')->withData($user);
	}
	
}
