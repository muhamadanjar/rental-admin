@extends('layouts.full')
@section('head_title')
  - Map
@endsection

@section('content')

<section id="header" role="header">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
      <img src="{{ url('/img/logo.png')}}" width="30" height="30" class="d-inline-block align-top" alt="">
      <span class="appName">{{ config('app.name') }}</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
      </ul>
      <!-- <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cari</button>
      </form> -->
      <ul class="nav justify-content-end">
        @if(Auth::user())
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ auth()->user()->name}}
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a href="{{ url('/backend') }}" class="dropdown-item"><i class="fa fa-dashboard"></i> Dashboard</a>
            <a href="{{ route('logout') }}" class="dropdown-item"><i class="fa fa-sign-out"></i> Logout</a>

          </div>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">Login</a>
        </li>
        @endif
      </ul>

    </div>
  </nav>
</section>
<section id="main" role="main">
  <div class="main-map">
    <div class="ui-layout-center map" id="map">
        <div id="popup" class="ol-popup slimcroll" style="width:300px">
          <a href="#" id="popup-closer" class="ol-popup-closer">
            <button type="button" class="btn btn-sm btn-primary"><i class="fa fa-close"></i></button>
          </a>
          <div id="popup-content"></div>
          <div id="street-view"></div>
        </div>
    </div>
    <div class="ui-layout-west">
      <div class="row">
        <div class="col-sm-12">
          <div class="panel-group panel-group-compact" id="accordion2">
              <div class="panel panel-default layer-panel">
                  <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#layercontrol" class="collapsed">
                            <span class="fa fa-plus"></span> Data Lapisan
                        </a>
                      </h4>
                  </div>
                  <div id="layercontrol" class="panel-collapse collapse">
                      <div class="panel-body">
                      </div>
                  </div>
              </div>
              <div class="panel panel-default info-panel">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#infoTool" class="collapsed">
                          <span class="fa fa-plus"></span> Informasi
                        </a>
                    </h4>
                </div>
                <div id="infoTool" class="panel-collapse collapse">
                  <div class="panel-body">
                    <form class="form-info" id="form-info" role="info">
                      <div class="form-group">
                        <button type="button" class="btn btn-primary btn-xs btn-infolayer" id="btn-infolayer">Info</button>
                      </div>
                      <div class="form-group">
                        <select name="identify" id="identify" class="form-control">
                          <option value="all">All</option>
                        </select>
                      </div>

                    </form>
                    <div class="input-group">
                        <input type="text" name="query-text" id="query-text" class="form-control">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-default btn-reset" id="btn-reset"><i class="fa fa-trash"></i></button>
                          <button type="button" class="btn btn-primary btn-search" id="btn-search"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                    <!-- <select name="provinsi" id="provinsi" class="form-control">
                    </select>
                    <select name="kabkota" id="kabkota" class="form-control">
                    </select> -->
                    <label>Kecamatan </label>
                    <select name="kecamatan" id="kecamatan" class="form-control">
                    </select>
                    <label>Kelurahan </label>
                    <select name="desa" id="desa" class="form-control">
                    </select>
                    <label>Jenis </label>
                    <select name="jenis" id="jenis" class="form-control">
                    </select>
                    <label>Objek </label>
                    <select name="objek" id="objek" class="form-control">
                    </select>
                    
                  </div>
                </div>
              </div>
              <div class="panel panel-default measure-panel">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#measureTool" class="collapsed">
                          <span class="fa fa-plus mr5"></span> Alat Ukur
                        </a>
                    </h4>
                </div>
                <div id="measureTool" class="panel-collapse collapse">
                  <div class="panel-body">
                    <form class="">
                      <div class="form-group">
                        <div class="btn btn-group">
                          <button type="button" class="btn btn-primary btn-xs btn-measure" id="btn-measure">
                          Measure</button>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="type">Measurement type &nbsp;</label>
                        <select id="type" class="form-control">
                          <option value="length">Length (LineString)</option>
                          <option value="area">Area (Polygon)</option>
                        </select>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!--<div class="panel panel-default query-panel">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#queryTool" class="collapsed">
                          <span class="fa fa-plus mr5"></span> Query
                        </a>
                    </h4>
                </div>
                 <div id="queryTool" class="panel-collapse collapse">
                  <div class="panel-body">
                    
                    <div id="dialog-disclaimer" title="&nbsp;">
                      Sistem Informasi Spasial Aset Daerah (SISADA) menunjukkan posisi relatif Aset Daerah Kota Bogor. Apabila terdapat perbedaan letak, batas, bentuk dan luas  agar melakukan verifikasi ke Badan Pengelola Keuangan Aset Daerah.
                          Peta dan informasi yang termuat dalam halaman ini tidak boleh disebarluaskan dalam format lain baik dalam bentuk berkas digital maupun cetakan.
                          Kami tidak bertanggungjawab terhadap segala bentuk penyalahgunaan informasi yang diambil dari halaman ini.
                          Kami akan senantiasa meningkatkan akurasi data yang disajikan pada peta ini. Saran dan masukan serta kelengkapan informasi dalam peta ini sangat kami hargai.
                          Saya telah membaca syarat dan ketentuan.
                    </div>
                    <form class="form" id="form-printpeta">
                      <label>Ukuran </label>
                      <select id="format" class="form-control">
                        <option value="a0">A0 (slow)</option>
                        <option value="a1">A1</option>
                        <option value="a2">A2</option>
                        <option value="a3">A3</option>
                        <option value="a4" selected>A4</option>
                        <option value="a5">A5 (fast)</option>
                      </select>
                      <label>Resolusi </label>
                      <select id="resolution" class="form-control">
                        <option value="72">72 dpi (fast)</option>
                        <option value="150">150 dpi</option>
                        <option value="300">300 dpi (slow)</option>
                      </select>
                    </form>
                    <button id="export-pdf" class="btn btn-secondary">Ekspor PDF</button>
                    <div id="dialog" title="Basic dialog">
                      <div id="street-view"></div>
                    </div>
                    <div id="dialog_legenda" title="Basic dialog">
                    </div>
                    <div id="dialog_query" title="Query">
                    </div>
                    <button id="btnSv" class="btn btn-secondary">Lihat Street View</button>
                    <div id="gallery"></div>

                  </div>
                </div> 
              </div>-->

          </div>
        </div>
      </div>

      <div class="">
          <div id="scale"></div>
          <div id="location"></div>
          <div id="nodelist"></div>
      </div>

    </div>
  </div>
</section>
@include('layouts.elements.modal')
@endsection
@section('style-head')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
    <!-- Application stylesheet : mandatory -->
     <!--<link rel="stylesheet" href="{{ url('css/app.css')}}"> -->

    <link rel="stylesheet" href="{{ url('css/main_op.css')}}">
    <!--/ Application stylesheet -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/plugins/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/plugins/Ionicons/css/ionicons.min.css')}}">

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <link type="text/css" rel="stylesheet" href="{{ url('/plugins/layout/layout-default-latest.css')}}" />
    <link rel="stylesheet" href="{{ url('components/jquery-ui/themes/base/all.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('css/uielement.css')}}" type="text/css">

    <link rel="stylesheet" href="{{ url('plugins/dx/css/dx.common.css')}}" type="text/css">
    {{-- <link rel="stylesheet" href="{{ url('plugins/dx/css/dx.spa.css')}}" type="text/css"> --}}
    
    <link href="https://cdn.jsdelivr.net/npm/ol-contextmenu@latest/dist/ol-contextmenu.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/ol-geocoder@latest/dist/ol-geocoder.min.css" rel="stylesheet">

    <style>
      button.layer-control {
          background-color: #eee;
          color: #444;
          cursor: pointer;
          padding: 8px;
          width: 100%;
          border: none;
          text-align: left;
          outline: none;
          font-size: 14px;
          transition: 0.4s;
      }
      button.layer-control.active, button.layer-control:hover {
          background-color: #ccc;
      }

      button.layer-control:after {
          content: '\002B';
          color: #777;
          font-weight: bold;
          float: right;
          margin-left: 5px;
      }

      button.layer-control.active:after {
          content: "\2212";
      }

      div.layer-control-panel {
          padding: 0 10px;
          background-color: white;
          max-height: 0;
          overflow: hidden;
          overflow-y: scroll;
          transition: max-height 0.2s ease-out;
      }

      .multiview-item {
          margin:25px;
          -webkit-touch-callout: none;
          -webkit-user-select: none;
          -khtml-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
          border-top: 1px solid lightgray;
          border-bottom: 1px solid lightgray;
          padding: 20px 0 30px;
      }
    </style>
@endsection
@section('style-theme')
    <style>
      #layertree li > span {
        cursor: pointer;
      }
      #street-view {
        height: 100%;
      }
      .no-padding {
        padding: 0 !important;
      }

    </style>
    <link rel="stylesheet" href="{{ url('css/popup.css')}}">
    <link rel="stylesheet" href="{{ url('css/overview.css')}}">
    <style>

    </style>
    <style>
      .tooltip {
        position: relative;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 4px;
        color: white;
        padding: 4px 8px;
        opacity: 0.7;
        white-space: nowrap;
      }
      .tooltip-measure {
        opacity: 1;
        font-weight: bold;
      }
      .tooltip-static {
        background-color: #ffcc33;
        color: black;
        border: 1px solid white;
      }
      .tooltip-measure:before,
      .tooltip-static:before {
        border-top: 6px solid rgba(0, 0, 0, 0.5);
        border-right: 6px solid transparent;
        border-left: 6px solid transparent;
        content: "";
        position: absolute;
        bottom: -6px;
        margin-left: -7px;
        left: 50%;
      }
      .tooltip-static:before {
        border-top-color: #ffcc33;
      }
    </style>
    <style>
        .box{
          width: 500px;
          margin: auto;
          margin-top: 50px;
        }
        .ui-autocomplete {
          position: absolute;
          z-index: 1000;
          cursor: default;
          padding: 0;
          margin-top: 2px;
          list-style: none;
          background-color: #ffffff;
          border: 1px solid #ccc;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
          border-radius: 5px;
          -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }
            .ui-autocomplete > li {
                padding: 3px 10px;
            }
            .ui-autocomplete > li.ui-state-focus {
                background-color: #3399FF;
                color:#ffffff;
            }
            .ui-helper-hidden-accessible {
                display: none;
            }
    </style>
@endsection
@section('script-head')
    @parent
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ol-contextmenu"></script>
    <script src="https://cdn.jsdelivr.net/npm/ol-geocoder"></script>
@endsection
@section('script-body')
@endsection
@section('script-end')
@parent


<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- <script src="{{ url('js/app.js')}}"></script> -->

<!-- nprogress -->
<script src="{{ asset('/plugins/nprogress/nprogress.js')}}"></script>
<script src="{{ asset('/plugins/dx/js/dx.all.js')}}"></script>

<script type="text/javascript" src="{{ url('/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
<script type="text/javascript" src="{{ url('plugins/layout/jquery.layout-latest.js')}}"></script>
<script type="text/javascript" src="{{ url('js/op/basemap.js')}}"></script>
<script type="text/javascript" src="{{ url('js/op/measure.js')}}"></script>
<script type="text/javascript" src="{{ url('plugins/handlebar/handlebars.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1j4LJ2c2RhsqEiA9aWYQoJdoij9QyCYw" async defer></script>

@include('layouts.elements.handlebar')
<!-- Plugins and page level script : optional -->
<script src="{{ asset('js/peta.op.js')}}"></script>
<script src="{{ asset('js/wilayah.js')}}"></script>
<script type="text/javascript">
  $.extend({
    getValues: function(url) {
      var result = null;
      $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        async: false,
        success: function(data) {
          result = data;
        }
      });
      return result;
    }
  });
	$(function() {
    $("div.main-map").css("height", $(window).height() - ($(window).height() * 0.1)+'px');
    $(window).resize(function(){
      $("div.main-map").css("height", $(window).height() - ($(window).height() * 0.1)+'px');
    });
		$('.main-map').layout({
		  west__size:300,
      west__initClosed:true,
      west__spacing_closed: 10,

		  stateManagement__enabled:	false,
      onresize: function () {
        map.updateSize();
      },
    }).sizePane("west", 300);
    initMap();
    createGroupLayer();
    overlaysOBJ = $.getValues("/map/getdata");

    objLayer(overlaysOBJ);
    initPopup();

    //initializeTree();
    identifyLayer();

    map.getLayers().forEach(function(layer, i) {
      bindInputs('#layer-base'+i, layer);
      if (layer instanceof ol.layer.Group) {
        layer.getLayers().forEach(function(sublayer, j) {
          //console.log(sublayer.get('id'));
          bindInputs('#layer-'+i+'-'+sublayer.get('id').split(':')[1], sublayer);
        });
      }
    });
    map.getView().on('change:resolution', function(evt) {
      var resolution = evt.target.get('resolution');
      var units = map.getView().getProjection().getUnits();
      var dpi = 25.4 / 0.28;
      var mpu = ol.proj.METERS_PER_UNIT[units];
      var scale = resolution * mpu * 39.37 * dpi;
      if (scale >= 9500 && scale <= 950000) {
          scale = Math.round(scale / 1000) + "K";
      } else if (scale >= 950000) {
          scale = Math.round(scale / 1000000) + "M";
      } else {
          scale = Math.round(scale);
      }
      document.getElementById('scale').innerHTML = "Scale = 1 : " + scale;
    });
    var contextmenu = new ContextMenu();
    map.addControl(contextmenu);
    var add_later = [
      '-', // this is a separator
      {
        text: 'Tambah Marker',
        icon: 'images/marker.png',
        //callback: marker
      },
      {
        text: 'Street View',
        icon: 'images/streetview-icon.png',
        callback: view_streetview
      }
    ];
    contextmenu.extend(add_later);

    $('#btn-infolayer').click(function(){
      console.log(measureTools.helpTooltipElement);
      measureTools.helpTooltipElement  = null;
      changeInfoMap('info');
      identifyLayer();
      map.removeInteraction(measureTools.draw);
    });

    $('#btn-measure').click(function(){
      map.removeInteraction(measureTools.draw);
      changeInfoMap('measure');
      if(vectorSource.getFeatures().length > 0){
        vectorSource.clear();
      }
      initMeasure();

      map.un('click',identifyLayerEvent);
    });

    $("#query-text").autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "/api/jenis",
          dataType: "jsonn",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
        log( "Selected: " + ui.item.value + " aka " + ui.item.id );
      }
    });

    $("#btn-search").click(function(e){
      updateFilter();
    });

    $("#btn-reset").click(function(e){
      resetFilter();
    });
    loadJenis();
    $('#jenis').on('change',function(e){
      loadObjek($(this).find(":selected").val());
    });
    
    loadKecamatan(3271);
    kecamatan.on('change',function(e){
      // console.log($(this).find(":selected").val());
      loadDesa($(this).find(":selected").val());
      var id_kecamatan = $(this).find(":selected").val();
      loaddatawilayah('/api/selectkecamatan/'+id_kecamatan).then(function(data){
          map.getView().fit(ol.proj.transformExtent([data.x_min,data.y_min,data.x_max,data.y_max],'EPSG:4326','EPSG:3857'), map.getSize());
      });

    });
    $("#dialog").dialog({
        autoOpen: false,
        show: {
          effect: "blind",
          duration: 1000
        },
        hide: {
          effect: "explode",
          duration: 1000
        }
    });
    $( "#dialog-disclaimer" ).dialog({
    			height: 370,
    			width: 600,
          autoOpen:false,
    			modal: false,
    			buttons: {
    				"OK": function() {
    					if ( $('#agreement').val('1') ) {
    					    $( "#dialog-disclaimer" ).dialog('close');
                            //document.location="setdisclaimer";
    					}else {
    						return false;
    					}
    				}
    			},
          resize: function( event, ui ) {
              $(this).dialog( "option", "width", ui.size.width );
              $(this).dialog( "option", "height", ui.size.height );
          },
    }).position({my: 'bottom',at: 'center',});
    $("#dialog_legenda").dialog({
      autoOpen:false,
      width:500
    }).position({my: 'bottom',at: 'center',});
    $("#btnSv").click(function() {
      // $("#dialog").dialog("open");
      $('#formModalMap').find('.modal-title').html('Street View');
      $('#formModalMap').find('.modal-body').html($('#street-view').css({
        'height':'400px'
      })
      );
      $('#formModalMap').modal();
      initializePanorama();
    });
    var dims = {
        a0: [1189, 841],
        a1: [841, 594],
        a2: [594, 420],
        a3: [420, 297],
        a4: [297, 210],
        a5: [210, 148]
      };
    var loading = 0;
    var loaded = 0;
    var exportButton = document.getElementById('export-pdf');
    exportButton.addEventListener('click', function() {
        exportButton.disabled = true;
        document.body.style.cursor = 'progress';
        var format = document.getElementById('format').value;
        var resolution = document.getElementById('resolution').value;
        var dim = dims[format];
        var width = Math.round(dim[0] * resolution / 25.4);
        var height = Math.round(dim[1] * resolution / 25.4);
        var size = /** @type {ol.Size} */ (map.getSize());
        var extent = map.getView().calculateExtent(size);

        var source = basemapTile.getSource();

        var tileLoadStart = function() {
          ++loading;
        };

        var tileLoadEnd = function() {
          ++loaded;
          if (loading === loaded) {
            var canvas = this;
            window.setTimeout(function() {
              loading = 0;
              loaded = 0;
              var data = canvas.toDataURL('image/png');
              var pdf = new jsPDF('landscape', undefined, format);
              pdf.addImage(data, 'JPEG', 0, 0, dim[0], dim[1]);
              pdf.save('map.pdf');
              source.un('tileloadstart', tileLoadStart);
              source.un('tileloadend', tileLoadEnd, canvas);
              source.un('tileloaderror', tileLoadEnd, canvas);
              map.setSize(size);
              map.getView().fit(extent);
              map.renderSync();
              exportButton.disabled = false;
              document.body.style.cursor = 'auto';
            }, 100);
          }
        };

        map.once('postcompose', function(event) {
          source.on('tileloadstart', tileLoadStart);
          source.on('tileloadend', tileLoadEnd, event.context.canvas);
          source.on('tileloaderror', tileLoadEnd, event.context.canvas);
        });

        map.setSize([width, height]);
        map.getView().fit(extent);
        map.renderSync();
    }, false);

	});
</script>

@endsection
