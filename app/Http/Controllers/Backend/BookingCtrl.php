<?php

namespace App\Http\Controllers\Backend;

use App\Customer;
use App\Kabupaten;
use App\UserProfile;
use DB;
use App\User;
use Laracasts\Flash\Flash;
use App\Http\Controllers\MainCtrl;
use Illuminate\Http\Request as httpRequest;
use App\Rental\Contract\IOrderRepository as currentRepo;
use Yajra\DataTables\Facades\DataTables;
use App\Rental\Models\Order;
use Hash;
use Session;
use Auth;
use Crypt;
use Gate;
class BookingCtrl extends MainCtrl{
    public function __construct(httpRequest $request, currentRepo $repository)
    {
        parent::__construct($request, $repository);
        $this->param = array(
            'view' =>"backend.booking.view",
            'view_show' => "backend.booking.read",
        );
        $this->route = array('index'=>'backend.dashboard.index');
    }

    public function view(){
        // if ($this->request->ajax()) {
            // $toolbar = ConsystHelper::generateToolbarButton(false);
            $toolbar = "";
            $pages = (object) array(
                'title'      => trans('view.daftar_anggota.title'),
                'breadcrumb' => "\Breadcrumbs::render('daftar_anggota')",
                'box_title'  => trans('view.daftar_anggota.box_title'),
                'content'    => '',
                'toolbar'    => $toolbar,
                'subtitle'   => '',
            );

            $html = \view(\Config::get('rental.view_moduls') . $this->param['view'], array('pages' => $pages,'status'=>1))->render();
            return \Response::view($this->param['view']);
        // } else {
        //     return \Response::view('errors.401');
        // }
    }

    public function data(){
        @$input['status'] = $status;
        @$input['keyword'] = $request->keyword;

        $buffer = $this->repository->data_pemesanan();
        return Datatables::of($buffer)
        ->addColumn('action', function ($d) {
            $content = '<div class="btn-group">';
            $content .= '<a href="'.route('admin-booking-view',[$d->order_id]).'" class="btn btn-xs btn-primary btn-edit"><i class="fas fa-paper-plane"></i> </a>';
            $content .= '<a href="#" class="btn btn-xs btn-primary btn-detail"><i class="fa fa-map"></i></a>';
            $content .= '</div>';
            return $content;
        })
        ->addColumn('status_order', function ($d) {
            
            $content = '<a href="#" class="badge bg-cyan">'.$d->status.'</a>';
            
            return $content;
        })
        ->editColumn('order_jenis',function($d){
            $content = ($d->order_jenis==1) ? 'Rentcar' : 'Reguler' ;
            return $content;
        })
        ->editColumn('order_user_id',function($d){

            $content = $d->order_user_id;
            if (isset($d->customer)) {
                $content = $d->customer->name;
            }
            return $content;
        })
        ->editColumn('order_driver_id',function($d){

            $content = 'Belum ada Driver';
            if (isset($d->driver)) {
                $content = $d->driver->name;
            }
            return $content;
        })
        ->editColumn('order_nominal',function($d){
            $content = "Rp. ". number_format($d->order_nominal);
            return $content;
        })
        ->rawColumns(['action', 'status_order'])
        ->make(true);
    
    }

    public function read(httpRequest $request,$id){
        $book = $this->repository->findByField('order_id',$id);
        if ($request->isMethod('post')) {
            $driver = User::find($request->assigned_for);
            $check = Order::where('order_driver_id',$driver->id)->where('order_status','<',4)->where('order_status','>',0)->first();
            // $check = $this->repository->findByField('order_driver_id',$driver->id)->findByField('order_status','<',4)->findWhere('order_status','>',0);
            if ($driver->isavail > 1 || $check != NULL) {
                Flash::error("Ada Transaksi yang belum beres");
            }else{
                $book->order_driver_id = $request->assigned_for;
                $book->order_status = 1;
                $book->save();
                $driver->isavail = 2;
                $driver->save();
            }
            
            return redirect()->route($this->route['index']);
        }
        $drivers = $this->repository->getDrivers();
        return view($this->param['view_show'])->with(['book'=>$book,'drivers'=>$drivers]);
    }

}