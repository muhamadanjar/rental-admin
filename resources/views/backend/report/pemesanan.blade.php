@extends('templates.adminlte.main')
@section('content-admin')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Pemesanan</h3>
            </div>
            <div class="card-body">
    
                <div class="form-group">
                    <label for="tgl_mulai">Tanggal Mulai</label>
                    <input type="text" class="form-control" name="sdate" id="sdate">
                </div>
                <div class="form-group">
                    <label for="tgl_mulai">Tanggal Akhir</label>
                    <input type="text" class="form-control" name="sdate" id="sdate">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Submit</button>
                </div>


            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md12">

    </div>
</div>
@endsection