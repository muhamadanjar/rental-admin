@extends($ctemplates.'.main')
@section('title')
<h5>
    <a href="{{ route('backend.packages.index') }}"><i class="icon-arrow-left52 mr-2"></i></a>
    <span class="font-weight-semibold">Transaksi</span>
    <small class="d-block text-muted">Pemilihan Driver</small>
</h5>
@endsection
@section('content-admin')
<?php

if (session('aksi') == 'edit') {
    $id = $trip->trip_id;
    $trip_code = $trip->trip_code;
    $origin = $trip->trip_address_origin;
    $destination = $trip->trip_address_destination;
    $trip_date_awal = $trip->trip_start;
    $trip_date_akhir = $trip->trip_end;
    $driverId = $trip->trip_driver;
    $status = $trip->status;
    $total = $trip->trip_total;
    $customerName = "";
    $customerEmail = "";
    $customerTelp = "";
    if(isset($trip->rider)){
        $customerName = $trip->rider->name;
        $customerEmail = $trip->rider->email;
        $customerTelp = $trip->rider->profile->no_telepon;
    }
    
} else {
	$id = "";
    $trip_code = "";
    $alamat = "";
    $rp_miles_km = "";
    $rp_hour = "";
    $rp_add_mile_km = "";
    $rp_add_min = "";
    $status ="";

}
?>

<form role="form" method="POST" enctype="multipart/form-data" action="{{ route('backend.trip_job.post')}}">
	<div class="row">
		<div class="col-md-8">
			<div class="card card-default">
				<div class="box-header with-border">
					<h6 class="box-title"><i class="fa fa-road"></i> Form Pemesanan</h6>
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
					<input type="hidden" name="id" value="{{ $id }}">
					
						
					<div class="form-group {{ $errors->has('rp_name') ? ' has-error' : '' }}">
						<label>No Pemesanan</label>
						<input type="text" class="form-control" name="rp_name" value="{{ $trip_code }}" required>
						<div class="col-md-6"></div>
                    </div>
                    
                    <div class="form-group">
						<label>Tanggal Mulai</label>
						<input type="text" class="form-control" name="trip_date_awal" id="trip_date_awal" value="{{ $trip_date_awal }}">
						<div class="col-md-6"></div>
					</div>

                    <div class="form-group">
						<label>Tanggal Akhir</label>
						<input type="text" class="form-control" name="trip_date_akhir" id="trip_date_akhir" value="{{ $trip_date_akhir }}">
						<div class="col-md-6"></div>
					</div>

					<div class="form-group">
						<label>Nama Customer</label>
						<input type="text" class="form-control" readonly name="customerName" id="customerName" value="{{ $customerName }}">
						<div class="col-md-6"></div>
					</div>

					<div class="form-group">
						<label>Email Customer</label>
						<input type="text" class="form-control" readonly name="customerEmail" id="customerEmail" value="{{ $customerEmail }}">
						<div class="col-md-6"></div>
					</div>

					<div class="form-group">
						<label>Telp Customer</label>
						<input type="text" class="form-control" readonly name="customerTelp" id="customerTelp" value="{{ $customerTelp }}">
						<div class="col-md-6"></div>
					</div>

					<div class="form-group">
						<label>Tempat Asal</label>
                        <input type="text" class="form-control" readonly name="origin" value="{{$origin}}"></input>
						<div class="col-md-6"></div>
					</div>

                    <div class="form-group">
						<label>Tempat Tujuan</label>
						<input type="text" class="form-control" readonly name="destination" value="{{ $destination }}">
						<div class="col-md-6"></div>
					</div>

                    

					<div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
						<label for="no_ruas_pangkal">Status : {{$status}}</label>
                    </div>
                    
                    <table class="table">
                        <tr>
                            <td>Total Harga</td>
                        </tr>
                        <tr>
                        <td><h3>Rp. {{number_format($total,2,",",".")}}</h3></td>
                        </tr>
                    </table>
					
					
						
					<div class="form-group">
						<button type="submit" class="btn btn-primary">
							Simpan
						</button>
					</div>
				</div>
			</div>
        </div>
        <div class="col-md-4">
            <div class="{{$div_box}}">
                <div class="box-header">
                    <h4 class="header-title">Driver Tersedia</h4>
                </div>
                <div class="card card-body">
                    <input type="hidden" id="duration" name="duration" value="{{$sewaDetail->duration}}"/>
					<input type="hidden" id="distance" name="distance" value="{{$sewaDetail->distance}}"/>
					<input type="hidden" name="sewa_type" value="{{$sewaDetail->sewa_type}}"/>
					
						<div class="form-group">
                                <label for="driver">Driver</label>
                                <input type="hidden" id="driverId" name="driverId" value="{{ $driverId }}">
                                <select name="driver" class="select2 driver form-control" id="driver">
                                    <option value="0">----</option>
									@foreach($driver as $k => $v)
                                    	<option value="{{$v->id}}" selected="selected">{{$v->name}}</option>
                                    @endforeach
                                </select>
                        </div>

						<div class="form-group">
							<label for="driverName">Driver</label>
							<h5 class="driverName">--</h5>
						</div>
						<div class="form-group">
							<label for="noTelp">No Telp</label>
							<h5 class="noTelp">--</h5>
						</div>
						

						<div class="form-group">
							<label for="noPlat">No Plat</label>
							<h5 class="noPlat">--</h5>
						</div>

						<div class="form-group">
							<label for="tahunMobil">Tahun</label>
							<h5 class="tahunMobil">--</h5>
						</div>

						<div class="form-group">
							<label for="warnaMobil">Warna</label>
							<h5 class="warnaMobil">--</h5>
						</div>

						<img class="fotoDriver profile-user-img img-responsive img-circle" src="http://placehold.it/160" alt="User profile picture">
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
@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{ url('/plugins/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
        
        loadData($('input[name="driverId"]').val());
		$('select#driver').on('change',function(){
			loadData($(this).val());
		});
        function loadData(id) { 
			$.ajax({
				url:`${Laravel.serverUrl}/backend/driver/${id}`,
				method: 'get'
			}).done(function(response){
                response = response.data;
                console.log(response.name)
                
				$('.warnaMobil').text(response.warna);
				$('.noPlat').text(response.no_plat);
				$('.tahunMobil').text(response.tahun);
				$('.driverName').html(response.name);
				$('.noTelp').text(response.no_telepon);
				$('.fotoDriver').attr('src',response.foto);
				var distanceInKM = Math.round($('#distance').val()* 0.001);
				//$('#total_bayar').val(Math.round(distanceInKM * response.mobil.harga));
				$("#status").val('confirmed');
				$('#driverId').val(response.id);
			});
		}
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

