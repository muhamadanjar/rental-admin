<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\User;
use App\UserProfile;
use App\Role;
use App\Mobil\Merk;
use App\Mobil\Type;
use DB;
use App\Mobil\Models\Mobil;
use Validator;
use App\Mobil\Repository;
use Laracasts\Flash\Flash;
class DriverCtrl extends BackendCtrl{
    private $roleName;
    public function __construct(Repository $repo){
        $this->roleName = 'driver';
        $this->mobil = $repo;
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

    public function show(Request $request,$id){
        $a = DB::table('tm_driver')->join('mobil','tm_driver.id','mobil.user_id')->where('tm_driver.id',$id)->first();
        // $a->foto = auth()->user()->getPermalink().$a->foto;
        
        if($request->ajax()){
            if($a === NULL){ return response()->json(array('message'=>'Driver tidak di temukan'));}
            return response()->json(array('data'=>$a));
        }
        return view('backend.driver.show')->with(['driver'=>$a]);
        
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

    public function add_saldo(Request $request){
        $c = UserProfile::where('user_id',$request->user_id)->first();
        $c = ($c === NULL) ? new UserProfile():$c;
        $c->user_id = $request->user_id;
		$c->wallet += $request->wallet;
		$c->save();
		
		Flash::success('Saldo Berhasil di tambahkan');
		return redirect()->route('backend.driver.index');

	}

    public function post(Request $request){
        $validator = Validator::make($request->all(),Mobil::$rules_driver,Mobil::$messages_driver);
        if(!$validator->passes()) {
		    return redirect()->route('backend.driver.index')
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

                return redirect()->route('backend.driver.index');
            

            
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
