<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Provinsi;
use App\Kabupaten;
use App\Kecamatan;
use App\Kelurahan;
class WilayahCtrl extends BackendCtrl{
    public function index(){
        $provinsi = Provinsi::get();
        $kabupaten = Kabupaten::get();
        $kecamatan = Kecamatan::get();
        $kelurahan = Kelurahan::get();
        return view('backend.wilayah.index')
        ->with([
            'provinsi'=>$provinsi,
            'kabupaten'=>$kabupaten,
            'kecamatan'=>$kecamatan
            ]
        );
    }

    public function post(Request $request){
        if ($request->wilayah =='prov') {
            
        }else if ($request->wilayah =='kab') {
        }else if ($request->wilayah =='kec') {
        }else if ($request->wilayah =='desa') {

        }
    }
}
