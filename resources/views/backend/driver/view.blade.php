@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">Data Driver</h3>
            <div class="card-tools text-right">
                <div class="btn-group">
                    <a href="{{ route('driver.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a>
                    
                </div>
            </div>
            
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table table-bordered" cellspacing="0" width="100%" id="table_data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        <th>Saldo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    
                </tbody>
                
            </table>
        </div>
    </div>



    <div id="formSaldo" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="frm_title">Tambah Saldo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
    
                <div class="modal-body" id="frm_body">
                <form action="" method="post" id="frm_saldo">
                    {{ @csrf_field() }}
                <div class="col-md-12">
                    <input type="hidden" id="action" name="action" value="addmoney">
                    <input type="hidden"  name="eTransRequest" id="eTransRequest" value="">
                    <input type="hidden"  name="eType" id="eType" value="Credit">
                    <input type="hidden"  name="eFor" id="eFor" value="Deposit">
                    <input type="hidden"  name="user_id" id="iRiderId" value="">							
                    <input type="hidden"  name="eUserType" id="eUserType" value="Rider">	
                        <div class="input-group input-append">
                            <h5>Entered Amount Will Be Added Directly To Rider's Account.</h5>
                            <div class="ddtt">
                            <h4>Enter Amount</h4>
                            <input type="text" name="wallet" id="saldo" class="form-control iBalance add-ibalance" onkeyup="checkzero(this.value);">
                            </div>
                            <div id="iLimitmsg"></div>										
                        </div>
                        
                </div>
                </form>
                
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('title','Data Driver')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('/plugins/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
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

<script>

$(function(){
    var table = $("#table_data").DataTable({
        pageLength: '10',
        ajax: '{!! route('driver-ajaxData') !!}',
        ordering: false,
        aoColumns: [
            { "mData": "name"},
            { "mData": "name"},
            { "mData": "email"},
            { "mData": "phonenumber"},
            { "mData": "saldo"},
            { "mData": "status"},
            { "mData": "action"},
        ],
        "columnDefs": [
            { "width": "10px", "targets": 2 },
            { "width": "10px", "targets": 4 },
            { "width": "10px", "targets": 5 }
        ]
    });
    $('#table_data tbody').on('click', 'a',function(e) {
        var data =  table.row($(this).parents('tr')).data();
        console.log(data);

        if ($(this).hasClass('formConfirmSaldo')) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) return;
            var el = $(this);
            var userId = el.attr('data-userId');
            $('#formSaldo').find('#iRiderId').val(userId);
            $('#formSaldo')
            .find('form').attr('action',"{{ route('driver.addsaldo') }}");
            $('#formSaldo').modal('show');    
        }
        
    });

    $('#formSaldo').on('click', '#frm_submit', function (e) {
        var id = $(this).attr('data-form');
        //console.log(id);
        $(id).submit();
    });
});
</script>
@endsection