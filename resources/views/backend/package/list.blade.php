@extends($ctemplates.'.main')
@section('content-admin')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Paket {{ $type->type }}</h3>
                    <div class="text-right">
                        <div class="btn-group">
                            <a href="{{ route('backend.packages.create',['type'=>$type->id]) }}" class=" btn btn-sm btn-primary ">
                                <i class="fa fa-plus"></i> Tambah</a>
                            <a href="{{ route('backend.packages.index') }}" class=" btn btn-sm btn-default ">
                                    <i class="fa fa-mail-reply"></i> Kembail</a>
                        </div>
                        
                    </div>
                </div>
                <div class="box-body">
                    <table class="display table table-bordered">
                        <thead>
                            <tr>
                                <td>Paket</td>
                                <td>Harga</td>
                                <td>Harga (Km)</td>
                                <td>Harga (Jam)</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $item)
                            <?php $active_class = ($item->status == 1 ? "fa-check":"fa-close") ?>
                            <tr>
                                <td>{{$item->rp_name}}</td>
                                <td>{{number_format($item->rp_total_price)}}</td>
                                <td>{{$item->rp_miles_km}}</td>
                                <td>{{$item->rp_hour}}</td>
                                <td width="50" class="text-center">{!!($item->status == 1 ? "<i class='fa fa-check text-green'></i>":"<i class='fa fa-close text-red'></i>")!!}</td>
                                <td width="50" class="text-center">
                                    <div class="btn-group">
                                        <button data-toggle="dropdown" class="btn btn-xs btn-icon dropdown-toggle" type="button"><i class="icon-cog4"></i><span class="caret"></span></button>
                                            <ul class="dropdown-menu icons-right dropdown-menu-right">
                                                <li><a class="dropdown-item" href="{{ route('backend.packages.edit', ['id' => $item->rp_id,'type'=>$type->id]) }}"><i class="fa fa-edit"></i> Ubah</a></li>
                                                <li class=""
                                                    data-form="#frmaktif-{{$item->rp_id}}" 
                                                    data-title="Aktif {{ $item->rp_id }}" 
                                                    data-message="Apa anda yakin mengaktifkan/menonaktifkan {{ $item->rp_name }} ?">
                                                    <a class= "dropdown-item formConfirm" href="#"><i class="fa {{$active_class}}"></i> Aktif / Non Aktif</a>
                                                </li>
                                                <li class=""
                                                    data-form="#frmdelete-{{$item->rp_id}}" 
                                                    data-title="Aktif {{ $item->rp_id }}" 
                                                    data-message="Apa anda yakin menghapus {{ $item->rp_name }} ?">
                                                    <a class= "dropdown-item formConfirm" href="#"><i class="fa fa-trash"></i> Hapus</a>
                                                </li>
                                                <form action="{{ route('backend.packages.destroy', array($item->rp_id) ) }}" method="post" style="display:none" id="frmdelete-{{$item->rp_id}}">
                                                    <input type="hidden" name="_method" value="delete">
                                                        {{ @csrf_field() }}
                                                </form>
                                                <form action="{{ route('backend.packages.na', array($item->rp_id) ) }}" method="post" style="display:none" id="frmaktif-{{$item->rp_id}}">
                                                    
                                                    {{ @csrf_field() }}
                                                </form>
                                            </ul>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script-end')
    @parent
    <script type="text/javascript" src="{{ url('js/rm.js')}}"></script>
@endsection