@extends('templates.adminlte.main')

@section('content-admin')
  <?php
    $id = '';
    $judul='';
    $deskripsi='';
    $upload = '';
    $kategori = '';
    
    if (session('aksi') == 'edit') {
      $id = $dokumen->id;
      $judul = $dokumen->judul;
      $deskripsi= $dokumen->deskripsi;
      $upload = $dokumen->upload;
      $kategori = $dokumen->kategori;
    }
  ?>
<form role="form" method="post" action="{{ route('backend.dokumen.post')}}" enctype='multipart/form-data'>
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"> Dokumen</h3>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" class="form-control" id="id" value="{{$id}}">
                <div class="form-group">
                  <label for="judul_info">Judul Dokumen</label>
                  <input type="text" name="judul_dokumen" class="form-control" id="judul_info" value="{{$judul}}">
                </div>
                <div class="form-group">
                  <label for="deskripsi">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rowspan="3" colspan="4">{{$deskripsi}}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="tanggal">File Upload</label>
                    <div class="input-group margin controlupload">
                        <input type="text" class="form-control txtfoto" readonly="readonly" name="foto" id="foto" value="{{ $upload }}" placeholder="Gambar">
                        <span class="input-group-btn">
                          <input type="file" name="users_file" class="hidden d-none file fileupload"
                            data-url="{{ route('backend.dokumen.upload',['path'=>'storage/uploads/dokumen'])}}"
                            data-path=""
                            data-type="single"
                            data-id="{{$id}}"
                            multiple>
                          <button type="button" class="btn btn-info btn-flat formUpload">Foto!</button>
                        </span>
                    </div>
                </div>

                
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-flat btn-simpan">Simpan</button>
            </div>
        </div>
    </div>
  </div>
        
</form>
@endsection
@section('style-head')
@parent

@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
<script type="text/javascript">
  $(function () {
      //Date picker
      $('#tanggal').datepicker({
          autoclose: true
      });
      CKEDITOR.replace( 'deskripsi',{
          toolbar : [
                    { name: 'basicstyles', items : [ 'Bold','Italic' ] },
                    { name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
                    { name: 'tools', items : [ 'Maximize','-' ] }
          ]
      });
  });
</script>

@endsection
