@extends('templates.adminlte.main')
@section('alert')
   
@endsection
@section('content-admin')
    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">DAFTAR DOWNLOAD DOKUMEN</h3>
            <div class="card-tools pull-right">
                <?php if(\Gate::check('create.dokumen')){ ?>
                <a href="{{ route('backend.dokumen.tambah') }}" class="btn btn-primary pull-right"><span class="fa fa-plus"></span> Tambah</a>
                <?php } ?>
            </div>
        </div>
            <!-- /.card-header -->
        <div class="card-body">
        	<table class="display table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Dokumen</th>
                        <th>Download</th>
                        
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($dokumen as $key => $p)
                    
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->judul }}</td>
                        <td><a href="{{ route('backend.dokumen.download',array($p->id)) }}" class="btn btn-xs btn-success">Download</a></td>
                                
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>

@endsection

@section('script-end')
@parent
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
@endsection