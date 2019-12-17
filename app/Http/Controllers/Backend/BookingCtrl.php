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
            'view_man' => "booking.man",
            'view_contact'  => "booking.contact_person",
            'view_show' => "booking.show",
        );
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

            $html = \view(\Config::get('consyst.view_moduls') . $this->param['view'], array('pages' => $pages,'status'=>1))->render();
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
            $book->order_driver_id = $request->assigned_for;
            $book->save();

            $driver = User::find($request->assigned_for);
            $driver->isavail = 0;
            $driver->save();

            return redirect()->route('backend.dashboard.index');
        }
        $drivers = $this->repository->getDrivers();
        return view('backend.booking.read')->with(['book'=>$book,'drivers'=>$drivers]);
    }

}