<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use App\Pool;
class PoolCtrl extends BackendCtrl{
   
    public function index(){
        $pool = Pool::orderBy('id','ASC')->get();
        return view('backend.pool.index')->with([
            'pool'=>$pool
        ]);
    }

    
    public function create(){
        session(['aksi'=>'add']);
        $pool = new Pool();
        return view('backend.pool.form')->with([
            'pool'=>$pool
        ]);;
    }

    public function show($id)
    {
        //
    }

    public function post(Request $request){
        try {
            $a = (session('aksi')=='edit') ? Pool::find($request->id) : new Pool();
            $a->pool_name = $request->pool_name;
            $a->pool_address = $request->pool_address;
            $a->pool_latitude = $request->pool_latitude;
            $a->pool_longitude = $request->pool_longitude;
            $a->pool_image = $request->pool_image;
            $a->save();
            Flash::success('Data Bool Berhasil di tambahkan');
            return redirect()->route('backend.pool.index');
        } catch (\Throwable $th) {
         
        }
    }

    
    public function edit($id){
        session(['aksi'=>'edit']);
        $pool = Pool::find($id);
        return view('backend.pool.form')->withPool($pool);
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    public function upload(){
        $promo =  new Pool();
        $dir = $promo->getPath();
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

    
    public function destroy($id){
        $pool = Pool::find($id);
        if($pool){
            $pool->delete();
        }

        return redirect()->route('backend.pool.index');
    }
}
