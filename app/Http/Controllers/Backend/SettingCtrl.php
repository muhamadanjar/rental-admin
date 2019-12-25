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
            
            $a->min_km = $request->min_km;
            $a->min_rp = $request->min_rp;
            $a->after_min_km = $request->after_min_km;
            $a->after_min_rp = $request->after_min_rp;
            $a->save();
            return redirect()->route('backend.setting.fare');
        }
        $type = CarType::where('status',1)->orderBy('id','ASC')->get();
        $data = array('type' => $type);
        return view('backend.setting.fare')->with($data);
    }

    public function store(Request $request){
        with(new Setting())->saveAll($request->all());
        return redirect()->back()->with('flash.success', 'Konfigurasi berhasil diperbarui');
    }
}
