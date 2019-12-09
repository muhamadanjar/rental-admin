@extends('templates.adminlte.main')
@section('content-admin')
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">DAFTAR UPLOAD DOKUMEN</h3>
            <div class="card-tools pull-right">
                <div class="btn-group">
                    <a href="{{ route('backend.dokumen.tambah') }}" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> Tambah</a>
                </div>
            </div>
            
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table" cellspacing="0" width="100%" id="table_dom">
                <thead>
                    <tr>
                        <th></th>
                        <th>Judul Dokumen</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($dokumen as $key => $p)
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-sm btn-default btn-flat dropdown-toggle" type="button">
                                <i class="caret"></i>&nbsp;
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('backend.dokumen.edit',array($p->id,'edit')) }}"><i class="fa fa-edit"></i> Edit</a></li>
                                    <li data-form="#frmDelete-{{ $p->id }}" 
                                        data-title="Hapus Informasi" 
                                        data-message="Anda yakin menghapus informasi ini ?">
                                        <a href="#" class="formConfirm"><i class="fa fa-trash"></i> Hapus</a></li>
                                        <form 
                                            action="{{ route('backend.dokumen.delete',array($p->id)) }}" 
                                            method="post" 
                                            style="display:none" 
                                            id="frmDelete-{{ $p->id }}">
                                            {{ csrf_field() }}
                                            <input name="_method" type="hidden" value="DELETE">    
                                        </form>
                                    <li><a href="{{ route('backend.dokumen.download',array($p->id)) }}"><i class="fa fa-download"></i> Download</a></li>                                            
                                </ul>
                            </div>
                        </td>
                        <td>{{ $p->judul }}</td>
                        <td>{{ strip_tags($p->deskripsi) }}</td>
                        <td>{{ date('M d, Y', strtotime($p->update_date)) }}</td>        
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

@endsection

@section('style-head')
@parent

@endsection
@section('script-end')
@parent

<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>

@endsection