@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">Request Saldo</h3>
            <div class="card-tools text-right">
                <div class="btn-group">
                    
                </div>
            </div>
            
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table" cellspacing="0" width="100%" id="table_data">
                <thead>
                    <tr>
                        
                        <th>User ID</th>
                        <th>Request Saldo</th>
                        <th>Request Code</th>
                        <th>Bukti TF</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($data as $k => $v)
                    <tr>
                        <td>@isset($v->user)
                            {{ $v->user->name }}
                        @endisset  </td>
                        <td>{{ 'Rp '. number_format($v->req_saldo,2,',','.')}}</td>
                        <td>{{ $v->req_code}}</td>
                        <td>
                            <a class="fancybox" data-toggle="lightbox" data-fancybox="fancybox" href="{{$v->PathFull}}"><img src="{{$v->PathFull}}" height="50px" width="50px" title="klik gambar untuk memperbesar" /></a>
                        </td>
                        <td class="text-center">
                        	@if($v->status == 1)
                        		<span><i class="fas fa-check text-green"></i></span>
                        	@else
                        		<span><i class="fas fa-close text-red"></i></span>
                        	@endif
                        	</td>
                        <td>
                            <div class="btn-group">
                                @if($v->status == 1) 
                                    <form action="{{url('/backend/reqsaldo/'.$v->id.'/konfirmasi')}}" method="POST">
                                        {{ csrf_field() }}                          
                                        <button type="submit" class="btn btn-success btn-sm" name="changeStatus" value="0" disabled="">Konfirmasi</button>
                                    </form>                    
                                @else
                                    <form action="{{url('/backend/reqsaldo/'.$v->id.'/konfirmasi')}}" method="POST">
                                        {{ csrf_field() }}                              
                                        <button type="submit" class="btn btn-success btn-disable btn-sm" name="changeStatus" value="1" onclick="return confirm('Apakah Anda Yakin ingin mengkonfirmasi {{$v->req_user_id}} dengan saldo {{$v->req_saldo}} ?')">Konfirmasi</button>
                                    </form>                                                 
                                @endif
                            </div>
                        </td>
                    </tr>
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
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{url('/plugins/fancybox/source/jquery.fancybox.css?v=2.1.7')}}" type="text/css" media="screen" />

@endsection
@section('script-end')
@parent
{{-- <script type="text/javascript" src="{{ asset('/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.7')}}"></script> --}}
<script type="text/javascript" src="{{ asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>

<script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script src="{{ asset('/js/rm.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // $(".fancybox").fancybox();
        // $(".desctooltips").tooltip();
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
        
        $('#table_data').dataTable({

        });
    });
</script>
@endsection