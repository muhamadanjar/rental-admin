@extends('templates.adminlte.main')

@section('content-admin')

<form role="form" method="POST" enctype="multipart/form-data" action="{{ route('customer.post')}}">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="card card-default">
				<div class="box-header with-border">
					<h6 class="box-title"><i class="fa fa-pencil"></i> Edit Customer</h6>
					<div class="header-elements text-right">
                        <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa fa-send ico-save"></i> Simpan
                            </button>
							<a href="{{ route('customer') }}" class=" btn btn-sm btn-success ">
                            <i class="fa fa-mail-reply"></i> Kembali</a>
                        </div>
                    </div>
				</div>
				<div class="box-body">
					{{ @csrf_field() }}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Status</label>
								<select name="status" class="form-control">
									<option value="1" @if($data->isactived == 1) selected @endif>Aktif</option>
									<option value="0" @if($data->isactived == 0) selected @endif>Non Aktif</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" class="form-control" name="name" value="{{$data->name}}" required>
						<div class="col-md-6"></div>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" value="{{$data->email}}" required>
						<div class="col-md-6"></div>
					</div>
					<div class="row">
						{{-- <div class="col-md-2">
							<div class="form-group">
								<label>JK</label>
								<select name="sex" class="form-control">
									<option value="Laki-laki" @if($data->sex == "Laki-laki") selected @endif>L</option>
									<option value="Perempuan" @if($data->Perempuan)=="Perempuan" selected @endif>P</option>
								</select>	
							</div>
						</div> --}}
						{{-- <div class="col-md-3">
							<div class="form-group">
								<label>Agama</label>
								<select name="religion" class="form-control">
									<option value="islam" @if($data->religion == 'islam') selected @endif>islam</option>
									<option value="katholik" @if($data->religion == 'katholik') selected @endif>katholik</option>
									<option value="protestan" @if($data->religion == 'protestan') selected @endif>protestan</option>
									<option value="hindu" @if($data->religion == 'hindu') selected @endif>hindu</option>
									<option value="buddha" @if($data->religion == 'buddha') selected @endif>buddha</option>
									<option value="konghucu" @if($data->religion == 'konghucu') selected @endif>konghucu</option>
								</select>	
							</div>
						</div> --}}
						<div class="col-md-3">
							<div class="form-group">
								<label>No Telp</label>
								<input type="text" class="form-control" name="no_telepon" value="{{$data->no_telepon}}" required>
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" id="password" class="form-control" name="password" value="{{$data->password}}">
								<input type="hidden" class="form-control" name="oldpassword" value="{{ $data->password }}">
							</div>
						</div>
						{{-- <div class="col-md-4">
							<div class="form-group">
								<label>Tanggal Lahir</label>
								<input type="date" class="form-control" name="tgl_lahir" value="{{$data->tgl_lahir}}" required>
							</div>
						</div> --}}
					</div>
					{{-- <div class="form-group">
						<label>Alamat</label>
							<textarea class="form-control" rows="4" name="address">{{$data->address}}</textarea>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Kota</label>
								<select name="city_id" class="form-control">
									<option value="0">---</option>
									@foreach($kota as $kotas)
									<option value="{{$kotas->kode_kab}}" @if($data->city_id == $kotas->kode_kab) selected @endif>{{$kotas->nama_kabupaten}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label>Pendidikan</label>
								<input type="text" class="form-control" name="education" value="{{$data->education}}" required>	
							</div>
						</div> --}}
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">
							Simpan
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
        
    </div>
    
</form>
@endsection
@section('style-head')
@parent
<style>
/* ===========
File Manager
============== */
.file-man-box {
  padding: 10px;
  border: 2px solid #dfe3e6;
  border-radius: 0;
  position: relative;
  margin-bottom: 10px; }
  .file-man-box .file-close {
    color: #f15642;
    position: absolute;
    line-height: 24px;
    font-size: 24px;
    right: 10px;
    top: 10px;
    visibility: hidden; }
  .file-man-box .file-img-box {
    line-height: 120px;
    text-align: center; }
    .file-man-box .file-img-box img {
      height: 64px; }
  .file-man-box .file-download {
    font-size: 32px;
    color: #98a6ad;
    position: absolute;
    right: 10px; }
    .file-man-box .file-download:hover {
      color: #313a46; }
  .file-man-box .file-man-title {
    padding-right: 25px; }
  .file-man-box:hover {
    border-color: #4489e4; }
    .file-man-box:hover .file-close {
      visibility: visible; }
</style>
@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{ url('/plugins/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
		$('.select2').select2();
		//Date picker
		// loadKecamatan(3271);
		// CKEDITOR.replace( 'keterangan', {
        //     toolbar : [
		// 		{ name: 'basicstyles', items : [ 'Bold','Italic' ] },
		// 		{ name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
		// 		{ name: 'tools', items : [ 'Maximize','-' ] }
		// 	]
        // } );
    
	});
</script>

@endsection
