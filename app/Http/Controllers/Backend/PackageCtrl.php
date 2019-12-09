<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Mobil\Models\RentPackage;
use App\Mobil\Models\Type;
use DB; 
use Validator;
use Laracasts\Flash\Flash;
class PackageCtrl extends BackendCtrl{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        $type = DB::table('rent_package')
                ->rightjoin('type','rent_package.rp_car_type','=','type.id')
                ->select('type.id as typeid','type.type','type.type','type.description','type.status',DB::raw('count(rent_package.rp_car_type) as count'))
                ->groupby('rent_package.rp_car_type','type.type','type.description','type.status','type.id')
                ->orderBy('type.id','ASC')
                //->paginate(2);
                ->get();
        return view('backend.package.index')->with(['package'=>[],'type'=>$type]);
    }
    public function edit($id,$type=null){
        session(['status'=>'edit']);
        $p = RentPackage::find($id);
        $path = $p->getPath();
        $permanentlink = $p->getPermalink();
        return view('backend.package.form')->with(['paket'=>$p,'type'=>$type,'path'=>$path,'permanentlink'=>$permanentlink]);
    }
    public function create($type){
        session(['status'=>'add']);
        $paket = new RentPackage();
        $path = $paket->getPath();
        $permanentlink = $paket->getPermalink();
        $id = RentPackage::select(DB::raw('MAX(rp_id) as max'))->first()->toArray();
        $id_paket = $id['max']+1;
        return view('backend.package.form')->with(['paket'=>$paket,'type'=>$type,'id_rent'=>$id_paket,'path'=>$path,'permanentlink'=>$permanentlink]);
    }
    public function destroy($id){
        $p = RentPackage::find($id);
        $p->delete();
        Flash::success('data berhasil dihapus');
        return redirect()->route('backend.packages.index');
    }

    public function list($type = null){
        $p = RentPackage::where('rp_car_type',$type)->get();
        $t = Type::find($type);
        return view('backend.package.list')->with(['type'=>$t,'packages'=>$p]);
    }

    public function post($type=null,Request $request){
        try {
            if ($type != null) {
                $rent = (session('status')=='edit') ? RentPackage::find($request->id) : new RentPackage();
                $rent->rp_name = $request->rp_name;
                $rent->rp_total_price = $request->rp_total_price;
                $rent->rp_miles_km = $request->rp_miles_km;
                $rent->rp_hour = $request->rp_hour;
                $rent->rp_add_mile_km = $request->rp_add_mile_km;
                $rent->rp_add_min = $request->rp_add_min;
                $rent->rp_car_type = $type;
                $rent->status = $request->status;
                $rent->image = $request->foto;
                $rent->save();
                Flash::success('Data berhasil');
            }else{
                Flash::error('Tipe Mobil tidak ada');
            }
            
            return redirect()->route('backend.packages.index');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function upload(){
        $packages =  new RentPackage();
        $dir = $packages->getPath();
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

    public function na($id){
        try {
            $r = RentPackage::find($id);
            $r->status = ($r->status == 1 ? 0:1);
            $r->save(); 
            return redirect()->route('backend.packages.index');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
