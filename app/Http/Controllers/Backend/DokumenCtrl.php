<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Dokumen;
use Event;
use Carbon\Carbon;
class DokumenCtrl extends BackendCtrl
{
    public $secret = 'siprahu_download';
    public $root_upload = '/files/uploads/dokumen/';
    private $_dokumen;
    public function __construct(Dokumen $dokumen){
        parent::__construct();
        $this->middleware('auth');
        $this->_dokumen = $dokumen;
    }
    public function getIndex(){
        $dokumen = Dokumen::orderBy('id','DESC')->get();
        if(auth()->user()->hasRole('admin') || auth()->user()->isSuper()){
            $dokumen = Dokumen::orderBy('id','DESC')->get();
        }
        session(['link_web'=>'file']);
        if (auth()->user()->isSuper() ||auth()->user()->isRole('admin') || auth()->user()->isRole('manager')) {
            $view = 'backend.dokumen.index';
        }else{
            $view = 'backend.dokumen.download';
        }
    	return view($view)->with('dokumen',$dokumen);
    }

    public function getTambah(){
    	if (Gate::check('create.dokumen')) {
            session(['aksi'=>'add']);
            $dokumen = new Dokumen();
            return view('backend.dokumen.tambah')->withDokumen($dokumen);
        }
        return redirect('/dokumen')->withInfo('Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function getEdit($id){
        if (Gate::check('edit.dokumen')) {
        	session(['aksi'=>'edit']);
            $dokumen = Dokumen::find($id);
        	return view('backend.dokumen.tambah')->withDokumen($dokumen);
        }
        return redirect('/dokumen')->withInfo('Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function postDokumen(Request $r){
        try {
            $dokumen = (session('aksi') == 'edit') ? Dokumen::find($r->id) : new Dokumen;
            $dokumen->judul = $r->judul_dokumen;
            $dokumen->deskripsi = $r->deskripsi;
            $dokumen->aktif = 0;
            $dokumen->update_by = auth()->user()->id;
            $dokumen->update_date = Carbon::now();
            $dokumen->file = $r->file_name;
            // if ($r->upload != null) {
                
            //     $fupload = $r->file('upload');
            //     $vdir_upload ='files/uploads/dokumen';
            //     $fileName=$dokumen->id.str_random(20) . '.' . $fupload->getClientOriginalExtension();
            //     $destinationPath = public_path().DIRECTORY_SEPARATOR.$vdir_upload;
            //     if (!$this->folder_exist($destinationPath)) {
            //         mkdir($destinationPath, 0777);
            //     } else {
            //     }
            //     $fuploadName = $fupload->getClientOriginalName();
            //     $fupload->move($destinationPath, $fileName);
            //     $lokasi_file = $destinationPath.'/'.$fileName;
            //     $dokumen->file = $fileName;
            // }
            $dokumen->save();

            // if(session('aksi') =='edit'){
            //     Event::fire('document.updated', [$dokumen, $dokumen]);
            // }else{
            //     Event::fire('document.created', [$dokumen, $dokumen]);
            // }
            return redirect()->route('backend.dokumen.index')->with('flash.success','Dokumen Berhasil ditambahkan');
        } catch (Exception $e) {
            
        }
    }

    public function postDelete($id){
        $vdir_upload ='/files/uploads/dokumen';
        if (Gate::check('edit.dokumen')) {
            $dokumen = \App\Dokumen::findOrFail($id);
            
            if(file_exists(public_path().$vdir_upload.DIRECTORY_SEPARATOR.$dokumen->file)){
                unlink(public_path().$vdir_upload.DIRECTORY_SEPARATOR.$dokumen->file);
            }
            $dokumen->delete();
            return redirect()->route('backend.dokumen.index');
        }
        return redirect()->route('backend.dokumen.index')->with('flash.error','Anda Tidak diijinkan Mengakses Halaman ini');
    }

    public function postDownload($id){
        ignore_user_abort(true);
        set_time_limit(0); 
        $path = public_path($this->root_upload);
         
        //$secret = 'your-secret-string';
         
        if (isset($id) /*&& preg_match('/^([a-f0-9]{32})$/', $id)*/) {
            $dokumen = \App\Dokumen::find($id);    
            
            if (isset($dokumen)) {
                $obj = $dokumen;
                $fullPath = $path.$obj->file;
                if ($fd = fopen ($fullPath, "r")) {


                    $fsize = filesize($fullPath);
                    $path_parts = pathinfo($fullPath);
                    $ext = strtolower($path_parts["extension"]);
                    switch ($ext) {
                        case "pdf":
                        header("Content-type: application/pdf");
                        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
                        break;
                        // add more headers for other content types here
                        default;
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
                        break;
                    }
                    header("Content-length: $fsize");
                    header("Cache-control: private"); //use this to open files directly
                    while(!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                    \DB::table('download')->update(['hits'=>DB::raw('hits+1')]);
                }else{
                    die("File tidak ditemukan");
                }
                fclose ($fd);
                exit;
            } else {
                die('no match');
            }
        } else {
            die('missing file ID');
        }
    }

    public function download($value=''){
        ignore_user_abort(true);
        set_time_limit(0); // disable the time limit for this script
         
        $path = "/absolute_path_to_your_files/"; // change the path to fit your websites document structure
         
        $dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $_GET['download_file']); // simple file name validation
        $dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
        $fullPath = $path.$dl_file;
         
        if ($fd = fopen ($fullPath, "r")) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);
            switch ($ext) {
                case "pdf":
                header("Content-type: application/pdf");
                header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
                break;
                // add more headers for other content types here
                default;
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
                break;
            }
            header("Content-length: $fsize");
            header("Cache-control: private"); //use this to open files directly
            while(!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose ($fd);
        exit;
    }
    public function folder_exist($folder){
        // Get canonicalized absolute pathname
        $path = realpath($folder);

        // If it exist, check if it's a directory
        if($path !== false AND is_dir($path))
        {
            // Return canonicalized absolute pathname
            return $path;
        }

        // Path/folder does not exist
        return false;
    }

    public function upload(Request $request){
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
}
