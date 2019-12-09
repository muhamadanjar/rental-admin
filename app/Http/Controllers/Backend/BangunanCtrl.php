<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Aset\BangunanRepository as Repository;
use Laracasts\Flash\Flash;
use App\Aset\Bangunan;
class BangunanCtrl extends BackendCtrl{
    public function __construct(){
        parent::__construct();
        $this->repo = new Repository();
    }
    public function index(){
        $bangunan = $this->repo->all();
        return view('backend.bangunan.index')->with(['bangunan'=>$bangunan]);
    }

    public function create(){
        session(['aksi'=>'add']);
        $bangunan = $this->repo->getModel();
        return view('backend.bangunan.form')->with(['bangunan'=>$bangunan]);
    }
    public function edit($id){
        session(['aksi'=>'edit']);
        $bangunan = $this->repo->find($id);
        return view('backend.bangunan.form')->with(['bangunan'=>$bangunan]);
    }

    public function destroy($id){
        $this->repo->delete($id);
        return redirect()->route('backend.bangunan.index');
    }
    public function post(Request $request){
        try {
            $dt = (session('aksi')=='edit') ? $this->repo->find($request->id) : $this->repo->getModel();
            $dt->kode_bangunan = strtoupper(substr(str_random(90),0,10));
            $dt->nama_bangunan = $request->nama_bangunan;
            $dt->luas_bangunan = $request->luas_bangunan;
            $dt->harga_bangunan = $request->harga_bangunan;
            $dt->nilai_bangunan = $request->nilai_bangunan;
            $dt->noimb_bangunan = $request->noimb_bangunan;
            $dt->penggunaan = $request->penggunaan;
            $dt->jumlah_lantai = $request->jumlah_lantai;
            $dt->save();
            $message = (session('aksi')=='edit') ?'Bangunan Berhasil di ubah':'Bangunan Berhasil di Buat';
            Flash::success($message);
            return redirect()->route('backend.bangunan.index');
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        

    }

    public function upload(Request $request){
        try {
            $arrImg = [];
            $targetDir = public_path('storage');
            // $tanah = $this->repo->find($request->id);
            $tanah = Bangunan::find($request->id) == NULL ? new Bangunan:Bangunan::find($request->id);
            // $tanah = new Bangunan();
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
                        $tanah->addMedia($fileFullPath)->toMediaCollection('bangunan');
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
