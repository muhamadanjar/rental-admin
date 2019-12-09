<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
//use App\Transmigrasi\Transmigrasi;
//use App\Transmigrasi\RepositoryInterface;
use App\Pengumuman;
use Illuminate\Support\Facades\Gate;
class PengumumanCtrl extends BackendCtrl
{
    public function __construct(/*RepositoryInterface $repo*/){
        /*$this->repo = $repo;*/
    }
    public function index(){
        $pengumuman = Pengumuman::get();
        return view('backend.pengumuman.index')->with('pengumuman',$pengumuman);
    }

    public function create(){
        if (Gate::check('create.transmigrasi')) {
            session(['aksi'=>'add']);
            
            return view('backend.pengumuman.form');
        }
        return redirect()->route('backend.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');
        
    }

    public function edit($id){
        if (Gate::check('edit.transmigrasi')){
            session(['aksi'=>'edit']);
            $pengumuman = Pengumuman::findOrFail($id);
            
            return view('backend.pengumuman.form')->with('pengumuman',$pengumuman);
        }
        return redirect()->route('backend.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function destroy($id){
        if (Gate::check('delete.transmigrasi')){
            $pengumuman = Pengumuman::findOrFail($id)->delete();
            return redirect()->route('backend.pengumuman.index')->with('flash.success','Data Berhasil di hapus!!.');
        }
        return redirect()->route('backend.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');

    }

    public function postPengumuman(Request $request){
        $user = auth()->user();
        $query = (session('aksi') == 'edit' ? Pengumuman::findOrFail($request->id): new Pengumuman());
        $link = $query;
        $link->info = $request->info;
        $link->aktif = $request->aktif;
        $link->author()->associate($user);
        $link->save();

        if(session('aksi')=='edit'){
            $flashmsg = 'Data Berhasil Di Ubah';
        }else{
            $flashmsg = 'Data Berhasil ditambahkan!!.';
        }
        return redirect()->route('backend.pengumuman.index')->with('flash.success',$flashmsg);
    }
}
