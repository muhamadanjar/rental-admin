@extends('layouts.full')

@section('style-head')
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endsection

@section('body-class','hold-transition lockscreen')


@section('content')
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="#"><b>Login</b>LTE</a>
    </div>
    <!-- User name -->
    <div class="lockscreen-name">John Doe</div>
    
    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
        <img src="../../dist/img/user1-128x128.jpg" alt="User Image">
        </div>
        <!-- /.lockscreen-image -->
    
        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials">
        <div class="input-group">
            <input type="password" class="form-control" placeholder="password">
    
            <div class="input-group-append">
            <button type="button" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
            </div>
        </div>
        </form>
        <!-- /.lockscreen credentials -->
    
    </div>
    <!-- /.lockscreen-item -->
    <div class="help-block text-center">
        Enter your password to retrieve your session
    </div>
    <div class="text-center">
        <a href="login.html">Or sign in as a different user</a>
    </div>
    <div class="lockscreen-footer text-center">
        Copyright &copy; 2014-2019 <b><a href="http://adminlte.io" class="text-black">AdminLTE.io</a></b><br>
        All rights reserved
    </div>  
</div>
@endsection

@section('script-end')
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js')}}"></script>
@endsection