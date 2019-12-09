<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Mobil\Models\Type;
class SettingCtrl extends BackendCtrl
{
    public function fare(Request $request){
        $type = Type::orderBy('id','ASC')->get();
        if ($request->isMethod('post')) {
            return redirect()->route('backend.setting.fare');
        }
        $data = array('type' => $type);
        return view('backend.setting.fare')->with($data);
    }
}
