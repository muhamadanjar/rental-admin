<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Jenis;
use App\Bidang;
class JenisCtrl extends BackendCtrl{
    public function index(){
        $jenis = Jenis::orderBy('id','ASC')->get();
        
        return view('backend.master.jenis.index')->with('jenis',$jenis);
    }

    public function create(){
        session(['aksi'=>'add']);
        $jenis = new Jenis();
        $bidang = Bidang::orderBy('id','ASC')->get();
        return view('backend.master.jenis.form')->with('jenis',$jenis)
        ->with('bidang',$bidang);
    }
    public function edit($id){
        session(['aksi'=>'edit']);
        $jenis = Jenis::find($id);
        $bidang = Bidang::orderBy('id','ASC')->get();
        return view('backend.master.jenis.form')->with('jenis',$jenis)
        ->with('bidang',$bidang);
    }
    public function show($id){
        $jenis = Jenis::find($id);
        return view('backend.master.jenis.show')->with('jenis',$jenis);
    }
    public function post(Request $request){
        $user = auth()->user();
        $jenis = (session('aksi')=='edit') ? Jenis::find($request->id) : new Jenis();
        $jenis->parent = $request->parent;
        $jenis->nama = $request->namajenis; 
        $jenis->author()->associate($user);
        $jenis->save();
        return redirect()->route('backend.jenis.index');
    }

    public function destroy($id){
        $jenis = Jenis::find($id);
        $jenis->delete();
        return redirect()->route('backend.jenis');
    }
}
