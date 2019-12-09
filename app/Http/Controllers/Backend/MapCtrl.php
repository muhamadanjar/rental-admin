<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

class MapCtrl extends BackendCtrl
{
    public function index(Request $request){
        return view('backend.maps.op');
    }

    public function getGoogle(){
        return view('backend.maps.google');
    }
}
