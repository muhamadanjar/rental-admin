@extends($ctemplates.'.main')
@section('title')
<h5>
    <a href="{{ route('backend.packages.index') }}"><i class="icon-arrow-left52 mr-2"></i></a>
    <span class="font-weight-semibold">Paket</span>
    <small class="d-block text-muted">Manajemen Data Paket</small>
</h5>
@endsection
@section('content-admin')
<?php

if (session('status') == 'edit') {
	$id = $paket->rp_id;
    $rp_name = $paket->rp_name;
    $rp_total_price = $paket->rp_total_price;
    $rp_miles_km = $paket->rp_miles_km;
    $rp_hour = $paket->rp_hour;
    $rp_add_mile_km = $paket->rp_add_mile_km;
    $rp_add_min = $paket->rp_add_min;
	$status = $paket->status;
	$image = $paket->image;
	$imagePath = $paket->imagePath;
} else {
	$id = "";
    $rp_name = "";
    $rp_total_price = "";
    $rp_miles_km = "";
    $rp_hour = "";
    $rp_add_mile_km = "";
    $rp_add_min = "";
	$status ="";
	$image = "";
	$imagePath = "";

}
?>

<form role="form" method="POST" enctype="multipart/form-data" action="{{ route('backend.packages.post',array('type'=>$type)) }}">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="card card-default">
				<div class="box-header with-border">
					<h6 class="box-title"><i class="fa fa-road"></i> Form Paket ({{$type}})</h6>
					<div class="header-elements text-right">
                        <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa fa-send ico-save"></i> Simpan
                            </button>
							<a href="{{ route('backend.packages.index') }}" class=" btn btn-sm btn-success ">
                            <i class="fa fa-mail-reply"></i> Kembali</a>
                        </div>
                    </div>
				</div>
				<div class="box-body">
					
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" value="{{$id}}">
					
						
					<div class="form-group {{ $errors->has('rp_name') ? ' has-error' : '' }}">
						<label>Nama Paket</label>
						<input type="text" class="form-control" name="rp_name" value="{{ $rp_name }}" required>
						<div class="col-md-6"></div>
					</div>

                    <div class="form-group {{ $errors->has('rp_total_price') ? ' has-error' : '' }}">
						<label>Harga Paket</label>
						<input type="text" class="form-control" name="rp_total_price" value="{{ $rp_total_price }}" required>
						<div class="col-md-6"></div>
					</div>

					<div class="form-group {{ $errors->has('rp_miles_km') ? ' has-error' : '' }}">
						<label>Harga Per Km</label>
						<input type="text" class="form-control" name="rp_miles_km" value="{{ $rp_miles_km }}" required>
						<div class="col-md-6"></div>
                    </div>
                    <div class="form-group {{ $errors->has('rp_hour') ? ' has-error' : '' }}">
						<label>Harga Per Jam</label>
						<input type="text" class="form-control" name="rp_hour" value="{{ $rp_hour }}" required>
						<div class="col-md-6"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group {{ $errors->has('rp_add_mile_km') ? ' has-error' : '' }}">
								<label for="rp_add_mile_km">Harga Setiap Km</label>
								<input type="text" class="form-control" id="rp_add_mile_km" name="rp_add_mile_km" value="{{$rp_add_mile_km}}" placeholder="Harga Setiap Km" required>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group {{ $errors->has('rp_add_min') ? ' has-error' : '' }}">
								<label for="rp_add_mile_km">Harga Setiap Menit</label>
								<input type="text" class="form-control" id="rp_add_min" name="rp_add_min" value="{{$rp_add_min}}" placeholder="Harga Setiap Menit" required>
							</div>
						</div>	
					</div>
					
					<div class="form-inline {{ $errors->has('status') ? ' has-error' : '' }}">
						<label for="no_ruas_pangkal" class="">Status</label>
                        <input type="checkbox" id="status" name="status" {{ ($status == 1 ? "checked":"")}} value="1" required class="checkbox">
                        
					</div>


					<div class="col-md-12">
						
						<div class="foto">
							<img src="{{ $imagePath}}"" alt="{{$rp_name}}" class="img-responsive imgfoto" width="100%">
						</div>
						<div class="input-group margin controlupload">
							<input type="text" class="form-control txtfoto" readonly="readonly" name="foto" value="{{ $image }}">
							<span class="input-group-btn">
								<input type="file" name="users_file" class="hidden file fileupload" 
								data-url="{{ route('backend.packages.upload')}}" 
								data-type="single"
								data-path="{{ asset('/files/uploads/packages/')}}">
								<button type="button" class="btn btn-info btn-flat formUpload">Foto!</button>
							</span>
						</div>
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

