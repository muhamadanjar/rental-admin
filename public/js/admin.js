let worker = new Worker(`${Laravel.serverUrl}/js/webworker.js`);
(function ($, window, document) {
    axios.get(`${Laravel.serverUrl}/backend/user/notifikasi`).then((a)=>{
        let res = a.data;
        let data = res.data;
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

    })

}(jQuery, window, document));