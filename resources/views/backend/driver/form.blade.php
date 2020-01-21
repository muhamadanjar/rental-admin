@extends('templates.adminlte.main')

@section('content-admin')
  <?php
    $id = $userId+1;
    $no_plat= old('no_plat');
    $merk=old('merk');
    $type=old('type');
    $warna=old('warna');
    $harga=old('harga');
    $tahun=old('tahun');
    $fotoMobil = '';
    $harga_perjam =old('harga_perjam');
    $deposit=0;
    $driverName='';
    $username='';
    $password='';
    $email='';
    $no_telp='';
    $nip='';
    $alamat='';
    $status = 0;
    $mobilName = '';
    $tahun = '';
    
    if (session('aksi') == 'edit') {
        $id = $driver->id;
        $no_plat= $mobil->no_plat;
        $merk= $mobil->merk;
        $type = $mobil->type;
        $warna = $mobil->warna;
        $harga = $mobil->harga;
        $harga_perjam = $mobil->harga_perjam;
        $mobilName = $mobil->name;
        $tahun = $mobil->tahun;
        $fotoMobil = $mobil->foto;
        $status = $driver->isactived;

        $driverName = $driver->name;
        $username = $driver->username;
        $password = $driver->password;
        $email = $driver->email;
        
        if (isset($anggota)) {
            $nip = $anggota->no_ktp;
            $no_telp = $anggota->no_telp;
            // $alamat = $driver->profile->address;
            $deposit=0;
        }else{
            $nip = "";
            $no_telp = "0";
            $alamat = "0";
            $deposit= "0";
        }
        
    }
    
   
  ?>
<form role="form" method="post" action="{{ route('driver.post')}}" enctype='multipart/form-data'>
  {{ csrf_field() }}
  <div class="row">
    
    <div class="col-md-12">
        <div class="card card-default color-palette-card">
            <div class="card-header with-border">
            <h3 class="card-title"><i class="fa fa-tag"></i> Form Pengemudi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                            <h3 class="card-title"> Data Mobil</h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" class="form-control" id="id" value="{{$id}}">
                                
                                <div class="form-group {{ $errors->has('no_plat') ? ' has-error' : '' }}">
                                    <label for="no_plat">No Plat</label>
                                    <input type="text" name="no_plat" class="form-control" id="no_plat" value="{{$no_plat}}">
                                </div>
                                {{-- <div class="form-group {{ $errors->has('merk') ? ' has-error' : '' }}">
                                    <label for="merk">Type Mobil</label>
                                    <select name="merk" class="select2 form-control" id="merk">
                                        <option value="--">----</option>
    
                                        @foreach($merkselect as $k => $v)
                                        <option value="{{$v->merk}}">{{$v->merk}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="merk" class="form-control" id="merk" value="{{$merk}}">
                                </div> --}}
                                <div class="form-group {{ $errors->has('mobil_name') ? ' has-error' : '' }}">
                                    <label for="mobil_name">Nama Mobil</label>
                                    <input type="text" name="mobil_name" class="form-control" id="mobil_name" value="{{$mobilName}}">
                                </div>
                                {{-- <div class="form-group">
                                    <label for="type">Type Mobil</label>
                                    
                                    <select name="type" class="select2 form-control" id="type">
                                        <option value="--">----</option>
    
                                        @foreach($typeselect as $k => $v)
                                        <option value="{{$v->type}}">{{$v->type}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group {{ $errors->has('warna') ? ' has-error' : '' }}">
                                    <label for="warna">Warna Mobil</label>
                                    <input type="text" name="warna" class="form-control" id="warna" value="{{$warna}}">
                                </div>
                                <div class="form-group {{ $errors->has('tahun') ? ' has-error' : '' }}">
                                    <label for="tahun">Tahun Mobil</label>
                                    <input type="text" name="tahun" class="form-control" id="tahun" value="{{$tahun}}">
                                </div>
                                
                                <!--<div class="form-group {{ $errors->has('harga') ? ' has-error' : '' }}">
                                    <label for="harga">Harga Mobil (per Kilo)</label>
                                    <input type="text" name="harga" class="form-control" id="harga" value="{{$harga}}">
                                </div>
                                <div class="form-group {{ $errors->has('harga_perjam') ? ' has-error' : '' }}">
                                    <label for="harga_perjam">Harga Mobil (per Jam)</label>
                                    <input type="text" name="harga_perjam" class="form-control" id="harga_perjam" value="{{$harga_perjam}}">
                                </div>-->
    
                            </div>
    
                            <div class="card-footer">
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-success">
                            <div class="card-header">
                            <h3 class="card-title"> Data Personal</h3>
                            </div>
                            <div class="card-body">
                                
                                <div class="form-group has-feedback {{ $errors->has('status') ? ' has-error' : '' }}">
                                    <select name="status" id="status" class="form-control">
                                        <option value="0" @if($status == '0') selected @endif>Non Aktif</option>
                                        <option value="1" @if($status == '1') selected @endif>Aktif</option>
                                    </select>
                                    
                                </div>
    
                                <div class="form-group has-feedback {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="Full name" name="name" value="{{$driverName}}">
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="Username" name="username" {{ session('aksi') == 'edit' ? 'readonly':'' }} value="{{$username}}">
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{$email}}">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" class="form-control" placeholder="Password" name="password" value="{{$password}}">
                                    <span class="fa fa-lock form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation" value="{{$password}}">
                                    <span class="fa fa-lock form-control-feedback"></span>
                                </div>
                                @if(session('aksi') == 'edit')	
                                    <input type="hidden" class="form-control" name="oldpassword" value="{{ $password }}">			
                                @endif
                                {{-- <div class="form-group has-feedback {{ $errors->has('jk') ? ' has-error' : '' }}">
                                    <select name="jk" id="jk">
                                        <option value=""></option>
                                        <option value="L">Laki - laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <span class="fa fa-mobile-phone form-control-feedback"></span>
                                </div> --}}
                                {{-- <div class="form-group has-feedback {{ $errors->has('provinsi') ? ' has-error' : '' }}">
                                    <select name="provinsi" id="provinsi">
                                        <option value=""></option>
                                        
                                    </select>
                                    <span class="fa fa-mobile-phone form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('kabupaten') ? ' has-error' : '' }}">
                                    <select name="kabupaten" id="kabupaten">
                                        <option value=""></option>
                                        
                                    </select>
                                    <span class="fa fa-mobile-phone form-control-feedback"></span>
                                </div> --}}
    
                                <div class="form-group has-feedback {{ $errors->has('no_telp') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="No Telp/Handphone" name="no_telp" value="{{$no_telp}}">
                                    <span class="fa fa-mobile-phone form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('nip') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="No KTP" name="nip" value="{{$nip}}">
                                    <span class="fa fa-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('alamat') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="Alamat" name="alamat" value="{{$alamat}}">
                                    <span class="fa fa-home form-control-feedback"></span>
                                </div>
                                {{-- <div class="form-group has-feedback {{ $errors->has('kode_pos') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="Kode Pos" name="kode_pos" value="{{$kode_pos}}">
                                    <span class="fa fa-home form-control-feedback"></span>
                                </div> --}}
                                
    
                                <div class="form-group has-feedback">
                                    <b>Saldo:</b> {{$deposit}}
                                    <input type="hidden" class="form-control" placeholder="Deposit" name="deposit_temp" value="{{$deposit}}">
                                </div>
    
                            </div>
    
                            <div class="card-footer">
                                <div class="form-group has-feedback {{ $errors->has('deposit') ? ' has-error' : '' }}">
                                    <input type="text" class="form-control" placeholder="Deposit" name="deposit">
                                    <span class="fa fa-money form-control-feedback"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-flat">Simpan</button>
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
        
<script src="{{ asset('plugins/select2/dist/js/select2.full.min.js')}}"></script>
<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    });
</script>
<script src="{{ asset('/js/rm.js')}}"></script>
@endsection
