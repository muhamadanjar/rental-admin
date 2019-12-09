<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use DataTables;
use DB;
use App\User;
use App\Trip;
use App\Mobil\Repository as MobilInterface;
use App\Notifications\OrderAccept;
use App\Notifications\OrderCompleted;
use Notification;
class TripCtrl extends BackendCtrl
{
    
    public function __construct(MobilInterface $mobil){
        $this->mobil = $mobil;
    }
    public function index()
    {
        $trip = \App\Trip::get();
        return view('backend.trip.index')->withTrip($trip);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function post(Request $request){
        if (session('aksi') == 'edit') {
            $d = Trip::find($request->id);
            if ($d->trip_status < 1) {
                $d->trip_driver = $request->driver;
                $d->trip_status = 1;
                $d->save();
                $user = User::where('username','superadmin')->first();
                $details = [
                    'greeting' => 'Hi Artisan',
                    'body' => 'This is my first notification from ItSolutionStuff.com',
                    'thanks' => 'Thank you for using ItSolutionStuff.com tuto!',
                    'actionText' => 'View My Site',
                    'actionURL' => url('/'),
                    'order_id' => $d->id
                ];
        
                Notification::send($user, new OrderAccept($details));
            }else{
                Flash::info('Driver Sudah di tentukan');
                return redirect()->route('backend.trip_job.index');
            }
        }
        Flash::success("Data Berhasil di perbaharui");
        return redirect()->route('backend.trip_job.index');
    }
    public function show($trip_job){   
        
        $trip = Trip::join('trip_detail','trip.trip_id','trip_detail.trip_id')
        ->where('trip.trip_id',$trip_job)
        ->orderBy('trip_date','DESC')->first();
        if($trip !== NULL) return view('backend.trip.show')->with(['trip'=>$trip]);
        else return dd('Data tidak ada,Silahkan kembali ke halaman sebelumnya');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        session(['aksi'=>'edit']);
        $t = Trip::find($id);
        $sewa_detail = $t->detail()->first();
        $driver = User::isDriver()->get();
        return view('backend.trip.form')->with(['trip'=>$t,'sewaDetail'=>$sewa_detail,'driver'=>$driver]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_data(Request $request){

        \DB::statement(DB::raw('set @rownum=0'));
            $sewa = Trip::join('trip_detail','trip.trip_id','=','trip_detail.trip_id')
            ->leftjoin('tm_driver','trip.trip_driver','=','tm_driver.id')
            ->leftjoin('tm_customer','trip.trip_bookby','=','tm_customer.id')
            ->join('service_type','trip_type','service_type.id')
            ->orderBy('trip.created_at','DESC')
            ->select(
            [
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'trip.trip_id',
                DB::raw('service_type as trip_type'),
                'trip.trip_bookby',
                'trip_code',
                DB::raw("CONCAT(trip_address_origin,' -> ',trip_address_destination) as trip_address_origin"),
                'trip_date',
                'tm_driver.name as driverName',
                'tm_customer.name as customerName',
                \DB::raw("CONCAT('Rp.',FORMAT(trip_total,2)) as trip_total"),
                'trip_status',
            ]
        );
        
    
        return Datatables::of($sewa)
        ->addColumn('action', function ($user) {
            $content = '<div class="btn-group">';
            $content .= '<a href="'.route('backend.trip_job.edit',[$user->trip_id]).'" class="btn btn-xs btn-primary btn-edit"><i class="fa fa-send"></i> </a>';
            $content .= '<a href="#" class="btn btn-xs btn-primary btn-detail"><i class="fa fa-map"></i></a>';
            $content .= '</div>';
            return $content;
        })
        
        ->editColumn('rownum', function ($tr) {
            return '<b>'.$tr->rownum.'</b>';
        })
        ->editColumn('trip_type', function ($tr) {
            return '<span class="label label-info">'.$tr->trip_type.'</span>';
        })
        ->editColumn('trip_date', function ($tr) {
            return '<b>'.date('d M Y',strtotime($tr->trip_date)).'</b>';
        })
        ->editColumn('trip_total', function ($tr) {
            return '<i>'.$tr->trip_total.'</i>';
        })
        ->editColumn('created_at', function ($user) {
            return date('D M Y',strtotime($user->created_at));
        })
        ->addColumn('details_url', function($user) {
            return url('backend/trip_job/'.$user->trip_id);
        })
        ->editColumn('trip_status', function($transaksi){
            $class = $transaksi->trip_status == '0' ? 'bg-orange' : 'label-success';
            return '<span class="label '.$class.'">'.$transaksi->status.'</span>';
        })
        ->rawColumns(['rownum', 'action','trip_type','trip_status','trip_total','trip_date'])
        ->filter(function ($query) use ($request) {
            if ($request->has('status')) {
                $query->where('trip_status', 'like', "%{$request->get('status')}%");
            }
            if ($request->has('tgl_mulai')) {
                $query->whereRaw("DATE_FORMAT(trip_date,'%H:%i:%s') like ?", ["%{$request->get('tgl_mulai')}%"]);
            }

            if ($request->has('sq')) {
                $query->whereRaw("DATE_FORMAT(sewa.date,'%H:%i:%s') like ?", ["%{$request->get('sq')}%"])
                    ->orWhere('trip_address_origin', 'like', "%{$request->get('sq')}%")
                    ->orWhere('trip_address_destination', 'like', "%{$request->get('sq')}%")
                    ->orWhere('trip_code', 'like', "%{$request->get('sq')}%");
            }
            
        })
        ->make(true);
    }
}
