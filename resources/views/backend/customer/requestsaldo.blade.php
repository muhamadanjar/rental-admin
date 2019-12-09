@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="box-header with-border">
            <h3 class="box-title">Request Saldo</h3>
            <div class="box-tools text-right">
                <div class="btn-group">
                    {{-- <a href="{{ route('backend.customer.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a> --}}
                </div>
            </div>
            
        </div>
            <!-- /.box-header -->
        <div class="box-body">
        	<table class="display table" cellspacing="0" width="100%" id="table_dom">
                <thead>
                    <tr>
                        <th>No</th>
                        {{-- <th>Code</th> --}}
                        <th>Nama</th>
                        <th>No Rekening</th>
                        <th>Saldo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php $no =1;?>
                    @foreach($rs as $k => $v)
                    <?php $disabled = $v->status == 1 ? 'disabled':'' ?>
                    <tr>
                        <td>{{ $no}}</td>
                        {{-- <td>{{ $v->req_code}}</td> --}}
                        <td>{{ $v->name}}</td>
                        <td>{{ $v->req_norek}}</td>
                        <td>{{ number_format($v->req_saldo,2,",",".")}}</td>
                        <td>{!! $v->status == 1 ? "<i class='fa fa-check text-green'></i>":"<i class='fa fa-close text-red'></i>" !!}</td>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-xs btn-icon dropdown-toggle" type="button"><i class="icon-cog4"></i><span class="caret"></span></button>
                                    <ul class="dropdown-menu icons-right dropdown-menu-right">
                                        <li
                                            data-image="{{ asset('files/uploads/bukti') }}/{{ $v->req_file}}"    
                                        ><a class="dropdown-item formPreview" href=""><i class="fa fa-eye"></i> Cek</a></li>
                                        <li class="{{$disabled}}"
                                        data-id="{{$v->id}}"     
                                        data-form="#frmSaldo-{{$v->id}}" 
                                            data-title="Konfirmasi saldo {{ $v->id }}" 
                                            data-image="{{ asset('files/uploads/bukti') }}/{{ $v->req_file}}"
                                            data-message="Apa anda yakin konfirmasi saldo {{ $v->name }} dengan {{ $v->req_saldo }} ?">
                                            <a class= "dropdown-item formConfirmSaldo {{$disabled}}" href="#"><i class="fa fa-check"></i> Konfirmasi</a>
                                        </li>
                                        <form action="{{ route('backend.customer.accept_request_saldo', array($v->id) ) }}" method="post" style="display:none" id="frmSaldo-{{$v->id}}">
                                            {{ @csrf_field() }}
                                            <input type="hidden" name="id" id="request_id">
                                        </form>
                                    </ul>
                            </div>
                        </td>
                    </tr>
                    <?php $no++; ?>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

@endsection
@section('title','Request Saldo')
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

<script>
    $('.formPreview').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;
        var el = $(this).parent();
        var image = el.attr('data-image');
        $('#formConfirm').find('#frm_body')
        .html(`<img class='img img-responsive' src='${image}'>`)
        .end().find('#frm_title').html('Bukti Transaksi')
        .end().find('#frm_submit').addClass('hidden')
        .end().find('#frm_cancel').text('Tutup')
        .end()
        .modal('show');
    });

    $('.formConfirmSaldo').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;
        var el = $(this).parent();
        var message = el.attr('data-message');
        var form = el.attr('data-form');
        var id = el.attr('data-id');
        $('#formConfirm').find('#frm_body')
        .html(`<h4>${message}</h4>`)
        .end().find('#frm_submit').removeClass('hidden')
        .end()
        .modal('show');
        $(form).find('#request_id').val(id);
        
        $('#formConfirm').find('#frm_submit').attr('data-form', form);
        console.log($(form));
        $('#formConfirm').on('click', '#frm_submit', function (e) {
            var id = $(this).attr('data-form');
            //console.log(id);
            $(id).submit();
        });
    });

    

</script>
@endsection