<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="@yield('html-class')">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ Config::get('app.name') }} @yield('head_title')</title>
        <meta name="author" content="Muhamad Anjar">
        <meta name="title" content="{{ Config::get('app.meta')}}" />
        <meta name="description" content="{{ Config::get('app.meta')}}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="_token" content="{!! csrf_token() !!}"/>
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        
        <link rel="icon" type="image/png" href="{{ url('/favicon.ico')}}" sizes="16x16">
        <!--/ END META SECTION -->

        <!-- START STYLESHEETS -->
        @yield('style-head')
        
        <!-- Theme stylesheet : optional -->
        @yield('style-theme')
        <!--/ Theme stylesheet : optional -->

        <!-- modernizr script -->
        @yield('script-head')
        
        <!-- END STYLESHEETS -->
        <!-- Scripts -->
        <script>
            window.Laravel = @php echo json_encode([
                'csrfToken' => csrf_token(),
                'apiUrl' => url('/api'),
                'serverUrl' => url('/'),
                'geoserverUrl' => config('app.geoserver_url')
            ]);
            @endphp
            
        </script>
    </head>
    <!--/ END Head -->

    <!-- START Body -->
    <body class="@yield('body-class')">
        @yield('body')
        
        @yield('script-body')
        
        @yield('script-end')        
        
    </body>
    <!--/ END Body -->
</html>
