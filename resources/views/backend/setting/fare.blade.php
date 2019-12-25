@extends('templates.adminlte.main')
@section('content-admin')
    
<div class="row">
	<div class="col-md-12">
		<div class="card card-secondary">
			<div class="card-header">
				<h3 class="card-title">Pengaturan Harga</h3>
			</div>
			<div class="card-body">
				<div>
					<ul class="nav nav-pills custom">
						@foreach ($type as $k => $item)
							<li class="nav-item {{ $k == 0 ? 'active':' '}}"><a href="#{{str_slug($item->type)}}" data-toggle="tab" class="nav-link text-uppercase"> {{$item->type}} <i class="fa"></i></a></li>
						@endforeach
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="tab-content card-body">
							@foreach ($type as $k => $item)			
							<div class="tab-pane  {{ $k == 0 ? 'active':' '}}" id="{{str_slug($item->type)}}">
								<form method="POST" action="{{ route('backend.setting.fare') }}">
									{{ csrf_field() }}
									<input name="idtype" value="{{$item->id}}" type="hidden">
									<div class="row">
										<div class="form-group col-md-3">
											<label for="min_km" class="form-label">Minimum (Km)</label>
											<div class="input-group mb-3">
												<div class="input-group-prepend">
												<span class="input-group-text">IDR</span></div>
												<input class="form-control" required="" name="min_km" type="number" value="{{$item->min_km}}">
											</div>
										</div>

										<div class="form-group col-md-3">
											<label for="min_rp" class="form-label">Minimum (Rp)</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
												<span class="input-group-text">Km</span></div>
												<input class="form-control" required="" name="min_rp" type="number" value="{{$item->min_rp}}">
											</div>
										</div>

										<div class="form-group col-md-3">
											<label for="hatchback_base_time" class="form-label">Setelah Minimum (Km)</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
												<span class="input-group-text">IDR</span></div>

												<input class="form-control" required="" name="after_min_km" type="number" value="{{$item->after_min_km}}">
											</div>
										</div>

										<div class="form-group col-md-3">
											<label for="hatchback_std_fare" class="form-label">Harga Setelah Minimum (Rp)</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
												<span class="input-group-text">IDR</span></div>
												<input class="form-control" required="" name="after_min_rp" type="number" value="{{$item->after_min_rp}}">
											</div>
										</div>

										

										
									</div>
									<div class="row">
										<div class="form-group col-md-3">
											<label for="hatchback_weekend_base_fare" class="form-label">Komisi Persentase</label>
											<div class="input-group mb-3">
												<div class="input-group-prepend">
												<span class="input-group-text">IDR</span></div>
												<input class="form-control" required="" name="com" type="number" value="{{$item->com}}">
											</div>
										</div>
									</div>

									<div class="card-footer">
										<div class="col-md-2">
											<div class="form-group">
												<input type="submit" class="form-control btn btn-success" value="Save">
											</div>
										</div>
									</div>
								</form>
							</div>
							@endforeach
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
      
@endsection