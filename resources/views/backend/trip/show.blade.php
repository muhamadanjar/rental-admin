@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="box-header with-border">
            <h3 class="box-title">Trip</h3>
            <div class="box-tools text-right">
                <div class="btn-group">
                    {{ $trip->trip_code}}
                </div>
            </div>
            
        </div>
            <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
                    <div id="map" class="map"></div>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tr>
                            <td>Tanggal Booking</td>
                            <td>:</td>
                            <td>{{date('d M Y H:i:s',strtotime($trip->trip_date))}}</td>
                        </tr>
                        <tr>
                            <td>Asal</td>
                            <td>:</td>
                            <td>{{$trip->trip_address_origin}}</td>
                        </tr>
                        <tr>
                            <td>Tujuan</td>
                            <td>:</td>
                            <td>{{$trip->trip_address_destination}}</td>
                        </tr>
                        <tr>
                            <td>Di Pesan</td>
                            <td>:</td>
                            <td>{{$trip->trip_book_by}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>{{$trip->trip_status}}</td>
                        </tr>
        
                    </table>
                </div>
            </div>
            
        </div>
    </div>

@endsection
@section('title','Data Transaksi')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('/plugins/datatables/datatables.min.css')}}">

@endsection
@section('script-end')
@parent

<script type="text/javascript" src="{{ url('/plugins/jquery-ui/js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{ url('/plugins/bootbox/js/bootbox.js') }}"></script>
<script type="text/javascript" src="{{ url('/plugins/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('/js/rm.js') }}"></script>


@endsection