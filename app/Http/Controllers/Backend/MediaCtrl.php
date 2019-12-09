<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Gallery\Media;
use App\Gallery\Album;
use App\Gallery\RepositoryInterface as MediaRepo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use App\Post\RepositoryInterface;
class MediaCtrl extends BackendCtrl{
    private $post;
    private $media;
    public function __construct(RepositoryInterface $postRepo,MediaRepo $mediaRepo){
        $this->post = $postRepo;
        $this->media = $mediaRepo;
        $this->middleware('auth',['except'=>'show']);
    }
    public function show($medium){
        $media = Media::where('filename', $medium)->firstOrFail();
        $headers = [
            'Content-Type' => $media->mime_type,
            'Content-Disposition' => "filename='{$media->original_filename}'"
        ];

        return response()->file($media->getPath(), $headers);
    }

    public function index(Request $r){
        $type = $r->get('type', 'media');
        $media = Media::orderBy('id','asc')->get();
        $album = Album::orderBy('id','asc')->get();
        $count['media'] = $this->media->count();
        $count['album'] = $this->media->countAlbum();
        return view('backend.galeri.index', compact('album','media','count', 'type'));
    }

    public function edit(Request $request,$id){
        if (!Gate::check('edit.dokumen')) {
            return redirect()->route('backend.dashboard.index')->with('flash.error','Anda dilarang mengakses Halaman ini.');
        }
        session(['aksi'=>'edit']);
        $media = Media::findOrFail($id);
       
        $album = Album::all();
        $type = $request->get('type');
        return view('backend.galeri.form', compact('media','album','type'));
    }

    public function destroy($id){
        if (!Gate::check('delete.dokumen')) {
            return redirect()->route('backend.dashboard.index')->with('flash.error','Anda dilarang mengakses Halaman ini.');
        }
        $media = Media::find($id);
        unlink($media->getPathMedia().$media->original_filename);
        $media->delete();
        Event::fire('media.delete', [$media, $media]);
        return redirect()->route('backend.media.index');
    }

    public function create(Request $request){
        if (!Gate::check('create.dokumen')) {
            return redirect()->route('backend.dashboard.index')->with('flash.error','Anda dilarang mengakses Halaman ini.');
        }
        session(['aksi'=>'add']);
        $media = new Media();
        $album = Album::all();
        $type = $request->get('type');
        return view('backend.galeri.form', compact('media','album','type'));
    }

    public function store(Request $input){
        if (!Gate::check('create.media')) {
            return redirect()->route('backend.dashboard.index')->with('flash.error','Anda dilarang mengakses Halaman ini.');
        }
        $user = Auth::user();
        $media = session('aksi') =='edit' ?Media::findOrFail($input->id) : new Media();
        if($input->hasFile('file') && $input->file('file')->isValid()){
            $file = $input->file('file');
            $destination = public_path().DIRECTORY_SEPARATOR.'images/uploads/gallery';
            $file->move($destination, $file->getClientOriginalName());
            if($file != null){
                $media->filename = File::name($file->getClientOriginalName());
                $media->original_filename = $file->getClientOriginalName();
                $media->mime_type = File::mimeType($destination.DIRECTORY_SEPARATOR.$file->getClientOriginalName());
            }
        }
        $media->embed = $input->embed;
        $media->album_id = $input->album_id;
        $media->author()->associate($user);
        if(session('aksi') =='edit'){
                Event::fire('media.updated', [$media, $media]);
        }else{
                Event::fire('media.created', [$media, $media]);
        }
        $media->save();
        return redirect()->route('backend.media.index')->with('flash.success', '');

        //return redirect()->back()->with('flash.error', 'Upload file gagal');
        
    }

    public function update(){
        
    }

    public function postimage(){
        return $this->post->postfeatureimage('/images/uploads/gallery/');
    }
}
