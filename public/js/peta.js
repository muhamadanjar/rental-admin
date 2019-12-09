var template = Handlebars.compile($("#details-infomap-template").html());
  var panorama;
  var map;
  var infoLayer = 'all';
  var infomap = 'info';
  var singleAllLayers;
  var intLayersString,intLayers = [];
  var selectedLayer = 'all';
  var searchLayer = 'geodata:survei';
  var searchObject = '';
  var overlaysOBJ;//Store array data from database
  var layer_global = [];
  var pureCoverage = false;
  var tematikGroup,rencanaGroup,citrafotoGroup;
  var container_popup,content_popup,closer_popup,overlay_popup;
  var DefaultCoordinate = [106.798036,-6.6033115];
  var geolocation;
  var provinsi = $('select#provinsi');
  var kabkota = $('select#kabkota');
  var kecamatan = $('select#kecamatan');
  var desa = $('select#desa');
  var bogorselatanbounds = [106.88851400971271,-6.594783661681163,106.71582266449786,-6.680039062359272];
  var bounds = [690359.8909999998, 9261356.9862,
                    704368.5469000004, 9279460.6045];

  var basemapTile = new ol.layer.Tile({
      source: new ol.source.OSM(),
      name:'BaseMap Image Esri',
      id:'basemap',
      isBasemap:true
  });

  var __root__ = new ol.layer.Tile({
    visible: true,
    source: new ol.source.TileWMS({
      url: window.Laravel.geoserverUrl+'/wms',
      params: {
        'FORMAT': 'image/png',
        'VERSION': '1.1.1',
        tiled: true,
        LAYERS: 'pandeglang:admin_prov',
        STYLES: '',
      }
    })
  });

  singleAllLayers = __root__;
  function get(name) {
      if (typeof (Storage) !== 'undefined') {
        return localStorage.getItem(name)
      } else {
        window.alert('Please use a modern browser to properly view this template!')
      }
    }
    function store(name, val) {
      if (typeof (Storage) !== 'undefined') {
        localStorage.setItem(name, val)
      } else {
        window.alert('Please use a modern browser to properly view this template!')
      }
    }
function initializePanorama(coord) {
  if(coord == null) {lat = -6.6033115;lng=106.798036;
  }else {lat = coord[1];lng=coord[0];}
  panorama = new google.maps.StreetViewPanorama(
      document.getElementById('street-view'),{
        position: {lat: lat, lng: lng},
        pov: {heading: 165, pitch: 0},
        zoom: 1
  });
}
function initMap(){
  tematikGroup = new ol.layer.Group({
        layers: [],
        name:'Tematik',
        id:'tematik',
  });
  rencanaGroup = new ol.layer.Group({
        layers: [],
        name:'Rencana',
        id:'rencana',
  });
  citrafotoGroup = new ol.layer.Group({
        layers: [],
        name:'Citra Foto',
        id:'citra',
  });
  var scaleLineControl = new ol.control.ScaleLine();
  var overviewMapControl = new ol.control.OverviewMap({
        className: 'ol-overviewmap ol-custom-overviewmap',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          })
        ],
        collapseLabel: '\u00BB',
        label: '\u00AB',
        collapsed: true
  });
  vectorSource = new ol.source.Vector();
  var vectorLayer = new ol.layer.Vector({
    name:'Layer Vector',
    id:'lyr_vector',
    source: vectorSource,
    style: new ol.style.Style({
      fill: new ol.style.Fill({
            color: 'rgba(255, 255, 255, 0.2)'
      }),
      stroke: new ol.style.Stroke({
            color: '#ffcc33',
            width: 2
      }),
      image: new ol.style.Circle({
            radius: 7,
            fill: new ol.style.Fill({
              color: '#ffcc33'
            })
      })
    })
  });

  map = new ol.Map({
    layers: [
      basemapTile, tematikGroup,rencanaGroup,citrafotoGroup,vectorLayer
    ],
    target: 'map',
    view: new ol.View({
      center: ol.proj.fromLonLat(DefaultCoordinate),
      zoom: 15,
      minZoom: 13,
      maxZoom: 19
    }),
    controls: ol.control.defaults({
      attributionOptions: ({
        collapsible: false
      })
    }).extend([
          new basemapTools.basemapControl(),
          overviewMapControl,
          scaleLineControl
    ]),
  });
  var mousePosition = new ol.control.MousePosition({
      coordinateFormat: ol.coordinate.createStringXY(2),
      projection: 'EPSG:4326',
      target: document.getElementById('myposition'),
      undefinedHTML: '&nbsp;'
  });
  map.addControl(mousePosition);

  geolocation = new ol.Geolocation({
    projection: map.getView().getProjection()
  });
  //geolocation.setTracking(true);

  geolocation.on('change', function() {
    console.log('accuracy',geolocation.getAccuracy() + ' [m]');
    console.log('altitude',geolocation.getAltitude() + ' [m]');
    console.log('altitudeAccuracy',geolocation.getAltitudeAccuracy() + ' [m]');
    console.log('heading',geolocation.getHeading() + ' [rad]');
    console.log('speed',geolocation.getSpeed() + ' [m/s]');
    console.log('position',geolocation.getPosition());
  });
  geolocation.on('error', function(error) {
    var info = document.getElementById('info');
    info.innerHTML = error.message;
    info.style.display = '';

    console.log('info',error.message);
  });

  var accuracyFeature = new ol.Feature();
  geolocation.on('change:accuracyGeometry', function() {
    accuracyFeature.setGeometry(geolocation.getAccuracyGeometry());
  });

  var positionFeature = new ol.Feature();
  positionFeature.setStyle(new ol.style.Style({
    image: new ol.style.Circle({
      radius: 6,
      fill: new ol.style.Fill({
        color: '#3399CC'
      }),
      stroke: new ol.style.Stroke({
        color: '#fff',
        width: 2
      })
    })
  }));

  geolocation.on('change:position', function() {
    var coordinates = geolocation.getPosition();
    positionFeature.setGeometry(coordinates ? new ol.geom.Point(coordinates) : null);
  });

  var geol = findBy(map.getLayerGroup(), 'id', 'lyr_vector').getSource();
  geol.addFeature(accuracyFeature);
  geol.addFeature(positionFeature);

  var geocoder = new Geocoder('nominatim', {
    provider: 'osm',
    key: '__some_key__',
    lang: 'en-US', //en-US, fr-FR
    placeholder: 'Pencarian for ...',
    targetType: 'text-input',
    limit: 5,
    keepOpen: true
  });
  map.addControl(geocoder);

  geocoder.on('addresschosen', function (evt) {
    if (evt.bbox) {
      map.getView().fit(evt.bbox, { duration: 500 });
    } else {
      map.getView().animate({ zoom: 14, center: evt.coordinate });
    }
  });

  console.log(map.getView().calculateExtent());

  console.log('getbound',map.getView().calculateExtent(map.getSize()))

}
function initPopup(){
  container_popup = document.getElementById('popup');
  content_popup = document.getElementById('popup-content');
  closer_popup = document.getElementById('popup-closer');

  overlay_popup = new ol.Overlay(/** @type {olx.OverlayOptions} */ ({
    element: container_popup,
    autoPan: true,
    autoPanAnimation: {
      duration: 250
    }
  }));
  closer_popup.onclick = function() {
      overlay_popup.setPosition(undefined);
      closer_popup.blur();
      return false;
  };
  map.addOverlay(overlay_popup);
}
function bindInputs(layerid, layer) {
  //console.log(layerid, layer);
  var visibilityInput = $(layerid + ' input.visible');
  visibilityInput.on('change', function() {
    layer.setVisible(this.checked);
  });
  visibilityInput.prop('checked', layer.getVisible());
  if(layer.getVisible()){
    $(visibilityInput).parent().parent().find('div.control-right').show();
  }else{
    $(visibilityInput).parent().parent().find('div.control-right').hide();
  }
  var opacityInput = $(layerid + ' input.opacity');
  opacityInput.on('input change', function() {
    layer.setOpacity(parseFloat(this.value));
  });
  opacityInput.val(String(layer.getOpacity()));
}
function identifyLayer(layer) {
  layer = layer || 'all';
  var identifyDom = $('select#identify');
  if(identifyDom.length > 0){
    var options = '<option value="all">All..</option>';
        for (var x = 0; x < intLayers.length; x++) {
            var data = intLayers;
            options += '<option value="' + data[x] +'">' + data[x] + '</option>';
        }
    identifyDom.html(options);
    identifyDom.change(function(e) {
      selectedLayer = $(this).val();
      if (selectedLayer != "") {
      }else{
        selectedLayer = "all";
      }
    });
  }
  info_event = map.on('click', identifyLayerEvent);

}
function identifyLayerEvent(evt) {
      var features = map.getFeaturesAtPixel(evt.pixel);
      if (selectedLayer == 'all') {
        var url = __root__.getSource()
            .getGetFeatureInfoUrl(
                evt.coordinate,
                map.getView().getResolution(),
                map.getView().getProjection(),
                {
                  'INFO_FORMAT': 'application/json',
                  //'propertyName': '*',
                  'QUERY_LAYERS':intLayersString,
                  'LAYERS':intLayersString,
                  'FEATURE_COUNT': 1000
                }
            );
      }else{
        var layer = findBy(map.getLayerGroup(), 'id', selectedLayer);
        var url = layer.getSource()
          .getGetFeatureInfoUrl(
            evt.coordinate,
            map.getView().getResolution(),
            map.getView().getProjection(),
            {
              'INFO_FORMAT': 'application/json',
              //'propertyName': '*',
              //'LAYERS':intLayersString,
              //'QUERY_LAYERS':intLayersString,
              'FEATURE_COUNT': 50
            }
          );
      }
    
      $.ajax({
        url: url,
        dataType : 'json',
        error:function (argument) {
            console.log(argument);
        },
        beforeSend: function() {
            $('#loading').html("<img src='images/ajax-loader.gif' />");
        },
      }).done(function (data) {
        //console.log(data);
        var feature = data.features;
        content = template();
        var props = feature.properties;
        // console.log(props);
        var coordinate = evt.coordinate;
        // content_popup.innerHTML = content;
        $('#formModalMap').find('.modal-title').html('Informasi');
        $('#formModalMap').find('.modal-body').html(content);
        var $carousel = $('#carousel-popup').carousel();
        $('#carousel-popup').find('.carousel-infotable').html(tablePopupItem(feature));

        $('#formModalMap').modal();
        $carousel.bind('slide.bs.carousel', function (e) {
            console.log($(".active", e.target).index());
        });
        // overlay_popup.setPosition(coordinate);
      }).fail(function(response){
        DevExpress.ui.notify('Gagal Meminta Request', 'error', 600);
      });


}
function updateInteractiveLayers(layer) {
  var index = $.inArray(layer, intLayers);
  //console.log(index);
  if(index > -1) {
    intLayers.splice(index,1);
  } else {
    intLayers.push(layer);
  }
  intLayersString = intLayers.join(',');
}
function findBy(layer, key, value) {

  if (layer.get(key) === value) {
    return layer;
  }

  if (layer.getLayers) {
  var layers = layer.getLayers().getArray(),
      len = layers.length, result;
      for (var i = 0; i < len; i++) {
        result = findBy(layers[i], key, value);
        if (result) {
          return result;
        }
      }
  }

  return null;
}
function objLayer(overlaysOBJ) {
  if (overlaysOBJ) {
    layer_global = new ol.Collection();
    var ul_layer_tematik = document.createElement('ul');
    ul_layer_tematik.setAttribute('class','list-group');

    for (var i=0; i<overlaysOBJ.length; i++) {
      var _layer = overlaysOBJ[i];
      var groupId = $($('#layercontrol').find('ul')[i]).attr('data-kodegroup');
      var group_ul = $('#layercontrol').find('.panel').find('ul')[i];
      var element = buildLayer(_layer);
      $(element).appendTo($('#layercontrol').find('.panel').find('ul#list-group-'+_layer.kodegroup.split(":")[1]));
      //$('#layertree').empty().append(ul_layer_tematik);
      //console.log(_layer);
      // console.log(overlaysOBJ[i].urllayer.search(/^http\:\/\//i),overlaysOBJ[i].kodelayer);
      var urllayer;
      // if(overlaysOBJ[i].urllayer.search(/^http\:\/\//i) == 0){
      //   //urllayer = 'http://peta.bpn.go.id/proxy/proxy.ashx?'+overlaysOBJ[i].urllayer+'?';
      //   urllayer = window.Laravel.geoserver_url+overlaysOBJ[i].urllayer;
      // }else{
      //   urllayer = window.Laravel.geoserver_url+overlaysOBJ[i].urllayer;
      // }
      urllayer = overlaysOBJ[i].urllayer;
      // console.log(urllayer);
      if(overlaysOBJ[i].tipelayer == 'olimage'){
        var format = 'image/png';
        var untiled = new ol.layer.Image({
          source: new ol.source.ImageWMS({
            ratio: 1,
            url: urllayer,
            params: {'FORMAT': format,
                     'VERSION': '1.1.1',  
                  LAYERS: overlaysOBJ[i].kodelayer,
            }
          }),
          name: overlaysOBJ[i].namalayer,
          id: overlaysOBJ[i].kodelayer,
          visible: overlaysOBJ[i].option_visible,
          opacity: overlaysOBJ[i].option_opacity,
        });
        layer_global.push(untiled);
        singleAllLayers = untiled;
      }else{
        var wmsSource = new ol.source.TileWMS({
          url: urllayer,
          params: {
            'LAYERS': overlaysOBJ[i].kodelayer,
            'VERSION': '1.1.1',
            'FORMAT': 'image/png',
            STYLES: overlaysOBJ[i].option_style,
            //"STYLES": '',
            crossOrigin: 'anonymous',
            serverType: 'geoserver',
            tiled: true,
          },
        });
        var wmsLayerTile = new ol.layer.Tile({
          source: wmsSource,
          visible: overlaysOBJ[i].option_visible,
          opacity: overlaysOBJ[i].option_opacity,
          name: overlaysOBJ[i].namalayer,
          id: overlaysOBJ[i].kodelayer,
          srs: overlaysOBJ[i].srs || 'EPSG:4326'
        });
        layer_global.push(wmsLayerTile);
        singleAllLayers = wmsLayerTile;
      }
      updateInteractiveLayers(overlaysOBJ[i].kodelayer);

    }
    tematikGroup.setLayers(layer_global);
  }
}
function tablePopup(feature){
  if (feature) {
    var content = "<div id='myCarousel' class='carousel slide' data-ride='carousel'>"
    content += "<div class='carousel-inner' style='max-height: 70vh;overflow-y: scroll;'>";

    if(feature.length > 0){
      for (var f in feature) {
        if(f == 0 ){
          content += "<div class='panel panel-default item active table-layout'>";
        }else{
          content += "<div class='panel panel-default item table-layout'>";
        }

        content += "<div class='panel-heading'><h6 class='panel-title'><i class='icon-accessibility'></i><b><a href='#"+feature[f].id+"' class='collapsed'>"+feature[f].id+"</a></b></h6><div class='panel-toolbar text-right'><div class='btn-group'><button class='btn btn-sm btn-default btn-popup-close'><i class='ico-close4'></i></button></div></div></div>";
        content += "<div id='"+feature[f].id.split(".")[0]+"' class='panel-collapse collapse in'>";
        content += "<div class='panel-body' class='max-height: 70vh;overflow-y: scroll;'>";

        content += "<table class='table table-striped'>";
        for (var name in feature[f].properties) {
          var fp = feature[f].properties;
          console.log(name);
          if (name == 'image_link' || name == 'IMAGE_LINK' || name == 'foto' || name == 'FOTO' || name == 'Foto') {
            
            content += "<tr><td><b>" + name + "</b></td><td><b>:</b> </td><td><image class='img-responsive' src='"+Laravel.serverUrl+"/files/uploads/asetkota/" + fp[name] + "' width='100'/></td></tr>";
          }else if(name == 'video'){
            content += "<tr><td><b>" + name + "</b></td><td><b>:</b> </td><td>"+
              "<video width='100%' autoplay loop preload >"+
                "<source src='"+fp[name]+"' type='video/mp4'>"+
                "Your browser does not support HTML5 video."+
              "</video>"+
            "</td></tr>";
          }else if(name == 'bbox'){
          }else{
            content += "<tr><td><b>" + name + "</b></td><td><b>:</b> </td><td>" + fp[name] + "</td></tr>";
          }
        };
        content += '</table>';
        content += '</div>';
        content += '</div>';
        content += '</div>';
      }
    }else{
      content += "<table class='table table-bordered'>";
      content += '<tr><td colspan=3>Data tidak ada</td></tr>';
      content += '</table>';
    }
    content += '</div>';
    content += '</div>';
  }else{
    var content = "<table class='table table-bordered'>";
    content += '<tr><td>Data tidak ada</td></tr>';
    content += '</table>';
  }
  return content;
}
function tablePopupItem(feature){
  console.log(feature);
  var content = "";
  var ns = 'kotabogor';
  if (feature) {
    if(feature.length > 0){
      for (var f in feature) {
        var id_layer = feature[f].id;
        var dl = getjson(Laravel.serverUrl+'/api/getlayerinformasi/'+ns+':'+id_layer.split('.')[0]);
        if(f == 0 ){
          content += "<div class='carousel-item active'>";
        }else{
          content += "<div class='carousel-item'>";
        }
        content += "<table class='table table-striped'>";
        if(dl.id !== null && dl.hasOwnProperty('keydata')){
          var properties = JSON.parse(dl.keydata);
          for(i in properties){
            var props = properties[i];
            var fp = feature[f].properties;
            if(props.visible){
              if(props.fieldType =='image'){
                content += "<tr><td><b>" + props.label + "</b></td><td><b>:</b> </td><td><image class='img-responsive' src='"+Laravel.serverUrl+"/files/uploads/asetkota/" + fp[props.fieldName] + "' width='200'/></td></tr>";
              }else if(props.fieldType == 'video'){
                content += "<tr><td><b>" + props.label + "</b></td><td><b>:</b> </td><td>"+
                  "<video width='100%' autoplay loop preload >"+
                    "<source src='"+Laravel.serverUrl+"/files/uploads/video/"+fp[props.fieldName]+"' type='video/mp4'>"+
                    "<source src='"+Laravel.serverUrl+"/files/uploads/video/"+fp[props.fieldName]+"' type='video/webm'>"+
                    "Your browser does not support HTML5 video."+
                  "</video>"+
                "</td></tr>";
              }else if(props.fieldType =='sertifikat'){
                content += "<tr><td><b>" + props.label + "</b></td><td><b>:</b> </td><td><image class='img-responsive' src='"+Laravel.serverUrl+"/files/uploads/sertifikat/" + fp[props.fieldName] + "' width='200'/></td></tr>";
              }else{
                content += "<tr><td><b>" + props.label + "</b></td><td><b>:</b> </td><td>" +fp[props.fieldName]+ "</td></tr>";
              }
              
            }
          }   
        }else{
          content += "<tr><th>"+id_layer.split('.')[0]+"</th></tr>";
          for (var name in feature[f].properties) {
            var fp = feature[f].properties;
            if (name == 'image_link' || name == 'IMAGE_LINK' || name == 'foto' || name == 'FOTO' || name == 'URL_PHOTO' || name == 'sertifikat' || name == 'Foto') {
              content += "<tr><td><b>" + name.replace('_'," ") + "</b></td><td><b>:</b> </td><td><image class='img-responsive' src='"+Laravel.serverUrl+"/files/uploads/asetkota/" + fp[name] + "' width='200'/></td></tr>";
            }else if(name == 'video'){
              content += "<tr><td><b>" + name + "</b></td><td><b>:</b> </td><td>"+
                "<video width='100%' autoplay loop preload >"+
                  "<source src='"+Laravel.serverUrl+"/files/uploads/video/"+fp[name]+"' type='video/mp4'>"+
                  "<source src='"+Laravel.serverUrl+"/files/uploads/video/"+fp[name]+"' type='video/webm'>"+
                  "Your browser does not support HTML5 video."+
                "</video>"+
              "</td></tr>";
            }else if(name == 'lampiran' || name == 'sertifikat'){
              content += "<tr><td><b>" + name.replace('_'," ") + "</b></td><td><b>:</b> </td><td><image class='img-responsive' src='"+Laravel.serverUrl+"/files/uploads/sertifikat/" + fp[name] + "' width='200'/></td></tr>";
            }
            else if(name == 'bbox' || name.includes('_id') || name.includes('id_') || name.includes('_ID') || name.includes('ID_') || name.includes('ID') || name.includes('id')){
            }else{
              content += "<tr><td><b>" + name.replace('_'," ") + "</b></td><td><b>:</b> </td><td>" + fp[name] + "</td></tr>";
            }
          }
        }
        
        content += '</table>';
        content += '</div>';
      }
    }else{
      content += "<table class='table table-bordered'>";
      content += '<tr><td colspan=3>Data tidak ada</td></tr>';
      content += '</table>';
    }
  }

  return content;
}
function initializeTree() {
  var elem = buildLayerTree(map.getLayerGroup());
  $('#layertree').empty().append(elem);
  $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
  $('.tree li.parent_li > span').on('click', function(e) {
    var children = $(this).parent('li.parent_li').find(' > ul > li');
    if (children.is(":visible")) {
      children.hide('fast');
      $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa fa-plus-square').removeClass('glyphicon-minus');
    } else {
      children.show('fast');
      $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa fa-minus-square').removeClass('glyphicon-plus');
    }
    e.stopPropagation();
  });
}
function buildLayerTree(layer) {
  var elem;
  var name = layer.get('name') ? layer.get('name') : "Group";
  var idlayer = layer.get('id');
  var onclick;
  var li = document.createElement('li');
  var div = "<li class='list-group-item' id='layer-1-"+idlayer+"'> " +
  "<label for='visible_"+idlayer+"'><input type='checkbox' id='visible_"+idlayer+"' class='visible'>"+name+"</label>"+
  "<div class='btn btn-group control-right'>" +
    "<span class='btn btn-primary btn-xs transparan'>Transparan</span>" +
    "<span class='btn btn-success btn-xs legenda'>Legenda</span>" +
  "</div>"+
  "<fieldset id='opacity'>"+
    "<input class='opacity' type='range' min='0' max='1' step='0.01'/>" +
  "</fieldset>" +
  "<fieldset id='legend'>" +
    "<img src='/geoserver/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=pandeglang:batas_kab' alt='Legenda'>" +
  "</fieldset>";

  if (layer.getLayers) {
    var sublayersElem = '';
    var layers = layer.getLayers().getArray(),len = layers.length;
    for (var i = len - 1; i >= 0; i--) {
      sublayersElem += buildLayerTree(layers[i]);
    }
    elem = div + " <ul>" + sublayersElem + "</ul></li>";
  } else {
    elem = div + " </li>";
  }

  return elem;
}
function buildLayer(_layer) {
      var li_layer = document.createElement('li');
      li_layer.setAttribute('class','list-group-item');
      li_layer.setAttribute('id','layer-1-'+_layer.kodelayer.split(':')[1]);
      var label = document.createElement('label');
      label.setAttribute('for','visible_'+_layer.kodelayer.split(':')[1]);
      var input = document.createElement('input');
      input.setAttribute('type','checkbox');
      input.setAttribute('id','visible_'+_layer.kodelayer.split(':')[1]);
      input.setAttribute('class','visible');
      label.append(input);
      label.append(' '+_layer.namalayer);

      li_layer.append(label);
      var div = document.createElement('div');
      div.setAttribute('class','btn btn-group control-right');
      var span_transparan = document.createElement('span');
      span_transparan.setAttribute('class','btn btn-primary btn-xs transparan');
      span_transparan.innerHTML = 'Transparan';
      div.append(span_transparan);

      var span_legenda = document.createElement('span');
      span_legenda.setAttribute('class','btn btn-success btn-xs legenda');
      span_legenda.innerHTML = 'Legenda';
      div.append(span_legenda);

      var span_zoom = document.createElement('span');
      span_zoom.setAttribute('class','btn btn-info btn-xs zoom');
      span_zoom.setAttribute('data-layer',_layer.kodelayer);
      span_zoom.innerHTML = 'Zoom';
      div.append(span_zoom);

      li_layer.append(div);

      var fieldset_opacity = document.createElement('fieldset');
      fieldset_opacity.setAttribute('id','opacity');
      fieldset_opacity.setAttribute('style','display:none');
        var input_op = document.createElement('input');
        input_op.setAttribute('class','opacity');
        input_op.setAttribute('type','range');
        input_op.setAttribute('min','0');
        input_op.setAttribute('max','1');
        input_op.setAttribute('step','0.01');
      fieldset_opacity.append(input_op);
      li_layer.append(fieldset_opacity);

      var fieldset_legenda = document.createElement('fieldset');
      fieldset_legenda.setAttribute('id','legend');
      fieldset_legenda.setAttribute('style','display:none');
        var img_legend = document.createElement('img');
        img_legend.setAttribute('src',Laravel.geoserverUrl+'/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER='+_layer.kodelayer);
        img_legend.setAttribute('alt','Legenda');
      fieldset_legenda.append(img_legend);
      li_layer.append(fieldset_legenda);
      li_layer.addEventListener("click", layerclickEvent);

      return li_layer;
}
function layerclickEvent(e) {
  if($(e.target).hasClass('transparan')){
    if($(e.target).hasClass('active')){
      $(e.target).removeClass('active');
    }else{
      $(e.target).addClass('active');
    }
    $(this).find('fieldset#opacity').toggle();
  }else if($(e.target).hasClass('legenda')){
    $(this).find('fieldset#legend').toggle();
  }else if($(e.target).hasClass('visible')){
    $(this).find('div.control-right').toggle();
  }else if($(e.target).hasClass('zoom')){

    var layer_zoom = findBy(map.getLayerGroup(), 'id', $(e.target).attr('data-layer'));
    zoomToExtent(layer_zoom);
  }
}
function createGroupLayer() {
  grouOpBJ = $.getValues("/map/getdata/group");
  var _div = $('#layercontrol').find('.panel-body');
  for (var i=0; i<grouOpBJ.length; i++) {
    _group = grouOpBJ[i];
    var button = document.createElement('button');
    button.setAttribute('class','layer-control');
    button.append(_group.namalayer);

    _div.append(button);

    var div = document.createElement('div');
    div.setAttribute('class','layer-control-panel panel');
    div.setAttribute('id','layer-group-'+_group.id);

    button.addEventListener('click',function(){
      this.classList.toggle("active");
      console.log(this.nextElementSibling);
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight){
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
    var ul_group = document.createElement('ul');
    ul_group.setAttribute('class','list-group');
    ul_group.setAttribute('id','list-group-'+_group.kodelayer.split(':')[1]);
    ul_group.setAttribute('data-parentid',_group.id);
    ul_group.setAttribute('data-kodegroup',_group.kodelayer);
    div.append(ul_group);
    _div.append(div);

  }
}
function changeInfoMap(info){
  infomap = info;
  console.log(infomap);
}
function zoomToExtent(source) {

  var url = Laravel.geoserverUrl+'/wms?request=GetCapabilities&service=WMS&version=1.1.1';
  var parser = new ol.format.WMSCapabilities();
  var layerobj;
  $.ajax(url).then(function(response) {
    var result = parser.read(response);
    var Layers = result.Capability.Layer.Layer;
    for (var i=0, len = Layers.length; i<len; i++) {
      layerobj = Layers[i];
      // console.log(layerobj.Name,source.get('id'));
      if (layerobj.Name == source.get('id')) {
        
        extent = layerobj.BoundingBox[0].extent;
        coord1 = ol.proj.transform([extent[1],extent[0]], 'EPSG:4326', 'EPSG:3857');
        coord2 = ol.proj.transform([extent[2],extent[3]], 'EPSG:4326', 'EPSG:3857');
        console.log(extent);
        console.log(coord1);
        console.log(coord2);
        extent = ol.extent.boundingExtent([coord1],[coord2]);
        extent_= ol.proj.transformExtent(extent,source.get('srs'),'EPSG:3857');
        
        console.log(source.get('srs'),extent);
        console.log(extent_);
        map.getView().fit(extent_, map.getSize());
        break;
      }
    }
    // console.log(extent);
  });
}
function resetFilter() {
  if (pureCoverage) {
    return;
  }
  $('#query-text').val("");
  updateFilter();
}
function updateFilter(){
  if (pureCoverage) {
    return;
  }
  var filter = $('#query-text').val();
  var filterwhere = {
      'geodata:poi_kota_bogor': "WHERE KATEGORI2 LIKE '%"+filter+"%'",
      'geodata:data_survei': "WHERE alamat LIKE '%"+filter+"%' OR nama_survei LIKE '%"+filter+"%' OR nama_objek LIKE '%"+filter+"%' OR nama_jenis LIKE '%"+filter+"%'",
      'geodata:aset_kota_bogor': "WHERE alamat LIKE '%"+filter+"%' OR nama_survei LIKE '%"+filter+"%'",
  };
  var filterParams = {
      'FILTER': null,
      'CQL_FILTER': null,
      'FEATUREID': null
  };
  searchObject = findBy(map.getLayerGroup(), 'id', selectedLayer);

  if (filter.replace(/^\s\s*/, '').replace(/\s\s*$/, '') != "") {
    filterParams["CQL_FILTER"] = filterwhere[selectedLayer];
    console.log(filterParams);
    searchObject.getSource().updateParams(filterParams);
  }
  if(filter == ""){
    filterParams["CQL_FILTER"] = null;
    searchObject.getSource().updateParams(filterParams);
  }
}

function capital_varter(str) {
    str = str.split(" ");
    for (var i = 0, x = str.length; i < x; i++) {
        str[i] = str[i][0].toUpperCase() + str[i].substr(1);
    }
    return str.join(" ");
}
function view_streetview(obj) {
      var coord4326 = ol.proj.transform(obj.coordinate, 'EPSG:3857', 'EPSG:4326'),   
      template = 'Coordinate is ({x} | {y})',
      iconStyle = new ol.style.Style({
        image: new ol.style.Icon({ scale: .6, src: 'img/pin_drop.png' }),
        text: new ol.style.Text({
          offsetY: 25,
          text: ol.coordinate.format(coord4326, template, 2),
          font: '15px Open Sans,sans-serif',
          fill: new ol.style.Fill({ color: '#111' }),
          stroke: new ol.style.Stroke({ color: '#eee', width: 2 })
        })
      }),
      feature = new ol.Feature({
        type: 'removable',
        geometry: new ol.geom.Point(obj.coordinate)
      });
      // feature.setStyle(iconStyle);
      $('#formModalMap').find('.modal-title').html('Street View');
      $('#street-view').css({'height':'400px'}).appendTo($('#formModalMap').find('.modal-body'));
      
      $('#formModalMap').modal();
      initializePanorama(coord4326);
      console.log(coord4326);
      // vectorLayer.getSource().addFeature(feature);
}
function OsOpenNamesSearch(options) {
    var url = options.url;
    return {
      /**
       * Get the url, query string parameters and optional JSONP callback
       * name to be used to perform a search.
       * @param {object} options Options object with query, key, lang,
       * countrycodes and limit properties.
       * @return {object} Parameters for search request
       */
      getParameters: function (opt) {
        return {
          url: url,
          callbackName: 'callback',
          params: {
            q: opt.query
          }
        };
      },
      /**
       * Given the results of performing a search return an array of results
       * @param {object} data returned following a search request
       * @return {Array} Array of search results
       */
      handleResponse: function (results) {
        // The API returns a GeoJSON FeatureCollection
        if (results && results.features && results.features.length) {
          return results.features.map(function (feature) {
            return {
              lon: feature.geometry.coordinates[0],
              lat: feature.geometry.coordinates[1],
              address: {
                // Simply return a name in this case, could also return road,
                // building, house_number, city, town, village, state,
                // country
                name: feature.properties.search_full
              },
              bbox: feature.bbox
            };
          });
        } else {
          return;
        }
      }
    };
}
