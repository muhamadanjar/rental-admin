
@extends('layouts.full')
@section('style-head')
    
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('plugins/openlayers/ol.css')}}" type="text/css">

@endsection
@section('head_title',$title)
    

@section('style-theme')
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mapv2.css') }}"/>
@endsection

@section('body-class','frontend')
    
@section('content')
    
<div class="wrapper">
    
    <nav class="navbar navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('img/logo.png') }}" width="50%" height="50%" class="d-inline-block align-top" alt="">
            {{-- {{ Config::get('app.name') }} --}}
        </a>
    </nav>
    <div class="content-wrapper">
        <div class="content">
                <div id="slidebar-left" style="background-color: #f3f3f3;" class="open">
                        <div class="toggle"><a href="javascript:void(0)"></a></div>
                        <div id="style-1" class="scrollbar">
                            <div class="force-overflow">
                                <div id="accordion">
        
                                    <div class="card" style="margin-bottom:10px !important;background-color: #3983d8">
                                        <a style="font-color:blue !important" href="{{ url('/') }}" class="btn btn-link collapsed">
                                            <div style="float: left; font-size: 16px; color: #ffffff">
                                                Home
                                            </div>
                                            <i class="fa fa-home" style="float: right;color: white"></i>
                                        </a>
                                    </div>
                                    
                                    <div id="layercontrol">
                                    </div>
        
                                </div>
                            </div>  
                        </div>
                    </div>
            <div class="container-fluid map-container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 nopadding">
                        <div class="map" id="map"></div>
                        <div id="mouse-position"></div>
                        <div id="popup" class="ol-popup">
                            <div id="loader-overlay">
                                <div class="loader" id="loader">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                            <div id="popup-content" style="padding: 10px;text-align: left;max-height: 250px;overflow-x: hidden;overflow-y: auto;">

                                <div class="">
                                    <div class="col-xs-12">
                                        <table id="view-table" style="width:100%">
                                            <tbody id="feature-popup-content">
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div id="detail-content">
                <button href="#" id="close" class="btn btn-grd-primary btn-sm" style="margin-bottom: 20px"><i class="ti-close"></i></button>
                <div id="content" style="height:90%"></div>
            </div>
            
        </div>
        
    </div>
    
</div>

@include('layouts.elements.handlebar')
@include('layouts.elements.modal')

@endsection

@section('script-end')
{{-- <script src="{{ asset('plugins/jquery/jquery.slim.js')}}" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> --}}

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="{{ asset('plugins/openlayers/ol.js')}}"></script>


<script type="text/javascript" src="{{ url('plugins/handlebar/handlebars.js')}}"></script>

<script type="text/javascript" src="{{ url('js/op/basemap.js')}}"></script>
<script type="text/javascript" src="{{ url('js/op/measure.js')}}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/peta.op.js') }}"></script> --}}

<script type="text/javascript">
    let mapDiv,map;
    let identifyFeature;
    let searchCollection = [],featureInfo = [];
    var start_measure = false;
    let view;
    let layer = [];
    var raw_local_wms;
    let local_gs = Laravel.geoserverUrl+'/wms';
    var layer_count = 0;
    var layeritem = 0;
    var layer_source = [];
    var info_layer = [];
    var layer_index = [];
    var layerlist = [];
    var map_extent = [106.88851400971271,-6.594783661681163,106.71582266449786,-6.680039062359272];
    var embedded;
    var palapa_api_url;
    var overlaysOBJ;
    var groupCollection = [];

    var closer = document.getElementById('popup-closer');
    $(function(){
        // initMap();
        var mousePositionControl = new ol.control.MousePosition({
            coordinateFormat: ol.coordinate.createStringXY(4),
            projection: 'EPSG:4326',
            className: 'custom-mouse-position',
            target: document.getElementById('mouse-position'),
            undefinedHTML: '&nbsp;'
        });
        var popupContainer = document.getElementById('popup');
        var overlay = new ol.Overlay(/** @type  {olx.OverlayOptions} */ ({
            element: popupContainer,
        }));
        closer.onclick = function() {
            overlay.setPosition(undefined);
            closer.blur();
            return false;
        };


        var vectorImbLayer,vectorImbSource,iconImbFeature=[];
        vectorImbSource = new ol.source.Vector();
        vectorImbLayer = new ol.layer.Vector({
            source: vectorImbSource,
            id:'layer_imb_vector'
        });

        var draw_source = new ol.source.Vector();
        var draw_vector = new ol.layer.Vector({
            source: draw_source,
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
            }),
            zIndex: 666666
        });

        var vector_style = new ol.style.Style({
            fill: new ol.style.Fill({
                color: 'rgba(170, 34, 34, 0.3)'
            }),
            stroke: new ol.style.Stroke({
                color: '#F22',
                width: 1
            }),
            text: new ol.style.Text({
                font: '12px Calibri,sans-serif',
                fill: new ol.style.Fill({
                    color: '#000'
                }),
                stroke: new ol.style.Stroke({
                    color: '#fff',
                    width: 3
                })
            }),
            image: new ol.style.Circle({
                radius: 5,
                fill: new ol.style.Fill({ color: 'rgba(170, 34, 34, 0.3)' }),
                stroke: new ol.style.Stroke({ color: '#F22', width: 1 })
            })
        });

        view = new ol.View({
            center: ol.proj.fromLonLat([104.4495777,0.9171477]),
            zoom: 14,
            // extent: ol.proj.transformExtent([110,-6,120,6], ol.proj.get('EPSG:4326'), ol.proj.get('EPSG:3857')),
            minZoom: 13,
        });
        mapDiv = new ol.Map({
            layers: [
                new ol.layer.Tile({source: new ol.source.OSM()}),
                vectorImbLayer
            ],
            target: 'map',
            view: view,
            controls: ol.control.defaults({
                attributionOptions: ({
                    collapsible: false
                })
            }).extend([mousePositionControl]),
            overlays: [overlay],
        });
        map = mapDiv;
        mapDiv.on('singleclick', function(evt) {
            var coordinate = evt.coordinate;
            var hdms = ol.coordinate.toStringHDMS(coordinate);
            console.log('event click');

            if (!start_measure) {
                mapDiv.forEachLayerAtPixel(evt.pixel, function(layer) {
                    console.warn('CALLBACK')
                }, this, function(layer) {
                    console.log('FILTER')
                    var feature = mapDiv.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
                        return feature;
                    });
                    console.log(layer, feature);
                    if (feature) {
                        return true
                    } else {
                        return false
                    }
                })

                var layerWithWmsSource = mapDiv.forEachLayerAtPixel(evt.pixel,
                    function(layer) {
                        // return only layers with ol.source.TileWMS
                        var source = layer.getSource();
                        if (source instanceof ol.source.TileWMS) {
                            return layer;
                        }
                    });
                    console.log(layerWithWmsSource);
                if (layerWithWmsSource) {
                    $('#popup-content').empty();
                    getInfo(evt, layerWithWmsSource);
                    overlay.setPosition(coordinate);
                }

                // Attempt to find a feature in one of the visible vector layers
                var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
                    return feature;
                });

                var content_html;
                if (feature) {
                    console.log(feature)
                    $('#popup-content').empty();
                    fkeys = feature.getKeys();
                    var tabel_info_head = "<table class='highlight'><thead><tr><th>Key</th><th>Value</th></tr></thead><tbody id='isiinfo'></tbody></table>";
                    $('#popup-content').append(tabel_info_head)
                    for (i = 0; i < fkeys.length; i++) {
                        if (fkeys[i] != 'geometry') {
                            // content_html = "<p>" + fkeys[i] + ": " + feature.get(fkeys[i]) + "</p>";
                            // $('#popup-content').append(content_html)
                            
                            if (fkeys[i] == 'photo') {
                                content_html = "<tr><td>" + fkeys[i] + "</td><td><img style='width: 250px;' src='data:image/jpeg;base64," + feature.get(fkeys[i]) + "'/></td></tr>";
                            } else {
                                content_html = "<tr><td>" + fkeys[i] + "</td><td>" + feature.get(fkeys[i]) + "</td></tr>";
                            }
                            
                            $('#isiinfo').append(content_html)
                        }
                    }
                    // $.each(feature.S, function(index, value) {
                    //     console.log(index, value);
                    //     content_html = "<p>" + index + ": " + value + "</p>";
                    //     $('#popup-content').append(content_html)
                    // });
                    // var content_html = '<p>You clicked here:</p><code>' + hdms + '</code>';
                    $('#popup-content').append(content_html)
                    overlay.setPosition(coordinate);
                }
            }
        });

        initGroupLayer();
        
        $('#slidebar-left').on('click','div.toggle',function(e){
            console.log(e);
            $(this).parent().toggleClass('open');
        })
        $('#bottom').on('click','div.toggle',function(e){
            console.log(e);
            $(this).parent().toggleClass('open');
        })

        $("#layercontrol").on('click', "li .collapsible-header .layer_control i#visibility", function(e) {
            e.stopPropagation();
            p_id = $(e.target).closest('li').attr('id');
            // console.log($(e.target).text());
            p_state = $(e.target).text();
            layerVis(p_id);
            if (p_state == 'check_box') {
                $(e.target).text('check_box_outline_blank');
            } else {
                $(e.target).text('check_box');
            }
            e.preventDefault();
        })

        $("#layercontrol").on('click', "li .collapsible-header i#zextent", function(e) {
            e.stopPropagation();
            p_id = $(e.target).closest('li').attr('id');
            layerZm(p_id);
            e.preventDefault();
        })
    });

    async function initGroupLayer() {
        try {
            let dataGrp = await axios.get('{{ $link }}');
            grouOpBJ = dataGrp.data;
            groupCollection = grouOpBJ;
            getLayers(groupCollection);
            var _div = $('#layercontrol');
            for (var i=0; i<grouOpBJ.length; i++) {
                    _group = grouOpBJ[i];
                var div = document.createElement('div');
                div.setAttribute('class','card');
                var header = document.createElement('div');
                header.setAttribute('class','card-header btn btn-link');
                header.setAttribute('data-toggle','collapse');
                header.setAttribute('data-target','#sidebar-tab-'+_group.id);
                header.setAttribute('aria-expanded','false');
                header.setAttribute('aria-controls','sidebar-tab-'+_group.id);
                header.setAttribute('data-menu-id',_group.id);
                var span = document.createElement('span');
                span.setAttribute('style','float: left; font-size: 16px; color: #007bff');
                span.append(_group.namalayer);
                header.append(span);
               
               var content = document.createElement('div');
               content.setAttribute('id','sidebar-tab-'+_group.id);
               content.setAttribute('class','collapse');
               content.setAttribute('aria-labelledby','headingOne');
               content.setAttribute('data-parent','#accordion');

               var cardBody = document.createElement('div');
               cardBody.setAttribute('class','card-body');
               cardBody.setAttribute('style','overflow:auto;height:300px');
               
               var ul = document.createElement('ul');
               ul.setAttribute('style','margin-left: -50px;')
               ul.setAttribute('class','intro');
               ul.setAttribute('id','list-group-'+_group.kodelayer.split(':')[1]);
               ul.setAttribute('data-parentid',_group.id);
               ul.setAttribute('data-kodegroup',_group.kodelayer);
               cardBody.append(ul);

               content.append(cardBody);
               div.append(header);
               div.append(content);
               _div.append(div);

            }
        } catch (error) {
            console.error(error);
        }
    }

    async function getLayers(collection) {
        for (let index = 0; index < collection.length; index++) {
            const element = collection[index];
            let remote_data = await axios.get(`/map/getdata/layer/${element.id}`);
            const response = remote_data.data;
            let data = response.data;
            for (i = 0; i < data.length; i++) {
                layeritem = {};
                layeritem = data[i];
                aktif = data[i]['option_visible'];
                let remote_url = data[i].urllayer;
                olAddFrontWMSLayer(remote_url, layeritem.kodelayer, layeritem.namalayer, layeritem.x_min, layeritem.y_min, layeritem.x_max, layeritem.y_max, layeritem.kodelayer, aktif, layeritem);
                console.log('Adding Layer : ',layeritem.namalayer);
            }
            
        }
        
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

            console.log(overlaysOBJ[i].urllayer.search(/^http\:\/\//i),overlaysOBJ[i].kodelayer);
            var urllayer;
            // if(overlaysOBJ[i].urllayer.search(/^http\:\/\//i) == 0){
            //   //urllayer = 'http://peta.bpn.go.id/proxy/proxy.ashx?'+overlaysOBJ[i].urllayer+'?';
            //   urllayer = window.Laravel.geoserver_url+overlaysOBJ[i].urllayer;
            // }else{
            //   urllayer = window.Laravel.geoserver_url+overlaysOBJ[i].urllayer;
            // }
            urllayer = overlaysOBJ[i].urllayer;
            // console.log(urllayer);
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
            //if(overlaysOBJ[i].option_visible){
                updateInteractiveLayers(overlaysOBJ[i].kodelayer);
            //}

            }
            tematikGroup.setLayers(layer_global);
        }
    }
    function olAddFrontWMSLayer(serviceUrl, layername, layermark, min_x, min_y, max_x, max_y, layer_nativename, aktif,more_option = false) {
        // rndlayerid = randomNumber()
        // if (_proxy) {
        //     serviceUrl = _proxy + encodeURIComponent(serviceUrl);
        // }
        console.log(more_option.option_style);
        
        if(more_option.option_style == null){
            style = 'generic'
        }else{
            style = more_option.option_style;
        }
        console.log(style);
        
        window.layer_count = layer_count + 1;
        // rndlayerid = layer_count;
        rndlayerid = more_option.id;
        layer_source[rndlayerid] = new ol.source.TileWMS({
            url: serviceUrl,
            params: { LAYERS: layername, TILED: true,STYLES:style,crossOrigin: 'anonymous',
                    serverType: 'geoserver','VERSION': '1.1.1','FORMAT': 'image/png', },

        });
        layer[rndlayerid] = new ol.layer.Tile({
            title: layermark,
            tipe: 'WMS',
            visible: aktif,
            preload: Infinity,
            // extent: extToMerc([min_x, min_y, max_x, max_y]),
            source: layer_source[rndlayerid]
        });
        // console.log(layer[rndlayerid]);
        map.addLayer(layer[rndlayerid]);
        // console.log(rndlayerid, layermark, layer[rndlayerid].get('title'))
        setTimeout(() => {
            _layer = more_option;
            const toc = buildToc(_layer);
            // listappend = "<li id='" + rndlayerid + "'><div class='collapsible-header'><div class='layer_control'><input type='checkbox' /><span class='layer_name'>" + layer[rndlayerid].get('title') + "</span></div><!--<i id='getinfo' class='material-icons right'>comment</i>--><i id='zextent' class='material-icons right'>aspect_ratio</i><i id='remove' class='material-icons right'>cancel</i></div></div><div class='collapsible-body'><div class='row opa'><span class='col s4'><i class='material-icons' style=' padding-right: 15px; position: relative; bottom: -6px;'>opacity</i>Opacity</span><div class='col s8 range-field'><input type='range' id='opacity' min='0' max='100' value='100'/></div></div><span id='wmslegend_" + rndlayerid + "'></span></div></li>";
            console.log(toc);
            
            $(toc).appendTo($('#layercontrol').find('.card').find('ul#list-group-'+_layer.kodegroup.split(":")[1]));
            info_layer.push(rndlayerid);
            legend_url = serviceUrl + '?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&legend_options=fontAntiAliasing:true&LAYER=' + layer_nativename;
            legend_html = "<img src='" + legend_url + "'>";
            $('#wmslegend_' + rndlayerid).append(legend_html);
            layer_index.push(rndlayerid);
            var layeritem = { layer_id: String(rndlayerid), layer_nativename: layer_nativename, layer_name: layer[rndlayerid].get('title') };
            layerlist.push(layeritem);
            layer[rndlayerid].setZIndex(layer.length);
        }, 1000);
    }


    function getInfo(evt, layer) {
        var resolution = map.getView().getResolution();
        var url = layer.getSource().getGetFeatureInfoUrl(evt.coordinate,
            resolution, 'EPSG:3857', { 'INFO_FORMAT': 'application/json' });
        if (url) {
            console.log(url)
            var infos;
            var tabel_info_head = "<table class='highlight'><thead><tr><th>Key</th><th>Value</th></tr></thead><tbody id='isiinfo'></tbody></table>";
            $('#popup-content').append(tabel_info_head)

            function getInnerInfo() {
                $.ajax({
                    url: url,
                    async: false,
                    success: function(data) {
                        try {
                            data = JSON.parse(decodeURIComponent(data))
                        } catch (error) {
                            //
                        }
                        infos = data.features[0].properties;
                        for (var key in infos) {
                            var value = infos[key];
                            content_html = "<tr><td>" + key + "</td><td>" + value + "</td></tr>";
                            $('#isiinfo').append(content_html)
                        }
                    }
                });
            }
            getInnerInfo();
            // $.get(url, function(data) {
            //     console.log(data)
            //     infos = data.features[0].properties;
            //     for (var key in infos) {
            //         var value = infos[key];
            //         content_html = "<tr><td>" + key + "</td><td>" + value + "</td></tr>";
            //         $('#isiinfo').append(content_html)
            //     }
            // })
        }
    }

    function layerVis(layerid) {
        if (layer[layerid].getVisible() == true) {
            layer[layerid].setVisible(false);
        } else {
            layer[layerid].setVisible(true);
        }
    };

    function layerZm(layerid) {
        if (layer[layerid].type == 'TILE') {
            layer_extent = layer[layerid].getExtent();
            map.getView().fit(layer_extent, map.getSize());
        }
        if (layer[layerid].type == 'VECTOR') {
            layer_extent = layer[layerid].getSource().getExtent();
            map.getView().fit(layer_extent, map.getSize());
        }
    };

    function layerOpa(layerid, opacity) {
        opafrac = opacity / 100;
        layer[layerid].setOpacity(opafrac);
    };

    function buildToc(_layer) {
        var li_layer = document.createElement('li');
        li_layer.setAttribute('class','list-group-item');
        li_layer.setAttribute('id','layer-1-'+_layer.kodelayer.split(':')[1]);
        li_layer.setAttribute('data-id',_layer.id);
        var label = document.createElement('label');
        label.setAttribute('for','visible_'+_layer.kodelayer.split(':')[1]);
        var input = document.createElement('input');
        if (_layer.option_visible) {
            input.setAttribute('checked','checked');    
        }
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
            legend_url = _layer.urllayer + '?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&legend_options=fontAntiAliasing:true&LAYER=' + _layer.kodelayer;
            img_legend.setAttribute('src',legend_url);
            img_legend.setAttribute('alt','Legenda');
        fieldset_legenda.append(img_legend);
        li_layer.append(fieldset_legenda);
        li_layer.addEventListener("click", (e)=>{
            
            let input = e.target;
            let li = $(input).closest('li');
            console.log($(li).data());
            
            let id = $(li).data('id');
            layerVis(id);
        });
        li_layer.addEventListener("change", (e)=>{
            
            let input = e.target;
            let li = $(input).closest('li');
            console.log($(li).data());
            
            let id = $(li).data('id');
            layerVis(id);
        });

        return li_layer;
    }

</script>



@endsection