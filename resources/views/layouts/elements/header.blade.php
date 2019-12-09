@include('layouts.elements.userbar')

<nav class="navbar navbar-header" role="navigation">
    <div class="container-fluid">
        <div class="hidden-xs col-sm-6 col-xs-9 col-md-6 col-lg-8">
            <div class="clock hidden animated fadeInDownBig">
                <div class="block-date hidden-sm hidden-xs"><span class="day" id="clock-day"></span><span class="date" id="clock-date"></span></div>
                <div class="time" id="clock-time"></div>
            </div>
        </div>
        <!--<div class="col-sm-3 col-xs-12 col-md-4 col-lg-4 text-right">
            <h5 class="hidden-sm hidden-xs">Dinas Trasmigrasi dan Tenaga Kerja</h1>
            <h5 class="hidden-sm hidden-xs">Provinsi Sulawesi Tenggara</h1>
        </div>-->
        <div class="hidden-sm hidden-xs col-sm-4 col-xs-12 col-md-6 col-lg-4 text-right">
            <img class="img-responsive" src="{{ asset('image/logo/logo-wide.png') }}" alt=""/>    
            
        </div>
        <div class="hidden-lg hidden-md col-sm-6 col-xs-12 col-md-3 col-lg-4 text-right">
            <img class="logo img-responsive" src="{{ asset('image/logo/logo-kemendes-mini.png') }}" alt=""/>
            <img class="logo img-responsive" src="{{ asset('image/logo/logo-sultra-mini.png') }}" alt=""/>
        </div>
    </div>
</nav>

<div class="clearfix"></div>

@section('script-end')
    @parent
    <script>
        function clockTick()
        {
            $('#clock-time').html(moment().format("HH:mm:ss"));
            $('#clock-day').html(moment().format("dddd"));
            $('#clock-date').html(moment().format("D MMMM YYYY"));
        }

        $(function(){

            // remove animation class for next visit
            var key = 'animation-' + moment().format("D");

            if($.cookie(key) != undefined) {
                $('.clock').removeClass('animated');
                $('.clock').removeClass('hidden');
            }
            $.cookie(key, true, {expires: 30});

            clockTick();
            setInterval(clockTick, 1000);
            setTimeout(function(){
                $('.clock').removeClass('hidden');
            }, 1000);

        });
    </script>
@stop