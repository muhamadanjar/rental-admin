$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
$.fn.rowCount = function () {
    return $('tr', $(this).find('tbody')).length;
};
window.gMapsCallback = function () {
    $(window).trigger('gMapsLoaded');
}

var permission;
var provinsi = $('select#provinsi');
var kabkota = $('select#kabkota');
var kecamatan = $('select#kecamatan');
var desa = $('select#desa');

$.extend({
    getValues: function (url) {
        var result = null;
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            async: false,
            success: function (data) {
                result = data;
            }
        });
        return result;
    }
});

var getjson = function (url) {
    var result = null;
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        async: false,

        success: function (data) {
            result = data;
        }
    });
    return result;
}
var numeralsOnly = function (evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
        ((evt.which) ? evt.which : 0));
        console.log(charCode);
    if (charCode > 31 && (charCode < 37 || charCode > 40) && (charCode < 48 || charCode > 57) && (charCode != 46) && (charCode > 190 || charCode < 190 ) && (charCode > 116 || charCode < 116)) {
        alert("Hanya Nomor yang bisa di input pada kolom ini.");
        // console.log(evt);
        return false;
    }
    return true;
}
var lettersOnly = function (evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
        ((evt.which) ? evt.which : 0));
    if (charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)) {
        alert("hanya huruf saja.");
        return false;
    }
    return true;
}
var ynOnly = function (evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
    if (charCode > 31 && charCode != 78 && charCode != 89 && charCode != 110 && charCode != 121) {
        alert("Masukan hanya \"Y\" atau \"N\".");

        return false;
    }
    return true;
}
var rangeNumber = function (evt) {

    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
        ((evt.which) ? evt.which : 0));
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        alert("Hanya Nomor yang bisa di input pada kolom ini.");
        $(this).val(0);
        return false;
    }
    var max = 100;
    var min = 0;
    if ($(this).val() > max) {
        $(this).val(max);
    } else if ($(this).val() < min) {
        $(this).val(min);
    }
    return true;
}
var hasRole = function (name) {
    permission = getjson('/api/checkpermission');
    var ret = null;
    for (i in permission) {
        console.log(permission[i]);
        if (permission[i] == name) {
            return true;
        }
    }
}
var sendData =function(address, data, typeSend, callback) {
    $.ajax({
        url: address,
        type: typeSend,
        timeout: 30000,
        dataType: 'json',
        data: data,
        cache: false,
        complete: function(jqXHR) {
            if (typeof jqXHR.responseJSON === 'undefined') { jqXHR.responseJSON = { code: jqXHR.status, message: jqXHR.statusText }; }
            if (typeof callback === 'function') { callback(jqXHR.responseJSON); }
        }
    });
}
window.sendData = sendData;
function base64image(resFile) {
    var reader = new FileReader();
    reader.readAsDataURL(resFile);
    reader.onloadend = function (evt) {
        return (evt.target.result);
    }
}

var loaddatawilayah = function(url, data) {
    return $.ajax({
        url: url,
        dataType: "json",
        beforeSend: function () {
            $('.loader').show();
            $("#loader-wrapper").show();
        },
    });

}

function loadProvinsi() {
    loaddatawilayah('/api/getprovinsi').then(function (res) {
        var options = '<option value="0">Pilih Provinsi..</option>';
        let data = res.data;
        for (var x = 0; x < data.length; x++) {
            var selected = (data[x]['kode_prov'] == $('#kode_prov').val()) ? "selected" : "";
            options += '<option value="' + data[x]['kode_prov'] + '"' + selected + '>' + data[x]['nama_provinsi'] + '</option>';
        }
        console.log(options);
        
        provinsi.html(options);
        $("#loader-wrapper").hide();
    });
}
window.loadProvinsi = loadProvinsi;
function loadKabupaten(id) {
    kabkota.html("<option value=''>Pilih Kota..</option>");
    loaddatawilayah('/api/getkabupaten/' + id).then(function (res) {
        var options = '<option value="0">Pilih Kota..</option>';
        let data = res.data;
        for (var x = 0; x < data.length; x++) {
            var selected = (data[x]['kode_kab'] == $('#kode_kab').val()) ? "selected" : "";
            options += '<option value="' + data[x]['kode_kab'] + '"' + selected + '>' + data[x]['nama_kabupaten'] + '</option>';
        }
        kabkota.select2();
        kabkota.html(options);
        $("#loader-wrapper").hide();
    });
}
window.loadKabupaten = loadKabupaten;
var loadKecamatan = function(id) {
    kecamatan.html("<option value=''>Pilih Kecamatan..</option>");
    loaddatawilayah('/api/getkecamatan/' + id).then(function (data) {
        let options = '<option value="0">Pilih Kecamatan..</option>';
        let _data = data.data;
        for (var x = 0; x < _data.length; x++) {
            var selected = (_data[x]['kode_kec'] == $('#kode_kec').val()) ? "selected" : "";
            options += '<option value="' + _data[x]['kode_kec'] + '" ' + selected + '>' + _data[x]['nama_kecamatan'] + '</option>';
        }
        kecamatan.select2();
        kecamatan.html(options);
        $("#loader-wrapper").hide();
    });
}
window.loadKecamatan = loadKecamatan;
function loadDesa(id) {
    desa.html("<option value=''>Pilih Desa/Kelurahan..</option>");
    loaddatawilayah('/api/getdesa/' + id).then(function (res) {
        let data = res.data;
        var options = '<option value="0">Pilih Desa..</option>';
        for (var x = 0; x < data.length; x++) {
            var selected = (data[x]['kode_desa'] == $('#kode_desa').val()) ? "selected" : "";
            options += '<option value="' + data[x]['kode_desa'] + '" ' + selected + '>' + data[x]['nama_desa'] + '</option>';
        }
        desa.select2();
        desa.html(options);
        $("#loader-wrapper").hide();
    });
}

function loadGoogleMaps() {
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type", "text/javascript");
    script_tag.setAttribute("src", "http://maps.google.com/maps/api/js?key=AIzaSyC-kEXeuhgPWY__PZ9mzePYwJuMwOzLyC0&sensor=false&callback=gMapsCallback");
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
}
(function ($, window, document) {
    $("#loader-wrapper").hide();
    // nprogress
    $(document).ajaxStart(function () {
        // NProgress.start();
    });

    $(document).ajaxStop(function () {
        // NProgress.done();

        //clickable_tr();
    });

}(jQuery, window, document));
//Modal
(function ($, window, document) {
    $('.formConfirm').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;
        var el = $(this).parent();
        var title = el.attr('data-title');
        var msg = el.attr('data-message');
        var dataForm = el.attr('data-form');

        $('#formConfirm')
            .find('#frm_body').html('<h6>' + msg + '</h6>')
            .end().find('#frm_title').html(title)
            .end().modal('show');

        $('#formConfirm').find('#frm_submit').attr('data-form', dataForm);
    });

    $('#formConfirm').on('click', '#frm_submit', function (e) {
        var id = $(this).attr('data-form');
        //console.log(id);
        $(id).submit();
    });


}(jQuery, window, document));

//Upload
(function ($, window, document) {
    // var hasChanged = false;
    // $(window).bind('beforeunload', function () {
    //     if (hasChanged) {
    //         return "You have unsaved changes";
    //         hasChanged = false;
    //     }
    // });
    // $('input[type=text]').change(function () {
    //     hasChanged = true;
    // });

    $('.formUpload').on('click', function (e) {
        var file_input = $(this).closest('span').find('.file');
        if (file_input.length > 0) {
            file_input.trigger('click');
        }
    });
    $('.fileupload:file').change(function () {
        var fileinput = $(this);
        var fileinput_url = fileinput.attr('data-url');
        var fileinput_path = fileinput.attr('data-path');
        var fileinput_type = fileinput.attr('data-type');
        var fileinput_id = fileinput.attr('data-id');
        var formData = new FormData($('*formId*')[0]);
        console.log(fileinput);
        formData.append("_token", window.Laravel.csrfToken);
        formData.append("id", fileinput_id);
        if (fileinput_type == '' || fileinput_type == 'single') {
            var file = this.files[0];
            name = file.name;
            size = file.size;
            type = file.type;
            if (file.name.length < 1) {} else if (file.size > 209715200) {
                alert("File Terlalu Besar, Maksimal 200 Mb");
            } else if (file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' && file.type != 'application/pdf' && file.type != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                alert("File tidak diijinkan untuk di upload");
                $(this).val('');
            } else {
                if (!!file.type.match(/.*/)) {
                    formData.append("images", file);
                }
                $.ajax({
                    url: fileinput_url /*"/kuesioner/bangunan/upload"*/ ,
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $("#loader-wrapper").show();
                    },
                    complete: function () {
                        $("#loader-wrapper").hide();
                    },
                    success: function (data) {
                        
                        if(data.error) return false;
                        if ($('.txtfoto').length > 0) {
                            
                            fileinput.closest('div.controlupload').find('input.txtfoto')
                                .css({
                                    "color": "peru",
                                    "border": "2px solid blue"
                                }).val(data.filename);
                            fileinput.parent().parent().parent().find('img.imgfoto').attr('src', fileinput_path + '/' + data.filename);
                        }
                    },
                    error: errorHandler = function (e) {
                        alert("Something went wrong!");
                        console.log(e);
                    },
                });
            }
        } else {
            var file = this.files;
            var ins = file.length;
            for (var x = 0; x < ins; x++) {
                formData.append("images[]", file[x]);
            }
            $.ajax({
                url: fileinput_url,
                type: "POST",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    $("#loader-wrapper").show();
                },
                complete: function () {
                    $("#loader-wrapper").hide();
                },
                success: function (data) {
                    if(data.error) return false;
                    if ($('.txtfoto').length > 0) {
                        fileinput.closest('div.controlupload').find('.txtfoto')
                            .css({
                                "color": "peru",
                                "border": "2px solid blue"
                            })
                            .val(data[0].filename);
                        var img = $('<img id="dynamic">');
                        img.attr('class', 'img img-thumbnail');
                        img.attr('src', fileinput_path + '/' + data[0].filename);
                        //console.log(base64image(fileinput_path+'/'+data[0].filename));
                        fileinput.closest('div.controlupload').prev()
                            .append(img);

                    }
                },
                error: errorHandler = function (e) {
                    alert("Something went wrong!");
                    console.log(e);
                },
            });
        }

    });
    $('#filedokumentasi').on('change',function(){
        var fileinput = $(this);
        var fileinput_url = fileinput.attr('data-url');
        var fileinput_path = fileinput.attr('data-path');
        var formData = new FormData($('*formId*')[0]);
        formData.append("_token", window.Laravel.csrfToken);
        formData.append("path", fileinput_path);
        var file = this.files[0];
            name = file.name;
            size = file.size;
            type = file.type;
    
            if(file.name.length < 1) {
            }else if(file.size > 209715200) {
                alert("File Terlalu Besar, Maksimal 200 Mb");
            }else {
                if(!!file.type.match(/.*/)){
                    formData.append("images", file);
                }
                $.ajax({
                    url: fileinput_url,
                    type: "POST",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType:'json',
                    beforeSend: function(){
                        $("#loader-wrapper").show();
                        $('.btn-simpan').prop("disabled",true);
                    },
                    complete: function(){
                        $("#loader-wrapper").hide();
                        $('.btn-simpan').prop("disabled",false);
                    },
                    success: function(data){
                        $('#images_preview').attr('src',`${data.url_location}/${data.filename}`).attr('class','img img-thumbnail');
                        $('#images_sizes').html(data.sizes);
                        $('.txtfiledokumentasi').
                            css({"color": "peru", "border": "2px solid blue"}).val(data.filename);
                    },
                    error: errorHandler = function(e) {
                        alert("Something went wrong!");
                        console.log(e);
                    },
                });
            }
    })
}(jQuery, window, document));
//Upload



(function ($, window, document) {
    sendData(`${Laravel.serverUrl}/backend/user/notifikasi`,'','GET',function(a) {
        let data = a.data;
        $('span.notif_count').html(0);
        $('.header_notifikasi').html(a.message);
        data.map(function(v,i){
            if (v.jenis == 'order') {
                $( "<a class=\"dropdown-item\">" )
                    .append("<i class='fa fa-users text-green'>")
                        .append(v.message)
                
                .appendTo( $( ".menu_notifikasi" ) );
            }
            $('a.dropdown-item')
            $('.menu_notifikasi')
        });
        
    });

}(jQuery, window, document));
//Form
(function ($, window, document) {
    if ($('.start_at').length > 0) {
        $('.start_at').datetimepicker({
            //defaultDate: new Date(),

            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm",

        });
    }
    if ($('.end_at').length > 0) {
        $('.end_at').datetimepicker({
            //defaultDate: new Date(),
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm",
        });
    }


    $('input.numberonly').on('keypress keydown', function (e) {
        return numeralsOnly(e);
    });
    $('input.letteronly').on('keypress', function (e) {
        return lettersOnly(e);
    });
    var checkall = 'th.check-all';
    $(checkall).on('change', function () {
        console.log(this);
        var $this = $(this),
            index = $this.index() + 1,
            checkbox = $this.find('input[type="checkbox"]'),
            table = $this.parents('table');
        // Make sure to affect only the correct checkbox column
        table.find('tbody > tr > td:nth-child(' + index + ') input[type="checkbox"]')
            .prop('checked', checkbox[0].checked);
    });

    loadProvinsi();

    if ($('#kode_prov').val() != null) {
        loadKabupaten($('#kode_prov').val());
    }
    provinsi.on('change', function () {
        loadKabupaten($(this).val());
    });
    if ($('#kode_kab').val() != null) {
        loadKecamatan($('#kode_kab').val());
    }
    kabkota.on('change', function () {
        loadKecamatan($(this).val());
    });
    if ($('#kode_kec').val() != null) {
        loadDesa($('#kode_kec').val());
    }
    kecamatan.on('change', function () {
        loadDesa($(this).val());
    });

    $('#checkmap').click(function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                $('input[name=latitude]').val(position.coords.latitude);
                $('input[name=longitude]').val(position.coords.longitude);
            }, function () {

            });
        } else {
            // Browser doesn't support Geolocation
            alert('Browser anda tidak mendukung Geolocation');

        }
    });

    $('#dari_km').on('change focusout',function(){
        $('#sampai_km').attr('data-max',parseInt($(this).val())+100);
        $('#sampai_km').attr('data-min',parseInt($(this).val()));
    });
    $('#sampai_km').on('change keyup',function(){
        let i = $(this);
        if(parseInt(i.val())>parseInt(i.attr('data-max'))){
            i.val(i.attr('data-max'));
        }else if(parseInt(i.val())<parseInt(i.attr('data-min'))){
            alert('Data Sampai KM tidak boleh lebih kecil')
        }

    });

    $('input#ptk_baik_persentase').on('keyup change paste', function (e) {
        var v = $(this).val();
        if (v > 100) v = 100;
        $(this).val(v);
        var persen = (v / 100);
        var result = persen * $('input#panjang').val();
        $('input#ptk_baik_km').val(result.toFixed(2));
    });

    $('input#ptk_baik_km').on('keyup change paste', function (e) {
        var v = $(this).val();
        var panjang = $('input#panjang').val();
        if (v > panjang) v = panjang;
        $(this).val(v)
        var km = ((v / panjang) * 100);
        var result = Math.round(km);

        $('input#ptk_baik_persentase').val(result);
    });

    //Sedang
    $('input#ptk_sedang_persentase').on('keyup change paste', function (e) {
        var v = $(this).val();
        if (v > 100) v = 100;
        $(this).val(v);
        var persen = (v / 100);
        var result = persen * $('input#panjang').val();
        $('input#ptk_sedang_km').val(result.toFixed(2));
    });

    $('input#ptk_sedang_km').on('keyup change paste', function (e) {
        var v = $(this).val();
        var panjang = $('input#panjang').val();
        if (v > panjang) v = panjang;
        $(this).val(v)
        var km = ((v / panjang) * 100);
        var result = Math.round(km);

        $('input#ptk_sedang_persentase').val(result);
    });

    //Rusak Ringan
    $('input#ptk_rusakringan_persentase').on('keyup change paste', function (e) {
        var v = $(this).val();
        if (v > 100) v = 100;
        $(this).val(v);
        var persen = (v / 100);
        var result = persen * $('input#panjang').val();
        $('input#ptk_rusakringan_km').val(result.toFixed(2));
    });

    $('input#ptk_rusakringan_km').on('keyup change paste', function (e) {
        var v = $(this).val();
        var panjang = $('input#panjang').val();
        if (v > panjang) v = panjang;
        $(this).val(v)
        var km = ((v / panjang) * 100);
        var result = Math.round(km);

        $('input#ptk_rusakringan_persentase').val(result);
    });

    //Rusak Berat
    $('input#ptk_rusakberat_persentase').on('keyup change paste', function (e) {
        var v = $(this).val();
        if (v > 100) v = 100;
        $(this).val(v);
        var persen = (v / 100);
        var result = persen * $('input#panjang').val();
        $('input#ptk_rusakberat_km').val(result.toFixed(2));
    });

    $('input#ptk_rusakberat_km').on('keyup change paste', function (e) {
        var v = $(this).val();
        var panjang = $('input#panjang').val();
        if (v > panjang) v = panjang;
        $(this).val(v)
        var km = ((v / panjang) * 100);
        var result = Math.round(km);

        $('input#ptk_rusakberat_persentase').val(result);
    });



    //==========================================================
    $('form#formSKJ input#nilai_sdi').on('keyup change paste', function (e) {
        var sampai = $('input#sampai_km').val();
        var dari = $('input#dari_km').val();
        var nilai_iri = $('input#nilai_iri').val();
        var nilai_sdi = $('input#nilai_sdi').val();
        getSdiIri(sampai, dari, nilai_sdi, nilai_iri);
    });

    $('form#formSKJ input#nilai_iri').on('keyup change paste', function (e) {

        var sampai = $('input#sampai_km').val();
        var dari = $('input#dari_km').val();
        var nilai_iri = $('input#nilai_iri').val();
        var nilai_sdi = $('input#nilai_sdi').val();
        console.log(sampai, dari, nilai_sdi, nilai_iri);
        getSdiIri(sampai, dari, nilai_sdi, nilai_iri);
    });

    $('form#formSKJ input.kl_jml_lubang').on('keyup change paste', function (e) {
        let $checked = $(this).filter(function() {
            return $(this).prop('checked');
        });
        let retak_luas = $('input.retak_luas:checked').val();
        let retak_lebar = $('input.retak_lebar:checked').val();
        let jumlah_lubang = $checked.val();
        let bekasroda = $('input.kl_bekas_roda:checked').val();
        getSdi(retak_luas, retak_lebar, jumlah_lubang, bekasroda);
        
        getSdiIri($('input#sampai_km').val(), $('input#dari_km').val(), $('input#nilai_sdi').val(), $('input#nilai_iri').val());
    });
    $('form#formSKJ input.kl_bekas_roda').on('keyup change paste', function (e) {
        let $checked = $(this).filter(function() {
            return $(this).prop('checked');
        });
        let retak_luas = $('input.retak_luas:checked').val();
        let retak_lebar = $('input.retak_lebar:checked').val();
        let jumlah_lubang = $('input.kl_jml_lubang:checked').val();
        let bekasroda = $checked.val();
        getSdi(retak_luas, retak_lebar, jumlah_lubang, bekasroda);
        getSdiIri($('input#sampai_km').val(), $('input#dari_km').val(), $('input#nilai_sdi').val(), $('input#nilai_iri').val())
    });


    
    String.prototype.replaces = function(search, replacement) {
        var str = this;
        if (str.indexOf(search) === -1) { return str; }
        return (str.replace(search, replacement)).replaces(search, replacement);
    }
    var format = function(num) {
        var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
        if (str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for(var j = 0, len = str.length; j < len; j++) {
            if (str[j] != ",") {
                output.push(str[j]);
                if (i%3 == 0 && j < (len - 1)) { output.push(","); }
                i++;
            }
        }
        formatted = output.reverse().join("");
        return(formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    };

    $('.currency, .number').on('keyup', function() {
        $(this).val(format($(this).val()));
    });
    


}(jQuery, window, document));