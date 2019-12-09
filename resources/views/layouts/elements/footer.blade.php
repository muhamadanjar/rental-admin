<footer class="text-center text-muted hidden-print">
    <div class="count-box text-center">
											<div class="counting">{{ $shared_hits }}</div>
											<div class="count-title">Hari ini</div>
    </div>										
    <div class="count-box text-center">
											<div class="counting">{{ $shared_pengunjungonline }}</div>
											<div class="count-title">Online</div>
    </div>	
    <a href="{{ (Auth::check())?route('backend.index'):route('gerbang.login') }}" class="">
        <i class="ion-ios-locked"></i>
    </a>
    <a href="{{ route('slide.index') }}" class="">
        <i class="ion-ios-play"></i>
    </a>
    <br/>
    <small>&copy; {{ date('Y') }} Dinas Trasnmigrasi dan Ketenagakerjaan</small>

    <div class="runningtext microsoft marquee">
        <span>  
        @if(isset($pengumuman))
            @foreach($pengumuman as $k => $v)
               {{$v->info}}. ||
            @endforeach

        @endif             
        </span>
        
        </div>
</footer>
