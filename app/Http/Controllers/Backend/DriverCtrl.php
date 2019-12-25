<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request as httpRequest;
use App\User;
use App\Rental\Models\UserSaldo;
use DB;
use Validator;
use App\Http\Controllers\MainCtrl;
use App\Rental\Contract\IAdministrasiUserRepository as currentRepo;
use Yajra\DataTables\Facades\DataTables;
use Laracasts\Flash\Flash;
class DriverCtrl extends MainCtrl{
    private $roleName;
    public function __construct(httpRequest $request, currentRepo $repository){
        parent::__construct($request, $repository);
        $this->roleName = 'driver';
        $this->params = array('index'=>'driver');
    }


    public function view()
    {
        return view('backend.driver.view');
    }

    public function data(){
        $buffer = $this->repository->getUserData('driver');
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
            $content .= 		'<a data-userId="'.$v->id.'" class="dropdown-item" href="'.route('driver-detail',[$v->id]).'">Detil User</a>';
            $content .= 		'<a data-userId="'.$v->id.'" class="dropdown-item formConfirmSaldo" href="#">Isi Saldo</a>';
			$content .= 	'</div>';
			$content .= '</div>';
            return $content;
		})
		->rawColumns(['action','status','saldo'])
            ->make(true);
    }
    public function index(){
        $role = Role::where('name',$this->roleName)->first();
        // $driver = $role->users;
        $driver = DB::table('tm_driver')->get();
        return view('backend.driver.index')->with(['driver'=>$driver]);
    }

    public function driver_mobil(){
        # code...
    }

    public function show(httpRequest $request,$id){
        $a = User::find($id)->where('isanggota',1)->first();
        // $a = DB::table('tm_driver')->join('mobil','tm_driver.id','mobil.user_id')->where('tm_driver.id',$id)->first();
        // $a->foto = auth()->user()->getPermalink().$a->foto;
        
        if($request->ajax()){
            if($a === NULL){ return response()->json(array('message'=>'Driver tidak di temukan'));}
            return response()->json(array('data'=>$a));
        }
        return view('backend.driver.show')->with(['item'=>$a]);
        
    }

    public function create(){
        session(['aksi'=>'add']);
        return view('backend.driver.form');
    }

    public function edit($id){
        session(['aksi'=>'edit']);
        $merk = Merk::orderBy('merk','ASC')->get();
        $type= Type::orderBy('type','ASC')->get();
        $user = User::find($id);
        $mobil = $this->mobil->findByField('user_id',$id);
        
        
        return view('backend.driver.form')->with(['driver'=>$user,'mobil'=>$mobil,'merkselect'=>$merk,'typeselect'=>$type]);
    }

    public function destroy($id){
        Flash::success('Berhasi di hapus');
        User::find($id)->delete();
        return redirect()->route('backend.driver.index');
    }
    public function nonaktif($id){
        $d = User::find($id);
        $d->isactived = $d->isactived == 1 ? 0:1;
        $d->save();
        Flash::success('Driver Berhasil di perbaharui');
        return redirect()->route('backend.driver.index');
    }

    public function addsaldo(httpRequest $request){
		$userId = $request->user_id;
		$c = UserSaldo::where('user_id',$request->user_id)->first();
		if ($c == NULL) $c = new UserSaldo();
		$user = User::find($userId);
		if(!$user->isactived){
			Flash::info('User tidak aktif');
			return redirect()->route($this->params['index']);	
		}
		$c->user_id = $request->user_id;
		$c->saldo += $request->wallet;
		$c->save();
		Flash::success('Saldo Berhasil di tambahkan');
		return redirect()->route($this->params['index']);

	}

    public function post(Request $request){
        $validator = Validator::make($request->all(),Mobil::$rules_driver,Mobil::$messages_driver);
        if(!$validator->passes()) {
		    return redirect()->route($this->params['index'])
                ->withErrors($validator)
                ->withInput();
        }
        \DB::beginTransaction();

        try{
                $driver = session('aksi') == 'edit' ? User::find($request->id) :new User();
                $driver->name = $request->name;
                $driver->username = $request->username;
                $driver->email = $request->email;
                $driver->isactived = $request->status;
                $driver->isverified = $request->status;
                if($request->oldpassword == $request->password){
                    $driver->password = $request->oldpassword;        
                }else{
                    $driver->password = bcrypt($request->password);           
                }
                $driver->save();
                if (!$driver->hasRole($this->roleName)) {
                    $driver->assignRole('driver');
                }
                
                
                $profile = session('aksi') == 'edit' ? $driver->profile : new UserProfile();
                $profile->nip = $request->nip;
                $profile->address = $request->alamat;;
                $profile->no_telepon = $request->no_telp;
                $profile->user_id = $driver->id;
                if(isset($request->deposit)){
                    $profile->wallet += $request->deposit;
                }
                $profile->user()->associate($driver)->save();

                $mobil = $this->mobil->findByField('user_id',$request->id);
                $mobil = $mobil != null ? $mobil:new Mobil();
                $mobil->no_plat = $request->no_plat;
                $mobil->name = $request->mobil_name;
                $mobil->merk = $request->merk;
                //$mobil->type = $request->type;
                $mobil->warna = $request->warna;
                $mobil->harga = $request->harga;
                $mobil->tahun = ($request->tahun =='') ? 0 : $request->tahun;
                $mobil->foto = ($request->foto =='') ? 'http://placehold.it/160' : $request->foto;
                $mobil->harga_perjam = $request->harga_perjam;   
                $mobil->author()->associate($driver);
                $mobil->save();
                Flash::success(trans('flash/mobil.drivercreated'));
                \DB::commit();

                return redirect()->route($this->params['index']);
            

            
        }catch(Exception $e){
            \DB::rollback();
            report($e);
            Flash::error(trans('flash/mobil.error'));
        }
    }

    public function change_photo(Request $request){
        $dir = public_path().DIRECTORY_SEPARATOR.$request->path;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.$request->path;
        if (!$this->folder_exist($destinationPath)) {
            mkdir($destinationPath, 0777);
        }
            $ext = pathinfo($_FILES["images"]["name"],PATHINFO_EXTENSION);
            $filename = time().'_'.urlencode(pathinfo($_FILES["images"]["name"],PATHINFO_FILENAME)).'.'.$ext;
            if(move_uploaded_file($_FILES["images"]["tmp_name"], $dir.DIRECTORY_SEPARATOR.$filename)){
                return json_encode(array(
                    'error'=>false,
                    'dir'=>$dir,
                    'url_location' =>$request->path,
                    'filename'=>$filename,
                    'data'=>$_FILES["images"]
                ));
                exit;
            }
        return json_encode(array('error'=>true,'message'=>'Upload process error'));
        exit;
    }
    public function folder_exist($folder){
        // Get canonicalized absolute pathname
        $path = realpath($folder);

        // If it exist, check if it's a directory
        if($path !== false AND is_dir($path)){
            // Return canonicalized absolute pathname
            return $path;
        }

        // Path/folder does not exist
        return false;
    }
}
