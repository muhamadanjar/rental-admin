@foreach($submenus as $submenu)
        <ul class="nav nav-treeview">
        <li class="nav-item"><a href="{{ url($submenu->url) }}" class="nav-link {{set_active_menu('backend.tanah.index')}}"><i class="far fa-circle nav-icon"></i>{{$submenu->title}}</a></li> 
	    @if(count($submenu->children))
            @include('templates.adminlte.submenu',['submenus' => $submenu->children])
        @endif
        </ul> 
@endforeach