@if(Auth::check())

<?php
    //$caseAlertCount = App::make('App\Cases\RepositoryInterface')->countAlert(Auth::user());
?>
    <div class="navbar-user">
        <div class="container-fluid">
            <div class="col-md-6 text-right">
                <div class="notification">
                    
                    
                </div>
            </div>

            <div class="col-md-6 text-center">
                Login sebagai
                <div class="btn-group">
                    <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <strong>{{ Auth::user()->name }}</strong> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('backend.index') }}"><i class="ion-navicon"></i> Backend</a></li>
                        <li><a href="{{ route('backend.me.profile') }}"><i class="ion-person"></i> Ganti Password</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('gerbang.logout') }}"><i class="fa fa-circle-o-notch"></i> Logout</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endif
