@extends('templates.adminlte.main')
@section('content-admin')


    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">Layer</h3>
            <div class="card-tools text-right">
                <div class="btn-group">
                    <a href="{{ route('backend.layer.create') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a>
                    
                </div>
            </div>
            
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table" cellspacing="0" width="100%" id="table_layer">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Layer</th>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Group</th>
                        <th>Urutan</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($layer as $key => $p)
                    <?php $disabled = ($p->jsonfield != null) ? '' : 'disabled' ;  ?>					
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default btn-flat dropdown-toggle" type="button">
                                <i class="caret"></i>&nbsp;
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('backend.layer.edit',array($p->id,'edit')) }}"><i class="fa fa-edit"></i> Edit</a></li>
                                    <li data-form="#frmDelete-{{ $p->id }}" 
                                        data-title="Hapus Informasi" 
                                        data-message="Anda yakin menghapus informasi ini ?">
                                        <a href="#" class="formConfirm"><i class="fa fa-trash"></i> Hapus</a></li>
                                        <form 
                                            action="{{ route('backend.layer.destroy',array($p->id)) }}" 
                                            method="post" 
                                            style="display:none" 
                                            id="frmDelete-{{ $p->id }}">
                                            {{ csrf_field() }}
                                            <input name="_method" type="hidden" value="DELETE">    
                                        </form>
                                    <li class="divider"></li>
					                <li><a href="{{ route('backend.layer.info',array($p->id)) }}">
					                    <i class="fa fa-bars"></i> Setting PopUp</a>
                                    </li>
					                <li class="{{$disabled}}" 
					                    data-form="#frmCEsri-{{ $p->id_layer }}" 
					                    data-title="Hapus data esri {{ $p->layername }}" 
					                    data-message="apa anda yakin menghapus data esri {{ $p->layername }} ?">
                                        <a class= "formConfirm" href="#"><i class="fa fa-bell" disabled></i> Hapus data Esri</a>
					                </li>
					                <form 
					                    action="{{ url('/layers/layeresrihapus', array($p->id_layer)) }}" 
					                    method="get" 
					                    style="display:none" 
					                    id="frmCEsri-{{ $p->id_layer}}"></form>
                                </ul>
                            </div>
                        </td>
                        <td>{{ $p->namalayer }}</td>
                        <td><span class="badge badge-primary">{{ $p->kodelayer }}</span></td>
                        <td><span class="badge bg-green">{{ $p->tipelayer }}</span></td>
                        <td>
                            @if(isset($p->groups))
                            <span class="badge bg-blue">{{ $p->groups->namalayer }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td><span class="badge   bg-green">{{ $p->urutanlayer }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

@endsection
@section('title','Layer')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('/plugins/datatables/datatables.min.css')}}">
@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{ url('/plugins/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{ url('/plugins/bootcard/js/bootbox.js') }}"></script>

<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>

@endsection