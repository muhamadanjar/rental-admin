let worker = new Worker(`${Laravel.serverUrl}/js/webworker.js`);
function resLastPosition(a) {
    let count = a.count;
    let data = a.data;
    data.map((v,i)=>{
        let meta = v.meta;
        let userData = v.users;
        let mobilData = v.mobil;
        if(meta.length > 0){
            meta.filter(function(word) {
                if(word.meta_key == 'LOCATION'){
                    let split = word.meta_value.split(',');
                    let transform = ol.proj.getTransform('EPSG:4326', 'EPSG:3857');
                    let coordinate = transform([parseFloat(split[1]), parseFloat(split[0])]);
                    let mapFeature = {
                        geometry: new ol.geom.Point(coordinate),
                        name: userData.name
                    };
                    if (v.hasOwnProperty('mobil')) {
                        mapFeature.mobil = mobilData[0];
                    }
                    // console.log(userData);
                    
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
            });
        }
        $('.span_actived').html(count[0]);
        $('.span_ordered').html(count[1]);
        $('.span_unavailable').html(count[2]);
    });
        
}

function resLastNotif(a) {
    
    $('span.notif_count').html(a.count);
    $('.menu_header').html(a.message);
    let data = a.data;
    $( ".menu_notifikasi" ).html('');
    data.map(function(v,i){
        
            let span = `${v.message} <span class="float-right text-muted text-sm">${v.notifHuman}</span>`;
            $(`<a href='${Laravel.serverUrl}/notif/read/${v.id}' class=\"dropdown-item\">`)
                .append("<i class='fas fa-file mr-2'>")
                    .append(span)
            
            .appendTo( $( ".menu_notifikasi" ) );
        
        $('a.dropdown-item')
        $('.menu_notifikasi')
    })
}
(function ($, window, document) {
    worker.addEventListener('error', function(a) {
        // console.log(a);
        console.error('Error: Line ' + a.lineno + ' in ' + a.filename + ': ' + a.message);
    }, false);
    worker.addEventListener('message', function(a) {
        if (a.data.cmd === 'resLastPosition') { resLastPosition(a.data.val); }
        if (a.data.cmd === 'resLastNotif') { resLastNotif(a.data.val); }
    });
    
    worker.postMessage({ cmd: 'reqLastNotif', val: `${Laravel.serverUrl}/backend/user/notifikasi`});

}(jQuery, window, document));