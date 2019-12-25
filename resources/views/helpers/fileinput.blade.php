<div class="form-group">
    <label for="exampleInputFile">{{$label}}</label>
    <div class="input-group">
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="{{$fileId}}">
        <label class="custom-file-label" for="exampleInputFile">{{$browseText}}</label>
      </div>
      <div class="input-group-append">
        <span class="input-group-text" id="">{{$uploadText}}</span>
      </div>
    </div>
</div>