<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Asset\Contracts\ISurvei as SurveiRepository;
class PageCtrl extends Controller
{   
    public function __construct(SurveiRepository $s)
    {
        $this->survei = $s;
    }
    public function landing(){
        $kec = $this->survei->showDataKec();
        $pu = $this->survei->showDataKec();
        // $jenis = $this->survei->showDataJenis();
        // $jenis2 = $this->survei->showDataJenis2();
        // dd($kec);
        return view('frontend.landing')->with(['kec'=>$kec,'pu'=>$pu]);
    }

    public function iframe()
    {
        return view('frontend.iframe');
    }

    public function streetview($id=NULL){
        $a = DB::table('gis_point_360')->where('gid',$id)->first();
        if ($id != NULL) {
            $mock = 'IMG_20191023_171829_00_205_.jpg';
            $mockon ='https://cdn.rawgit.com/mistic100/Photo-Sphere-Viewer/3.1.0/example/Bryce-Canyon-National-Park-Mark-Doliner.jpg';
            $b = array();
            $b['pano'] = $a;
            $b['imgUrl'] = asset('storage/files/').'/'.$mock;
            // $b['imgUrl'] = $mockon;
            return view('frontend.photoshepre')->with($b);    
        }else{
            // return response()->json(array('message'=>'Tidak ada'));
        }
        
    }
}
