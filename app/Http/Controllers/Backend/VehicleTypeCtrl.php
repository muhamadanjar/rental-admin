<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Mobil\Models\Type;
use Laracasts\Flash\Flash;
class VehicleTypeCtrl extends BackendCtrl{
     public function __construct(){
         parent::__construct();
     }
     public function index(Type $var = null){
        session(['aksi'=>'add']);
        $type = Type::get();
        return view('backend.driver.type')->with(['type'=>$type]);
     }
    public function create(Request $request){
        session(['aksi'=>'add']);
        $t = new Type();
        return view('backend.driver.type-form')->with(['type'=>$t]);
    }
    public function edit($id){
        session(['aksi'=>'edit']);
        $t = Type::find($id);
        if($t == NULL){
            return redirect()->route('backend.dashboard.index');
        }
        return view('backend.driver.type-form')->with(['type'=>$t]);
    }

    public function destroy($id = null){
        if($id === NULL) return true;
        $t = Type::find($id);
        if ($t !== NULL) {
            $t->delete();
        }
        return redirect()->route('backend.typevehicle.index');
    }
    public function post(Request $request){
        try {
            $t = (session('aksi')=='edit') ? Type::find($request->id) : new Type();
            $t->type = $request->type;
            $t->description = $request->description;
            $t->status = $request->status;
            $t->base_harga = $request->base_harga;
            $t->per_min = $request->per_min;
            $t->per_miles = $request->per_miles;
            $t->person_capacity = $request->person_capacity;
            $t->com = $request->com;
            $t->after_min_km = $request->after_min_km;
            $t->min_km = $request->min_km;
            $t->save();
            Flash::success('Data Tipe Mobil Berhasil di simpan');
        } catch (\Exception $e) {
            Flash::error('Data Tipe mobil gagal di simpan '.$e->getMessage());
        }
        return redirect()->route('backend.dashboard.index');
        
    }
    public function upload(){
        $t =  new Type();
        $dir = $t->getPath();
        if(!is_dir($dir))
            mkdir($dir);
            $ext = pathinfo($_FILES["images"]["name"],PATHINFO_EXTENSION);
            $filename = time().'_'.urlencode(pathinfo($_FILES["images"]["name"],PATHINFO_FILENAME)).'.'.$ext;
            if(move_uploaded_file($_FILES["images"]["tmp_name"], $dir. $filename)){
                return json_encode(array(
                    'error'=>false,
                    'dir'=>$dir,
                    'filename'=>$filename,
                    'data'=>$_FILES["images"]
                ));
                exit;
            }
        return json_encode(array('error'=>true,'message'=>'Upload process error'));
        exit;
    }
}
