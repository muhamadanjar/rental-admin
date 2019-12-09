@extends($ctemplates.'.main')
@section('content-admin')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Type Mobil</h3>
                </div>
                <div class="box-body">
                    <table class="display table table-bordered">
                        <thead>
                            <tr>
                                <td>Type</td>
                                <td>Description</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($type as $item)
                            <tr>
                                <td>{{$item->type}}&nbsp;<span class="label label-success">{{$item->count}}</span></td>
                                <td>{{$item->description}}</td>
                                <td>{!!($item->status == 1 ? "<i class='fa fa-check text-green'></i>":"<i class='fa fa-cross text-red'></i>")!!}</td>
                                <td><a href="{{ route('backend.packages.list',array($item->typeid)) }}" class="btn btn-xs btn-primary">Tambah Paket</a>
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