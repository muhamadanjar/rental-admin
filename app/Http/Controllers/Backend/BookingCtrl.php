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
use Yajra\Datatables\Facades\Datatables;
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

    public function data($input){
        @$input['status'] = $status;
        @$input['keyword'] = $request->keyword;

        $buffer = $this->repository->data_pemesanan($input);

        return Datatables::of($buffer)
            ->make(true);
    
    }

}