@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">Promo</h3>
            <div class="card-tools text-right">
                <div class="btn-group">
                    <a href="{{ route('backend.promo.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a>
                </div>
            </div>
            
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table" cellspacing="0" width="100%" id="table_promo">
                <thead>
                    <tr>
                        <th></th>
                        <th>Kode</th>
                        <th>Promo</th>
                        <th>Dari</th>
                        <th>Sampai</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($promo as $k => $v)
                    <tr>
                        <td></td>
                        <td>{{ $v->kode_promo}}</td>
                        <td>{{ $v->name}}</td>
                        <td>{{ date('d-m-Y', strtotime($v->tgl_mulai))}}</td>
                        <td>{{ date('d-m-Y', strtotime($v->tgl_akhir))}}</td>
                        <td><span title="{{$v->description}}" class="desctooltips">{{ substr($v->description,0,40)}}...</span>
                        </td>
                        <td><a class="fancycard" data-fancycard="fancycard" href="{{asset('files/uploads/promo/'.$v->foto)}}"><img src="{{asset('files/uploads/promo/'.$v->foto)}}" height="50px" width="50px" title="klik gambar untuk memperbesar" /></a></td>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-xs btn-icon dropdown-toggle" type="button"><i class="icon-cog4"></i><span class="caret"></span></button>
                                    <ul class="dropdown-menu icons-right dropdown-menu-right">
                                        <li><a class="dropdown-item" href="{{ route('backend.promo.edit', ['id' => $v->id]) }}"><i class="fa fa-edit"></i> Ubah</a></li>
                                        <li class=""
                                            data-form="#frmaktif-{{$v->id}}" 
                                            data-title="Aktif {{ $v->id }}" 
                                            data-message="Apa anda yakin mengaktifkan/menonaktifkan {{ $v->name }} ?">
                                            <a class= "dropdown-item formConfirm" href="#"><i class="fa fa-trash"></i> Hapus</a>
                                        </li>
                                        <form action="{{ route('backend.promo.destroy', array($v->id) ) }}" method="post" style="display:none" id="frmaktif-{{$v->id}}">
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
@section('title','Promo')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('/plugins/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{url('/plugins/fancycard/source/jquery.fancycard.css?v=2.1.7')}}" type="text/css" media="screen" />

@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{url('/plugins/fancycard/source/jquery.fancycard.pack.js?v=2.1.7')}}"></script>
<script type="text/javascript" src="{{ url('/plugins/jquery-ui/js/jquery-ui.js')}}"></script>

<script type="text/javascript" src="{{ url('/plugins/datatables/datatables.min.js')}}"></script>


<script src="{{ asset('/js/rm.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".fancycard").fancycard();
        $(".desctooltips").tooltip();
    });
</script>
@endsection