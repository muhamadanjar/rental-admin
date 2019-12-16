<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Rental\Models\CarType;
class SettingCtrl extends BackendCtrl{
    public function general(){
        return view('backend.setting.general');
    }
    public function fare(Request $request){
        if ($request->isMethod('post')) {
            $a = (CarType::find($request->idtype) == NULL ? new CarType(): CarType::find($request->idtype) );
            
            // $a->save();
            dd($request->name['basic_min_km']);
        }
        $type = CarType::where('status',1)->orderBy('id','ASC')->get();
        if ($request->isMethod('post')) {
            return redirect()->route('backend.setting.fare');
        }
        $data = array('type' => $type);
        return view('backend.setting.fare')->with($data);
    }
}
