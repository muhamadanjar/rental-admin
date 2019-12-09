<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Aset\Area;
use App\Aset\Tanah;
use App\Pool;
use App\Media;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Aset\Repository;
use MulutBusuk\Workspaces\Repositories\Traits\GlobalBantuan;
use Laracasts\Flash\Flash;
class TanahCtrl extends BackendCtrl{
    use GlobalBantuan;
    public $repo;
    public function __construct(){
        parent::__construct();
        $this->repo = new Repository();
    }

    public function index(){
        $tanah = $this->repo->all();        
        return view('backend.tanah.index')->with(['tanah'=>$tanah]);
    }

    public function create(){
        session(['aksi'=>'add']);
        $tanah = new Tanah();
        $pool =  Pool::get();
        return view('backend.tanah.form')->with(['tanah'=>$tanah,'pool'=>$pool]);
    }
    public function edit($id){
        session(['aksi'=>'edit']);
        $tanah = $this->repo->find($id);
        $pool =  Pool::get();
        return view('backend.tanah.form')->with(['tanah'=>$tanah,'pool'=>$pool]);
    }

    public function destroy($id){
        $this->repo->delete($id);
        return redirect()->route('backend.tanah.index');
    }
    public function post(Request $request){
        try {
            $dt = (session('aksi')=='edit') ? $this->repo->find($request->id) : $this->repo->getModel();
            
            $dt->kode_objek = randomString(6).chr(rand(65,90)).chr(rand(97,122));;
            $dt->nama_objek = $request->nama_objek;
            $dt->nama_jalan = $request->nama_jalan;
            $dt->jenis_surat = $request->jenis_surat;
            $dt->atas_nama_sekarang = $request->atas_nama_sekarang;
            $dt->atas_nama_sebelum = $request->atas_nama_sebelum;
            $dt->kode_provinsi = $request->kode_provinsi;
            $dt->kode_kab = $request->kode_kab;
            $dt->kode_kec = $request->kode_kec;
            $dt->kode_desa = $request->kode_desa;
            $dt->pool_id = $request->kode_pool;
            $dt->latitude = $request->latitude;
            $dt->longitude = $request->longtitude;

            $dt->no_surat_tanah = $request->no_surat_tanah;
            // $dt->sertifikat = $request->sertifikat;
            $dt->tgl_sertifikat = $request->masa_berlaku;
            $dt->tgl_masa_berlaku = $request->masa_berlaku;
            $dt->nop_pbb = $request->nop_pbb;
            $dt->tahun = $request->tahun;
            $dt->luas_m2 = $request->luas_m2;
            
            $dt->alamat = $request->alamat;
            
            $dt->keterangan = $request->keterangan;
            
            $dt->save();

            // if (/*isset($request->foto) &&*/ $request->foto != null && !empty($request->foto)) {
            //     foreach ($request->foto as $key => $value) {
            //         if ($value != null) {
            //             $_file = new Media();
            //             $_file->kode_media = $dt->kode_objek;
            //             $_file->typefile = 'image/png';
            //             $_file->namafile = $value;
            //             $_file->keterangan = 'Foto Tanah ' . $dt->nama_jalan;
            //             $_file->kode_area = 'tanah';

            //             $dt->files()->save($_file);
            //         }
            //     }

            // } else {
            //         // $jalan->files()->sync(array());
            // }

            Flash::success('Tanah Berhasil '.session('aksi'));
            return redirect()->route('backend.tanah.index');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors($th->getMessage());
            
        }
        

    }

    public function area($id=NULL,Request $request){
        try {
            if($request->isMethod('post')){

                $tanah = $this->repo->find($id);
                $geom = $request->geom;
                $pol = '';
                foreach ($geom as $key => $value) {
                    $cd = $geom[$key] = json_decode($value,true);
                    $gdel = '';
                    foreach ($cd['coordinates'] as $k => $v) {
                        unset($coord);
                        $coord = '';
                        foreach ($v as $i => $ci) {
                            // echo $key. ' = '. implode(' ',$ci).PHP_EOL;
                            $del = $i == 0 ? '':',';
                            $coord .= $del.implode(' ',$ci);
                        }
                        $gdel = $key == 0 ? '':',';
                        $pol .= $gdel.'(('.$coord.'))'; 
                    }
                }
                $tanah->geom = DB::raw("ST_GeomFromText('MULTIPOLYGON(${pol})',4326)");
                $tanah->save();
    
                
                foreach ($geom as $key => $value) {
                    $area = new Area();
                    $area->kode_area = substr(md5(rand(0,1000)),0,10);
                    $area->tipe_area = 'tanah';
                    $area->tanah()->associate($tanah);
                    $area->rect_area = json_encode($value['coordinates']);
                    $area->save();
                }
                
                return response()->json(array('message'=>'Data Berhasil di simpan'),200);
                // ST_GeometryFromText()
            }else{
                $tanah = $this->repo->find($id);
                if($tanah === NULL) return  redirect()->name('backend.tanah.index');
                return view('backend.tanah.area');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            // return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function upload(Request $request){
        try {
            $arrImg = [];
            $targetDir = public_path('storage');
            // $tanah = $this->repo->find($request->id);
            $tanah = Tanah::find($request->id) == NULL ? new Tanah:Tanah::find($request->id);
            if($request->hasfile('images')){
                $fs = $request->file('images');
                if (is_array($fs)) {
                    foreach($fs as $v){
                        $image_name = $v->getClientOriginalName();
                        $size = $v->getSize();
                        $type = $v->getMimeType();
                        $tmp_name = $v->path();
                        $ext = $v->clientExtension();
                        $filename = str_random(20).'.'.$ext;
                        $targetFilePath = $targetDir;
                        $fileFullPath = $targetFilePath . DIRECTORY_SEPARATOR. $filename;
                        $v->move($targetFilePath, $filename);  
                        $images_arr['target'] = $targetFilePath;
                        $images_arr['origin_name'] = $image_name;
                        $images_arr['filename'] = $filename;
                        $images_arr['tmp_name'] = $tmp_name;
                        $images_arr['type'] = $type;
                        array_push($arrImg, $images_arr);
                        $tanah->addMedia($fileFullPath)->toMediaCollection();
                        // $tanah->addMediaFromRequest('images')->toMediaCollection('images');
                        
                    }
                }   
            }
            return $arrImg;
            
        } catch (\Exception $e) {
            return response()->json(array('error'=>true,'message'=>$e->getMessage()),500);
        }
        
    }
    
}