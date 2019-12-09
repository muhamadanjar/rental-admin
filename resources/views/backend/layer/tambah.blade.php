@extends('templates.adminlte.main')

@section('content-admin')
  <?php
  $id = '';
  $namalayer = '';
  $kodelayer = '';
  $urllayer = '';
  $aktif = '';
  $urutanlayer = '';
  $tipelayer = '';
  $option_opacity = '';
  $option_visible = '';
  $option_style = '';
  $x_min = '';
  $y_min = '';
  $x_max = '';
  $y_max = '';
  $parent_id = '';
  $jsonfield = '';
  if (session('aksi') == 'edit') {
    $id = $layer->id;
    $namalayer = $layer->namalayer;
    $kodelayer = $layer->kodelayer;
    $urllayer = $layer->urllayer;
    $aktif = $layer->aktif;
    $urutanlayer = $layer->urutanlayer;
    $tipelayer = $layer->tipelayer;
    $option_opacity = $layer->option_opacity;
    $option_visible = $layer->option_visible;
    $option_style = $layer->option_style;
    $parent_id = $layer->parent_id;
    $x_min = $layer->x_min;
    $y_min = $layer->y_min;
    $x_max = $layer->x_max;
    $y_max = $layer->y_max;
    $jsonfield = $layer->jsonfield;
  }
  ?>
<form role="form" method="post" action="{{ route('backend.layer.post')}}" enctype='multipart/form-data'>
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-9">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"> Layer</h3>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" class="form-control" id="id" value="{{$id}}">
                <div class="form-group">
                  <label for="namalayer">Nama Layer/Group</label>
                  <input type="text" name="namalayer" class="form-control" id="namalayer" value="{{$namalayer}}">
                </div>
                <div class="form-group">
                  <label for="kodelayer">Kodelayer</label>
                  <input type="text" name="kodelayer" class="form-control" id="kodelayer" value="{{$kodelayer}}">
                </div>
                <div class="form-group">
                  <label for="urllayer">Url Layer</label>
                  <div class="input-group">
                    <input type="text" name="urllayer" class="form-control" id="urllayer" value="{{$urllayer}}">
                    <span class="input-group-btn">
                              <button id="btn-load-geoserver" type="button" class="btn btn-default">Load Data</button>
                    </span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="aktif">Aktif</label>
                  <input type="checkbox" name="aktif" id="aktif" value="1" @if($aktif === true) checked @endif>
                </div>
                <div class="form-group">
                  <label for="urutanlayer">Urutan Layer</label>
                  <input type="text" name="urutanlayer" class="form-control" id="urutanlayer" value="{{$urutanlayer}}">
                </div>
                <div class="form-group">
                  <label for="tipelayer">Tipe Layer</label>
                  <select name="tipelayer" id="tipelayer" class="form-control">
                    <option value="esri" @if($tipelayer == 'esri') selected @endif>Esri</option>
                    <option value="esrigroup" @if($tipelayer == 'esrigroup') selected @endif>Esri Group</option>
                    <option value="ol" @if($tipelayer == 'ol') selected @endif>Ol</option>
                    <option value="olgroup" @if($tipelayer == 'olgroup') selected @endif>OL Group</option>
                    <option value="googlegroup" @if($tipelayer == 'googlegroup') selected @endif>Google Group</option>
                    <option value="googlegeojson" @if($tipelayer == 'googlegeojson') selected @endif>googlegeojson</option>
                    <option value="geojson" @if($tipelayer == 'geojson') selected @endif>GeoJSON</option>
                    <option value="olimage" @if($tipelayer == 'olimage') selected @endif>OL Image</option>
                    
                  </select>
                </div>
                <input name="jsonfield" id="jsonfield" type="hidden" class="form-control" value="{{ $jsonfield }}" />
                <code class="jsonfield_code"></code>
                
            </div>

            <div class="panel-footer">
                <button type="submit" class="btn btn-primary btn-flat">Simpan</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title"> Layer</h3>
        </div>
        <div class="card-body">
                <div class="form-group">
                  <label for="parent_id">Group</label>
                  <select name="parent_id" id="parent_id" class="form-control">
                    <option value="0">-------</option>
                    @foreach($groups as $k => $g)
                    <option value="{{$g->id}}" @if($parent_id == $g->id) selected @endif>{{$g->namalayer}}</option>
                    @endforeach
                  </select>
                  
                </div>

                <div class="form-group">
                  <label for="option_visible">Layer Muncul di peta</label>
                  <input type="checkbox" name="option_visible" id="option_visible" value="1" @if($option_visible === true) checked @endif>
                </div>

                <div class="form-group">
                  <label for="option_style">Style</label>
                  <input type="text" class="form-control" name="option_style" id="option_style" value="{{$option_style}}">
                </div>

                <div class="form-group">
                  <label for="option_opacity">Opacity</label>
                  <input type="range" name="option_opacity" class="form-control" id="option_opacity" value="{{$option_opacity}}" min='0' max='1' step='0.01'>
                </div>

                <div class="form-group">
                    <label for="role" >Role</label>
                    @forelse ($role as $role)
                    <div class="checkbox">
                        <label>
                            @if(session('aksi') == 'edit')
                            <input type="checkbox" @if($layer->hasRole($role->name)) checked @endif class="flat-red" name="role[]" value="{{ $role->id }}"/> {{ $role->name }} 
                            @else
                            <input type="checkbox" class="flat-red" name="role[]" value="{{ $role->id }}"/> {{ $role->name }}
                            @endif
                        </label>
                    </div>        
                    @empty
                        <p>No Roles</p>
                    @endforelse
                </div>

                <div class="form-group">
                  <label for="x_min">X Min</label>
                  <input type="text" name="x_min" class="form-control numberonly" id="x_min" value="{{$x_min}}">
                </div>
                <div class="form-group">
                  <label for="y_min">Y Min</label>
                  <input type="text" name="y_min" class="form-control numberonly" id="y_min" value="{{$y_min}}">
                </div>
                <div class="form-group">
                  <label for="x_max">X Max</label>
                  <input type="text" name="x_max" class="form-control numberonly" id="x_max" value="{{$x_max}}">
                </div>
                <div class="form-group">
                  <label for="x_max">Y Max</label>
                  <input type="text" name="x_max" class="form-control numberonly" id="x_max" value="{{$x_max}}">
                </div>
        </div>
      </div>
    </div>
  </div>
        
</form>
@endsection
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('assets/plugins/selectize/css/selectize.css')}}">
<link rel="stylesheet" href="{{ url('assets/plugins/jquery-ui/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.css')}}">
<link rel="stylesheet" href="{{ url('assets/plugins/touchspin/css/touchspin.css')}}">
@endsection
@section('script-end')
@parent
        
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
<script type="text/javascript" src="{{ asset('3.19compact/init.js')}}"></script>
<!-- <script src="https://js.arcgis.com/3.19compact/"></script> -->
<script type="text/javascript" src="{{ asset('js/esriGetFields.js') }}"></script>
@endsection
