@extends('templates.adminlte.main')
@section('content-admin')
<form action="{{route('admin-booking-view',[$book->order_id])}}" method="POST">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Peta</h3>
        </div>
        <div class="card-body">
            <div id="map" class="map"></div>
            
            <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Awal</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                 
                  <tr>
                    <td></td>
                    <td>{{$book->order_address_origin}}</td>
                    <td>{{$book->order_address_destination}}</td>
                    <td><span class="badge bg-success">{{$book->status}}</span></td>
                  </tr>
                </tbody>
              </table>
            
        </div>
        <div class="card-footer">
            <div class="form-group">
                <label>Assigned For</label>
                <select id="assigned" class="form-control select2"  style="width: 100%;" name="assigned_for">
                    <option value='0'>Select Driver</option>
                    @foreach ($drivers as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save Details " name="Save" id="pointedit">
            </div>
        </div>
    </div>
</form>
@endsection

@section('style-head')
@parent
    <link rel="stylesheet" href="https://openlayers.org/en/v5.3.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/ol-popup@4.0.0/src/ol-popup.css" />	
@endsection

@section('style-theme')
@parent
    <style>
        #map{
            height: 400px;
        }
    </style>
@endsection

@section('script-end')
    @parent
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>
    <script type="text/javascript">
        var originLat = '{{$book->order_address_origin_lat}}';
        var originLng = '{{$book->order_address_origin_lng}}';
        var destinationLat = '{{$book->order_address_destination_lat}}';
        var destinationLng = '{{$book->order_address_destination_lng}}';
        var map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                }), 
            ],
            view: new ol.View({
            center: ol.proj.fromLonLat([98.669689, 3.590003]),
            zoom: 12
            })
        });

        var markerOrigin = new ol.Feature({
            id: 'origin', 
            geometry: new ol.geom.Point(
                ol.proj.fromLonLat([parseFloat(originLng),parseFloat(originLat)])
            ),
        });
        var markerDestination = new ol.Feature({
            id: 'origin', 
            geometry: new ol.geom.Point(
                ol.proj.fromLonLat([parseFloat(destinationLng),parseFloat(destinationLat)])
            ),
        });

        var iconStyle = new ol.style.Style({
            image: new ol.style.Icon(({
                anchor: [0.2, 32],
                anchorXUnits: 'fraction',
                anchorYUnits: 'pixels',
                src: 'http://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png',
                scale: 0.7
            }))
        });
        markerOrigin.setStyle(iconStyle);
        markerDestination.setStyle(iconStyle);
        var vectorSource = new ol.source.Vector({
            features: [markerOrigin,markerDestination]
        });
        markerVectorLayer = new ol.layer.Vector({
            source: vectorSource,
        });
        map.addLayer(markerVectorLayer);
    </script>
@endsection