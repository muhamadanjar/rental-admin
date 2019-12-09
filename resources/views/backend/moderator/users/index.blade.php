@extends('templates.adminlte.main')
@section('style-head')
@parent
<link rel="stylesheet" href="{{ url('/plugins/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{ url('assets/plugins/datatables/css/tabletools.css')}}">
@endsection
@section('content-admin')
    <div class="card card-default">
        <div class="card-header">
            <h6 class="card-title">Manajemen User</h6>
            <div class="card-tools text-right">
                <div class="btn-group pull-right">
                    <a href="{{ route('backend.setting.users.create') }}" class=" btn btn-sm btn-primary">
                    <i class="fa fa-plus ico-user-plus2"></i> Tambah</a>
                </div>
            </div>
            
        </div>
        <table class="table display" id="table_dom">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Username/Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <?php $class_active = ($user->isactived==0) ? 'btn-danger':'' ?>
                <?php $fa_active = ($user->isactived==0) ? 'fa-circle':'fa-circle-o' ?>
                <?php 
                    $currentuser_class = '';
                    $role_status = '';
                    $label_status = '';
                    $verified_status = '';
					if(\Auth::user()->id == $user->id){
						$currentuser_class = 'disabled';
                    }
                    elseif($user->isadmin){
                        $role_status = 'Administrator';
                        $label_status = 'label-info';
                    }else{
                        $role_status = 'User';
                        $label_status = 'label-info';
                    }

                    if($user->isverified){
                        $verified_status = 'Terverifikasi';
                    }
				?>
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="label {{$label_status}}">{{ $role_status }}</span></td>
                    <td><span class="label label-success">{{ $verified_status }}</span></td>
                    <td class="text-center">
                        <div class="btn-group">
					        <button data-toggle="dropdown" class="btn btn-xs {{ $class_active }} btn-icon dropdown-toggle" type="button"><i class="icon-cog4"></i><span class="caret"></span></button>
									<ul class="dropdown-menu icons-right dropdown-menu-right">
										<li><a href="{{ route('backend.setting.users.edit', ['id' => $user->id]) }}"><i class="fa fa-edit"></i> Ubah</a></li>
										<li class="{{$currentuser_class}}" data-form="#frm-{{$user->id}}" 
											data-title="Hapus {{ $user->id }}" 
											data-message="Apa anda yakin menghapus {{ $user->username }} ?">
											<a class="formConfirm {{$currentuser_class}}" href="#"><i class="fa fa-trash"></i> Hapus</a>
										</li>
										<form action="{{ route('backend.setting.users.delete', array($user->id) ) }}" method="post" style="display:none" id="frm-{{$user->id}}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="delete">
										</form>
                                        <li class="{{$currentuser_class}}"
											data-form="#frmaktif-{{$user->id}}" 
											data-title="Aktif {{ $user->id }}" 
											data-message="Apa anda yakin mengaktifkan/menonaktifkan {{ $user->username }} ?">
											<a class= "formConfirm {{$currentuser_class}}" href="#"><i class="fa {{$fa_active}}"></i> Aktif / Non Aktif</a>
										</li>
										<form action="{{ route('backend.setting.users.na', array($user->id) ) }}" method="get" style="display:none" id="frmaktif-{{$user->id}}"></form>
                                        <li><a href="{{ route('backend.setting.users.resetpassword', [$user->id]) }}" class="btn btn-reset-password">Reset Password</a></li>
									</ul>
				        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@stop

@section('script-end')
    @parent
    
    
    <script type="text/javascript" src="{{ url('/plugins/select2/js/select2.js')}}"></script>
    <script type="text/javascript" src="{{ url('/plugins/datatables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('/plugins/bootbox/js/bootbox.js') }}"></script>
    <script>
        $(function(){
            $(document).on('click', '.btn-reset-password', function(e){
                e.preventDefault();
                var btn = $(e.currentTarget);
                console.log(btn);
                bootbox.confirm("Password lama akan dihapus dan password baru akan digenerate secara otomatis oleh sistem. Anda yakin ingin melanjutkan?", function(result) {
                    if(result)
                    {
                        //btn.button('loading');
                        $.ajax({
                            url: btn.attr('href'),
                            type: 'get',
                            dataType: 'json'
                        }).done(function(response){
                            bootbox.alert("Password baru: " + response.password);
                        }).fail(function(){
                            bootbox.alert('Oops, tidak bisa melakukan perubahan password saati ini. Coba lagi beberapa saat atau hubungi admin.');
                        }).always(function(){
                            //btn.button('reset');
                        });
                    }
                });

            });
        });
    </script>
    <script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
@stop