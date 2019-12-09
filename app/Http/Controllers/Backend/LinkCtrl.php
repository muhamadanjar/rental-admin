<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Link;
use App\Post\RepositoryInterface;
use Gate;
class LinkCtrl extends BackendCtrl{
    public function __construct(RepositoryInterface $repo){
        $this->repo = $repo;
    }
    public function index(){
        $link = DB::table('link')->get();
        
        return view('backend.link.index')->with('link',$link);
    }

    public function create(){
        if(Gate::check('create.link')){
            session(['aksi'=>'add']);
            $link = new Link();
            return view('backend.link.form')->with('link',$link);
        }
        return redirect()->route('backend.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function edit($id){
        if(Gate::check('edit.link')){
            session(['aksi'=>'edit']);
            $link = Link::findOrFail($id);
            return view('backend.link.form')->with('link',$link);
        }
        return redirect()->route('backend.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function destroy($id){
        if(Gate::check('delete.link')){
            //$link = DB::table('link')->where('id',$id)->delete();
            $link = Link::findOrFail($id)->delete();
            return redirect()->route('backend.link.index')->with('flash.success','Data Berhasil di hapus!!.');
        }
        return redirect()->route('backend.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function postLink(Request $request){
        $user = auth()->user();
        $query = (session('aksi') == 'edit' ? Link::findOrFail($request->id): new Link());
        $link = $query;
        $link->judul = $request->judul;
        $link->url = $request->url;
        $link->image = $request->foto;
        $link->author()->associate($user);
        $link->save();

        if(session('aksi')=='edit'){
            $flashmsg = 'Data Berhasil Di Ubah';
        }else{
            $flashmsg = 'Data Berhasil ditambahkan!!.';
        }
        return redirect()->route('backend.link.index')->with('flash.success',$flashmsg);
    }

    public function postImage(){
        return $this->repo->postfeatureimage('/images/uploads/link/');
    }
}
