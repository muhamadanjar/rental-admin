<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

class ReportCtrl extends BackendCtrl
{
    public function pemesanan(Request $request){
        return view('backend.report.pemesanan');
    }

    public function customer(Request $request){
        return view('backend.report.customer');
    }
}
