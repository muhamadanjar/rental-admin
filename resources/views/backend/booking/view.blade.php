@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">Trip</h3>
            <div class="card-tools text-right">
                <div class="btn-group">
                    {{-- <a href="{{ route('backend.promo.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a> --}}
                    
                </div>
            </div>
            
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table table-bordered" cellspacing="0" width="100%" id="table_data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Trip Type</th>
                        <th>Book By</th>
                        <th>Book No</th>
                        <th>Jemput</th>
                        <th>Tujuan</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Driver</th>
                        <th>Customer</th>
                        <th>Fare</th>   
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    
                </tbody>
                
            </table>
        </div>
    </div>

@endsection
@section('title','Data Transaksi')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="https://openlayers.org/en/v5.3.0/css/ol.css" type="text/css">

<style>
	
    .map_transaksi {
    height: 300px;
    width: 100%;
  }

</style>
@endsection
@section('script-end')
@parent

<script type="text/javascript" src="{{ url('/plugins/jquery-ui/js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{ url('/plugins/bootbox/js/bootbox.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>

$(function(){
    $("#table_data").DataTable({
        pageLength: '10',
        ajax: '{!! route('admin-booking-ajaxData') !!}',
        ordering: false,
        responsive:true,
        aoColumns: [
            { "mData": "order_code"},
            { "mData": "order_jenis"},
            { "mData": "created_by"},
            { "mData": "order_code"},
            { "mData": "order_address_origin", "width": 200},
            { "mData": "order_address_destination"},
            { "mData": "order_tgl_pesanan"},
            { "mData": "order_driver_id"},
            { "mData": "order_user_id"},
            { "mData": "order_nominal"},
            { "mData": "status_order"},
            { "mData": "action"},
        ],
        "columnDefs": [
            { "width": "10px", "targets": 2 },
            { "width": "10px", "targets": 4 }
        ]
    });
});
</script>
@endsection