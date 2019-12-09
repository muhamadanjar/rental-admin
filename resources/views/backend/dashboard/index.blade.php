
@extends('templates.adminlte.main')
@section('title','Dashboard')
@section('breadcrumb')

@endsection
@section('body-class','skin-blue sidebar-mini fixed sidebar-mini-expand-feature')
@section('content-admin')

    <!-- Info boxes -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Costumer</span>
            <span class="info-box-number">{{$totalcustomer}}<small></small></span>
          </div>
        </div>
      
      
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Driver</span>
              <span class="info-box-number">{{$totaldriver}}<small></small></span>
            </div>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Pemesanan</span>
              <span class="info-box-number">{{$totalpemesanan}}<small></small></span>
            </div>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-bar-chart"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pengunjung</span>
            <span class="info-box-number">{{$totalpengunjung}}<small></small></span>
          </div>
      </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Peta Driver</h3>
          </div>
          <div class="card-body">
            <div id="map" class="map"></div>
          </div>
          <div class="card-footer">

          </div>
        </div>
      </div>
      
      <div class="col-md-4">
          <div class="card">
            <div class="card-header border-transparent">
              <h3 class="card-title">Latest Orders</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Nominal</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($orderList as $k => $v)
                      <tr>
                          <td><a href="#">{{$v->order_code}}</a></td>
                          <td>
                            @isset($v->driver)
                              {{$v->driver->name}}
                            @endisset
                          </td>
                          <td><span class="badge badge-success">{{$v->order_status}}</span></td>
                          <td><span class="badge badge-info">{{$v->order_nominal}}</span></td>
                      </tr>
                      @endforeach
                 
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
            </div>
            <!-- /.card-footer -->
          </div>
          </div>

      
    </div>
    
    
    

@endsection
@section('content')
@parent
<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="modaldetailasset" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="gridasset"></div>
        </div>
        <div class="modal-footer">
            {{-- <a href="{{ url('frontend.map')}}" class="btn btn-default"><span>Lihat Peta</span></a> --}}
        </div>
        </div>
    </div>
    </div>
</div>

    <div class="modal fade" id="modaldetailjenis" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <div id="gridjenis"></div>
                </div>
                <div class="modal-footer">
                {{-- <a href="{{ route('frontend.map') }}" class="btn btn-default"><span>Lihat Peta</span></a> --}}
                </div>
            </div>
        </div>
      </div>
@endsection
@section('style-head')
@parent
<link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/ol-popup@4.0.0/src/ol-popup.css" />
<!-- plugin dx -->
<link href="{{ asset('plugins/dx/css/dx.common.css')}}" rel="stylesheet">
<link href="{{ asset('plugins/dx/css/dx.greenmist.css')}}" rel="stylesheet">
<link href="{{ asset('plugins/dx/css/dx.light.css')}}" rel="stylesheet">
<link href="{{ asset('plugins/dx/css/dx.spa.css')}}" rel="stylesheet">


@endsection
@section('script-end')
    @parent
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>
    <script src="https://unpkg.com/ol-popup@4.0.0"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
    let worker = null;
		let vectorSource =null;
		let vectorLayer = null;
		$(function () {
			worker = new Worker(`${Laravel.serverUrl}/js/webworker.js`);
			worker.addEventListener('error', function(a) {
					// console.log(a);
					console.error('Error: Line ' + a.lineno + ' in ' + a.filename + ': ' + a.message);
			}, false);

			worker.addEventListener('message', function(a) {
				if (a.data.cmd === 'resLastPosition') { resLastPosition(a.data.val); }
			});
			vectorSource = new ol.source.Vector();
      vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            id:'layer_vector'
      });
			var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
					}),
					vectorLayer
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([98.669689, 3.590003]),
          zoom: 12
        })
			});

			var popup = new Popup();
			map.addOverlay(popup);

			map.on('click', function(event) {
				var coordinate = event.coordinate;
				var pixel = map.getPixelFromCoordinate(coordinate);
				map.forEachFeatureAtPixel(pixel, function(f) {
					var geometry = f.getGeometry();
					var coord = geometry.getCoordinates();
					console.log(f);
					content = `<br>${f.get('name')}`;
					content += `<br>${f.get('mobil').no_plat}`;
          // el.innerHTML += feature.get('name') + '<br>';
					popup.show(coord, content);
        });
				
				
				
			});
			function resLastPosition(a) {
					a.map((v,i)=>{
            let meta = v.meta;
            let userData = v.users;
            let mobilData = v.mobil;
            meta.filter(function(word) {
              if(word.meta_key == 'LOCATION'){
                let split = word.meta_value.split(',');
                var transform = ol.proj.getTransform('EPSG:4326', 'EPSG:3857');
                let coordinate = transform([parseFloat(split[1]), parseFloat(split[0])]);
                
                
                var mapFeature = {
                  geometry: new ol.geom.Point(coordinate),
                  name: userData.name
                };
                if (v.hasOwnProperty('mobil')) {
                  mapFeature.mobil = mobilData[0];
                }
                console.log(userData);
                
                var feature = new ol.Feature(mapFeature);
                var iconStyle = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.2, 32],
                        
                        scale: 0.3,
                        anchorXUnits: 'fraction',
                        anchorYUnits: 'pixels',
                        src: `${Laravel.serverUrl}/img/carMarker.png`
                    }))
                });
                feature.setStyle(iconStyle);
                vectorSource.addFeature(feature);
              }
              
            })
						
					});
				
      }
      worker.postMessage({ cmd: 'reqLastPosition', val: `${Laravel.serverUrl}/api/user/location`});
    });

    
    </script>

    <script type="text/javascript" src="{{ asset('js/rm.js') }}"></script>
    
@endsection