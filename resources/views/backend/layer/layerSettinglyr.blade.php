@extends('templates.adminlte.main')
@section('content-admin')

<div class="panel panel-default">
  <div class="panel-heading">{{ $title }}</div>
  <div class="panel-body"> 
      <div class="row">
      <div class="col-sm-12">
      <form>
        <div class="form-group">
          <label for="layerurl">URL</label>
          <input type="text" class="form-control" id="layerurl" value="{{ $layers->layerurl }}" placeholder="Layer URL" disabled="disabled">
          <input type="text" class="form-control" id="layer" value="{{ $layers->layer }}" placeholder="Layer" disabled="disabled">
        </div>
        <div id="content"></div>
        <div class="form-group">
            <ul id="list-group" class="list-group">
              
            </ul>
            <ul class="list-group">
              
              @if(isset($field))
                
                @foreach($field as $key => $d)
                  
                  <li class="list-group-item">{{ $d->name }} 
                    
                    <a class="btn btn-primary btn-xs" 
                    href="{{ route('backend.layer.infopopup',array($layers->id,$key,$layers->kodelayer)) }}">
                      <i class="fa fa-pencil-square-o"></i>
                    </a>
                    
                  </li>
                @endforeach
              @else
                <div class="row">
                  <div class="col-sm-12">
                    <div class="alert alert-dismissible alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                          Tolong Update Layer Untuk Mendapatkan Info.<br>
                          Untuk Update Silakan Link berikut.
                          <a href="{{ route('backend.layer.edit',array($id)) }}" class="alert-link">Link</a>
                    </div>
                  </div>
                </div>
                
              @endif
            </ul>
        </div>
      </form>
      </div>
      </div>
  </div>
</div>
@stop

@section('script-end')
@parent
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
@endsection