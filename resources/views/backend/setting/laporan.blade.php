@extends('layouts.limitless.main')

@section('content-admin')
<div class="row">
    
        <div class="col-md-6">
          
            <div class="card card-info">
                <div class="card-header with-border">
                <h3 class="card-title">Laporan</h3>
                </div>
                <form class="form-horizontal" method="post" action="{{ route('backend.laporan.index')}}">
                    {{csrf_field()}}
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kecamatan" class="col-sm-2 control-label">Kecamatan</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="kecamatan" name="kecamatan">
                                    <option value="0">----</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan" class="col-sm-2 control-label">Akses Jalan</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="akses_jalan" name="akses_jalan">
                                    <option value="0">----</option>
                                    <option value="N">Nasional</option>
                                    <option value="K">Kabupaten</option>
                                    <option value="P">Provinsi</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                
                    <div class="card-footer">
                        <button type="submit" class="btn btn-default">Batal</button>
                        <button type="submit" class="btn btn-info pull-right">Proses</button>
                    </div>
                
                </form>
          </div>
         
        </div>
        <!-- <div class="col-md-6">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Bordered Table</h3>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div> -->
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">Data Jalan</h3>
                </div>
                <div class="card-body">
                    @if(isset($data))
                    <table class="table table-bordered">
                            <tr>
                                <th>No Ruas</th>
                                <th>Ruas</th>
                                <th>Panjang</th>
                                <th>Lebar</th>
                                <th>Kecamatan</th>
                                <th>Baik</th>
                                <th>Sesang</th>
                                <th>Rusak Ringan</th>
                                <th>Rusak Berat</th>
                            </tr>
                        <tbody>
                        @foreach($data as $k => $v)
                            <tr>
                                <td>{{$v->no_ruas}}</td>
                                <td>{{$v->nama_ruas}}</td>
                                <td>{{$v->panjang}}</td>
                                <td>{{$v->lebar}}</td>
                                <td>{{$v->nama_kecamatan}}</td>
                                <td>{{$v->ptk_baik_km}}</td>
                                <td>{{$v->ptk_sedang_km}}</td>
                                <td>{{$v->ptk_rusakringan_km}}</td>
                                <td>{{$v->ptk_rusakberat_km}}</td>
                            </tr>
                        @endforeach
                        
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('backend.laporan.exportexcel')}}">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAASrSURBVGhD7VptiBVVGN4sg6LSSsssELe9M8uSaNkf+46MEEH84Srkes/Z3VpIsEL8EQTdfgjWr6iwSOpH/aju3Xtm/NhUNBKMCALtR5AVFURapGCFSd+uz3nnPePcM2eWvbs7d9ZlH3jYmfO+58z7zPl6z9xtyxMLQjHbD8UDnhJP+4F8xwvEF6VAPM7myQl/Z9/8khIr/UA8i4AHfSW/R/DDNr2g9wmuUjCG2y7pCMq3ekF5LYLdhuD2eYH82Q44i4UIWfrGwMyS6l3cGUiBYF/2lTiM4H93BTha5i6kq7bxKj+Ud3lKbkSwOzA8jiD4v13BjIe5CcFEfBeBf4mH/Gc/NA8aIfh7D+67x0MvFKs7qr1zSQgKnA/Mi0YIrods21iIjjiBhebqi14IEVNiSgjRw3RayFg55YToh+M+vRIpcRb71SmXLaaSIf4m2yxOSBaQ4vyG/esHvnWCM4pkm9NCxsyWCkF68g82GZ/rxcBDPnD6K/HV/Ycql7EbQednTl8z2ZV8E+195+D/aO9fR/kFKvmr1eaIPTJEESXg1cXdDr9hry7XsQuBziGBOOn0NUJaumrV5SMUWQJIKg9Zfkd1as9mAkRst3xiFiMkkMd0Kk/RMTqVfDjpg3PJCjYROgY33I7yzAT0ghDxPl7KaZuRnx5eaZshfP602hzFZFfiSYowATzoM7Z9zEUR0DOYqJ+m2kjQCMlC5JPHqoWJ5e0emMNtEHT6rG2lUN7LRQS8rcdS9S0WJ0RTide4jQiVygysLHv4jtAZbLg+a4InWawQPeZDuYjbIXRV+6/jSwJ643VHvRSNkFIolpTqvcttst8vLpsh7O+Z9jSbEaLf0kcUsQP+YN+deoK66tk0QnBdYNJYLz9IkVuALUj5ZrB4IUp+e0t1zRUUuQVM/oecdRwsVoiS5/T+QVEzOvauv4YvCfBrGLdZjIWEQmI4vpAm/OiTk8sW0V7im+mRtylaRnttYBZWsuf5lqC/NEYBOOvHNEKyEPnksmqJk6l9JJCbwZ+W7h64kosI8H8qXb+RhQmBUw/XJ+ghxSc42MQzXByh1n0pHvK53UaShQhBoPu5bgzsF1tjO/KeRUOPXssmAjbGZXjQuWQ7SRoh+iXgvuagPhacdZQneQxMtpktBEH+4e3qWUjRMW7b1X+jLm/wxdthcwyU7WjwSdAIwXWLVi0lt1BUCeBNvWr76bfXqdbfxC4ESld4+KX8WylEf8DW452iYkQ/JeDk6PBP5WIA9pZ+l2+rewRjUBxMEiK+cfgRI4GN/ujRD11zxQjRX//1/LIZ+YkfXTZD+LxktTnyZM+DRkgWIp/8st8J45QT0lUT85CSt9skHyVOuGyG9pGhUCG4Lij7nSBOCxmB00LGwwkXosRflFng5njKmCNjIZkHqyaIZbhUL9+n2yPon3j1CZAyUiWrWN6+huOoPiY0SyOkZdAphP4Ah67bhHX9LZ1/ZeZaTbDlQlzoqnVfjp67AwH1Yfd9BQI/gbgzdrAjcVIIcaJSmYG03eN/snkRwg5gWGZ+cZy8QjJQqpZv9lV5FYQ9BwEBRNK/PV10QlzQn1nbVc8NfDuBaGs7D6q1v+TjuoCtAAAAAElFTkSuQmCC">
                            </a>
                            <a href="{{ route('backend.laporan.getpdf')}}">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAHySURBVFhH7ZbbKwRRHMf3v/PiDbmlpKSENynlTaEUi0TuHlZISXJ7cMk1u/bmssIuFqtFdpd2Wct8zTmd0paHk/mdycN86lfT78yc36c55zdnbBaqyGndBVXktu9tiWnpYBNTwOYp7T3QyCUpBQ/v0/SSlIKXcY1eklqQXFKFIKmkKkEySZWCJJKqBVkYkqQUlAlRVh72kFlYggz7UghF3S7cx99FRh7lgi9vGeR3OjG4dg3H9q3IyqNcMJHKoMDuxOhGGI6dfyjIKOt1o3rEj52zZ5GRxxTBtrlzXiie/BAZeUwRnN6744X+gimCw+thXuhfdjGjvM+N8c0btM9fiIw8ygU9V3E0TJxA08Ab5TTyKkbkUC7YOBmAKxTj10c3L/rb9PBPjyxKBS8fkqga8uHzS0MwmsTq8SPyOpy8aNN0AM2zZ7zDp/QmiiZ+35/KBNkJwu5jwU6S2lE/updDmNmPoKTngOdX/A/YPX/GgH7KVA54xZPZkAtGYm9oFd89FvvBGFLpTzH6w4I3iuIeF2p08bqxQ9Q7jsVINqSC3usECrtc6FgM8uMt/JQSI7+T0ZeeNU3g7hXpzJfIZkMqWNHvwZIvyvfd+smTyBqDVLBFX1r2W7WoLx8VpIIqsASNYgkaxRI0yp8FzQxR1oIYm+0b8FMSGzPfRJgAAAAASUVORK5CYII=">
                            </a>
                        </div>

                    </div>
                    @endif
                </div>
            </div>
        </div>
</div>
@endsection


@section('script-end')
@parent
    <script type="text/javascript" src="{{ asset('plugins/select2/dist/js/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('/plugins/bootbox/js/bootbox.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/rm.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            loadKecamatan(3271);
            //Date picker
            // $('#daritgl').datepicker({
            //     autoclose: true
            // });
            // $('#sampaitgl').datepicker({
            //     autoclose: true
            // })
        });
    </script>

@endsection