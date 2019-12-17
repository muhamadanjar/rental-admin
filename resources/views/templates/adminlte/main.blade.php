@extends('layouts.full')

@section('body-class','hold-transition sidebar-mini layout-navbar-fixed layout-footer-fixed')
    

@section('style-head')
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper">
    @include('templates.adminlte.navbar')
    @include('templates.adminlte.sidebar')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@yield('title')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @yield('breadcrumb')
                </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            @yield('content-admin')
        </section>

        
    </div>
    @include('templates.adminlte.footer')
    @include('layouts.elements.modal')
</div>
@endsection


@section('script-end')
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
    <script src="{{ asset('js/admin.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{-- <script src="{{ asset('js/pages/dashboard.js')}}"></script> --}}
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ asset('js/demo.js')}}"></script> --}}

    <script type="text/javascript">
        function sendData(address, data, typeSend, callback) {
            $.ajax({
                url: address,
                type: typeSend,
                timeout: 60000,
                dataType: 'json',
                data: data,
                cache: false,
                complete: function(jqXHR) {
                    if (typeof jqXHR.responseJSON === 'undefined') { jqXHR.responseJSON = { code: jqXHR.status, message: jqXHR.statusText }; }
                    if (typeof callback === 'function') { callback(jqXHR.responseJSON); }
                }
            });
        }
        function sendDataMultipart(address, data, typeSend, callback) {
            $.ajax({
                url: address,
                processData: false,
                type: typeSend,
                timeout: 60000,
                dataType: 'json',
                data: data,
                contentType: false,
                cache: false,
                complete: function(jqXHR) {
                    if (typeof jqXHR.responseJSON === 'undefined') { jqXHR.responseJSON = { code: jqXHR.status, message: jqXHR.statusText }; }
                    if (typeof callback === 'function') { callback(jqXHR.responseJSON); }
                }
            });
        }
        function initSidebar() {
				var CURRENT_URL = window.location.href.split('#')[0].split('?')[0];
				var a = $('.sidebar-menu').find('a[href="' + CURRENT_URL + '"]').parent('li').attr('class');
				if (typeof a === 'string') {
					a = a.replace('modul ', '');
					a = a.replace('menu-hide', '');
					loadSidebar(a);
				}
				$('.sidebar-menu').find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('active');
				$('.sidebar-menu').find('a').filter(function() { return this.href === CURRENT_URL; }).parent('li').parents('li').addClass('active menu-open');
				$('.sidebar-menu').find('a').filter(function() { return this.href === CURRENT_URL; }).parent('li').parents('ul').show();
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
        
        $(document).ready(function() {
            initSidebar();
            $('.currency, .number').on('keyup', function() {
                $(this).val(format($(this).val()));
            });
            
        });
    </script>
@endsection