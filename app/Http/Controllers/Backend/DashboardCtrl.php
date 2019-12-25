<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Rental\Models\Order;
use App\Rental\Models\UserAnggota;
// use App\AuditTrail\Activity\IActivity as activityRepo;
class DashboardCtrl extends BackendCtrl{
    public function __construct(){
        parent::__construct();
    }
    public function getIndex(Request $request){
        if($request->ajax()){}
        $countuser = DB::table('users')->count();
        $totaldriver = UserAnggota::where('is_anggota',1)->count();
        $totalcustomer = UserAnggota::where('is_anggota',2)->count();
        $totalpemesanan = Order::count();
        // $datastatistik = $this->activity->statistikPengunjung();
        // $totalpengunjung = $this->activity->totalhits();
        
        $orderList = Order::orderBy('order_tgl_pesanan','DESC')->orderBy('order_status','ASC')->paginate(10);
        return view('backend.dashboard.index')->with([
            'totaldriver'=>$totaldriver,
            'totalcustomer'=>$totalcustomer,
            'totalpemesanan' => $totalpemesanan,
            'countuser'=>$countuser,
            'totalpengunjung'=>0,
            'orderList'=>$orderList,
        ]);
    }
    public function index(){
        $countuser = DB::table('users')->count();
        $totaldriver = DB::table('sys_tr_anggota')->where('is_anggota',1)->count();
        $totalcustomer = DB::table('sys_tr_anggota')->where('is_anggota',2)->count();
        $totalpemesanan = Order::count();
        
        $orderList = Order::orderBy('order_tgl_pesanan','DESC')->paginate(10);
        return view('backend.dashboard.index')->with([
            'totaldriver'=>$totaldriver,
            'totalcustomer'=>$totalcustomer,
            'totalpemesanan' => $totalpemesanan,
            'countuser'=>$countuser,
            'totalpengunjung'=>0,
            'orderList'=>$orderList,
        ]);
    }
}
