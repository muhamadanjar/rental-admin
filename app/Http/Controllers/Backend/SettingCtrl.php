<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Rental\Models\CarType;
use App\Setting;
class SettingCtrl extends BackendCtrl{
    public function general(){
        $setting = Setting::pluck('setting_value', 'setting_key');
        return view('backend.setting.general')->with('setting',$setting);
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
        // with(new Setting())->saveAll($request->all());
        foreach($request->all() as $key => $value){	
            $checkExist = Setting::where('setting_key','=', $key)->first();
			if($checkExist){
				Setting::where('setting_key','=', $key)->update([
					'setting_value'			=>	($value),
					// 'updated_at'	=>	date('Y-m-d h:i:s')
				]);
			}else{
				Setting::insertGetId([
					'setting_value'			=>	($value),
					'setting_key'			=>	str_slug($key),
				]);				
			}
        }
        return redirect()->back()->with('flash.success', 'Konfigurasi berhasil diperbarui');
    }
}
