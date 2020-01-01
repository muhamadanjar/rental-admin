@extends('templates.adminlte.main')
@section('content-admin')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reviews</h3>
                </div>
                <div class="card-body">
                    <table class="table display table_reviews">
                        <thead>
                            <tr>
                                <th>Ride Number</th>
                                <th>Driver</th>
                                <th>User</th>
                                <th>Tanggal</th>
                                <th>Rating</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($review as $item)
                                <tr>
                                    <td>
                                        @isset($item->driver)
                                        {{$item->driver->mobil[0]->no_plat}}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($item->driver)
                                        {{$item->driver->name }}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($item->rider)
                                            
                                        {{$item->rider->name }}
                                        @endisset
                                    </td>
                                    <td>{{$item->date}}</td>
                                    <td><span class="badge badge-primary">{{$item->rate}}</span></td>
                                    <td>{{$item->description}}</td>
                                </tr>    
                            @empty
                                <tr >
                                    <td class="text-center" colspan="6">Data tidak ada</td>
                                </tr>    
                            @endforelse
                            
                            
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection