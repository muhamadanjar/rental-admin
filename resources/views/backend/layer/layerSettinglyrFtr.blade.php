@extends('templates.adminlte.main')
@section('content-admin')

<?php
$title_m = '';
$caption_m = '';
$url_m = '';
$link_m = '';
$check;
$lengthkey_ = 0;
$lengthmedia = 0;
if (!empty($identify)) {
  $title = $identify->title;
  $description = $identify->description;
  
  $keydata = json_decode($identify->keydata, true);
  $media = json_decode($identify->media, true);
  if (isset($keydata)) {
    $lengthkey_ = count($keydata);
    $encode_key = json_decode(json_encode($keydata));
  }
  if (isset($media)) {
    $lengthmedia = count($media);
  }
}
$url_service = ($layers->tipelayer == 'dynamic' ? $layers->layerurl . '/' . $idx : $layers->layerurl);
?>
<div class="panel panel-default">
  <div class="panel-heading">{{ $judul }}</div>
  <div class="panel-body"> 
      <div class="row">
        <div class="col-sm-12">
          <form method="post" enctype="multipart/form-data" role="form" accept-charset="UTF-8">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="form-group">
              <label for="layerurl">Judul</label>
              <input type="hidden" class="form-control" disabled="disabled" id="layerurl" value="{{ $url_service }}" />
              <input type="text" name="title" value="{{ $title }}" class="form-control" />
              <input type="hidden" name="layerid" class="form-control" value="{{ $idx }}" />
              <input type="hidden" name="layer_id" class="form-control" value="{{ $layers->id }}" />
              <input type="hidden" name="namalayer" class="form-control" value="{{ $layers->kodelayer }}" />
            </div>
            <div class="form-group">
                <label for="display">Display</label>
                <select name="display" class="form-control" id="display-keyvalue">
                  <option value="-">---</option>
                  <option value="keyvalue" @if($identify->display == 'keyvalue') selected @endif>Key Value</option>
                  <option value="custom" @if($identify->display == 'custom') selected @endif>Custom</option>
                  
                </select>
            </div>
            <div class="form-group" id="deskripsi">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control">{{ $description }}</textarea>
            </div>
            <div class="form-group" id="field-list">
                <label for="title">Field</label>
                <div id="dif">
                    
                </div>

                <table class="table table-bordered">
                  <tr>
                    <th class="check-all"><input type="checkbox" name="checkall" id="checkall"  /></th>
                    <th>Nama</th>
                    <th>Alias</th>
                    <th>Tipe</th>
                  </tr>

                  @if($lengthkey_ > 0)
                      @foreach($field->fields as $key => $a)
                      <?php $b = ($layers->tipelayer == 'dynamic' ? $a->name : $a->name); ?>
                        @if($encode_key[$key]->fieldName == $b )
                          <?php $c = $encode_key[$key]->label; ?>
                        @else
                          <?php $c = $b ?>
                        @endif
                        @php $tipefield = (isset($encode_key[$key]->fieldType) ? $encode_key[$key]->fieldType:null); @endphp
                      <tr>
                        <td><input @if($encode_key[$key]->visible) checked @endif type="checkbox" class="checkbox" name="visible[{{ $key }}]" value="{{ $b }}" /></td>
                        <td>{{ $a->name }}<input type="hidden" name="name_field[]" value="{{ $b }}"></td>
                        <td><input type="text" class="form-control" name="label_field[]" value="{{ $c }}"></td>
                        <td><select class="form-control" name="type_field[]">
                          <option @if($tipefield=='text') selected="selected" @endif value="text">Text</option>
                          <option @if($tipefield=='image') selected="selected" @endif value="image">Image</option>
                          <option @if($tipefield=='sertifikat') selected="selected" @endif value="sertifikat">Sertifikat</option>
                          <option @if($tipefield=='video') selected="selected" @endif value="video">Video</option>
                          </select>
                        </td>
                      </tr>
                      @endforeach
                  @else
                      @foreach($field->fields as $key => $a)
                      <?php $b = ($layers->tipelayer == 'dynamic' ? $a->name : $a->name); ?>
                      <tr>
                        <td><input type="checkbox" class="checkbox" name="visible[{{ $key }}]" value="{{ $b }}" /></td>
                        <td>{{ $a->name }}<input type="hidden" name="name_field[]" value="{{ $b }}"></td>
                        <td><input type="text" class="form-control" name="label_field[]" value="{{ $b }}"></td>
                        <td><select class="form-control" name="type_field[]">
                          <option value="text">Text</option>
                          <option value="image">Image</option>
                          <option value="sertifikat">Sertifikat</option>
                          <option value="video">Video</option>
                          </select>
                        </td>
                      </tr>
                      @endforeach
                  @endif
                </table>
            </div>
            <!-- Media -->
            <div class="form-group">
                <label for="showattachments">Show Attachments</label>  
                <input type="radio" name="showattachments" value="true" @if($identify->showattachments == true) checked="checked" @endif> Yes
                <input type="radio" name="showattachments" value="false" @if($identify->showattachments == false) checked="checked" @endif> No
                
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-sm" name="button" value="Submit">  
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