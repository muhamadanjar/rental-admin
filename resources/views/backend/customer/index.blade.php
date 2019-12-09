@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="box-header with-border">
            <h3 class="box-title">Customer</h3>
            <div class="box-tools text-right">
                <div class="btn-group">
                    {{-- <a href="{{ route('backend.customer.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a> --}}
                </div>
            </div>
            
        </div>
            <!-- /.box-header -->
        <div class="box-body">
        	<table class="display table" cellspacing="0" width="100%" id="table_promo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        
                        <th>Saldo</th>
                        
                        <th>Status</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($data as $k => $v)
                    <tr>
                        <td></td>
                        <td>{{ $v->name}}</td>
                        <td>{{ $v->email}}</td>
                        <td>{{ $v->no_telepon}}</td>
                        
                        <td>Rp.{{ number_format($v->wallet) }} <button data-form="#frm_saldo-{{$v->id}}" data-userid="{{ $v->id }}"  class="btn btn-sm btn-success btn-xs formConfirmSaldo">Tambah Saldo</button></td>
                        
                        <td class="text-center">
                        	@if($v->isactived == 1)
                        		<span><i class="fa fa-check text-green"></i></span>
                        	@else
                        		<span><i class="fa fa-close text-red"></i></span>
                        	@endif
                        	</td>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-xs btn-icon dropdown-toggle" type="button"><i class="icon-cog4"></i><span class="caret"></span></button>
                                    <ul class="dropdown-menu icons-right dropdown-menu-right">
                                        <li><a class="dropdown-item" href="{{url('/backend/customer/'.$v->id.'/edit')}}"><i class="fa fa-edit"></i> Ubah</a>
                                        </li>
                                        <li class=""
                                            data-form="#frmaktif-{{$v->id}}" 
                                            data-title="Aktif {{ $v->id }}" 
                                            data-message="Apa anda yakin mengaktifkan/menonaktifkan {{ $v->name }} ?">
                                            <a class= "dropdown-item formConfirm" href="#"><i class="fa fa-trash"></i> Hapus</a>
                                        </li>
                                        <form action="{{ route('backend.customer.destroy', array($v->id) ) }}" method="post" style="display:none" id="frmaktif-{{$v->id}}">
                                            <input type="hidden" name="_method" value="delete">
                                            {{ @csrf_field() }}
                                        </form>
                                    </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

@endsection
@section('title','Customer')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('/plugins/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{url('/plugins/fancybox/source/jquery.fancybox.css?v=2.1.7')}}" type="text/css" media="screen" />

@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{url('/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.7')}}"></script>
<script type="text/javascript" src="{{ url('/plugins/jquery-ui/js/jquery-ui.js')}}"></script>

<script type="text/javascript" src="{{ url('/plugins/datatables/datatables.min.js')}}"></script>


<script src="{{ asset('/js/rm.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox();
        $(".desctooltips").tooltip();
        $('.formConfirmSaldo').on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) return;
            var el = $(this);
            var userId = el.attr('data-userId');
            $('#formSaldo').find('#iRiderId').val(userId);
            $('#formSaldo')
            .find('form').attr('action',"<?php echo url('backend/customer/addsaldo')?>");
            $('#formSaldo').modal('show');

        });

        $('#formSaldo').on('click', '#frm_submit', function (e) {
            var id = $(this).attr('data-form');
            //console.log(id);
            $(id).submit();
        });
    });
</script>
@endsection