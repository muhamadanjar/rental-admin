@extends('layouts.limitless.main')
@section('title')
<h5>
    <i class="icon-arrow-left52 mr-2"></i>
    <span class="font-weight-semibold">Manage Permission</span> 
    <small class="d-block text-muted">Pengaturan hak akses setiap modul</small>
</h5>
@endsection
@section('content-admin')

    <div class="row">
        <div class="col-md-4">
            <ul class="list-group list-group-bordered bg-slate-600">
                <li class="list-group-item font-weight-semibold">Hak Akses</li>
                @foreach($groups as $group)
                <li class="list-group-item {{ ($selectedGroup && $group->id == $selectedGroup->id)?'active':'' }}">
                    <a href="{{ route('backend.setting.permissions.index', [$group->id]) }}" style="color: white;">
                        {{ $group->name }}
                    </a>
                </li>
                @endforeach
            </ul>
            
        </div>
        @if($selectedGroup)
        <div class="col-md-8">
            <div class="card card-default">
                
                <div class="card-header header-elements-inline">
                    <h5 class="card-title"><strong>{{ $selectedGroup->name }}</strong> <small>bisa melakukan hal-hal di bawah ini:</small></h5>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item" data-action="collapse"></a>
		                		<a class="list-icons-item" data-action="reload"></a>
		                		<a class="list-icons-item" data-action="remove"></a>
		                	</div>
	                	</div>
					</div>
                <div class="card-body">

                <form action="{{ route('backend.setting.permissions.assign') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="id" value="{{ $selectedGroup->id }}"/>
                    @foreach($resources as $resource)
                      <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="resources[]" value="{{$resource->id}}" @if(in_array($resource->id, $groupResources)) checked @endif/>{{$resource->name}}
                        </label>
                      </div>
                    @endforeach

                    <button class="btn btn-primary">Simpan</button>
                </form>

                </div>
            </div>
        </div>
        @endif
    </div>

@stop
