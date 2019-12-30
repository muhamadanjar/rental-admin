@extends('templates.adminlte.main')

@section('content-admin')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Detil User {{$item->name}}</h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active" href="#tab_user" data-toggle="tab">User</a></li>
                        <li class="nav-item"><a class="nav-link" href="#tab_disbursment" data-toggle="tab">Disbursment</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_user">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>DETIL USER</h5>
                                    <div id="griduser"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="tab_disbursment">
                            <form id="form-view-disbursment">
                                <div class="form-group">
                                    <label for="">Dari Tanggal</label>
                                    <input type="text" id="sdate" name="sdate" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Sampai Tanggal</label>
                                    <input type="text" id="edate" name="edate" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                                </div>
                            </form>


                            <div id="gridHistory"></div>
                        </div>

                        
                    </div>
                </div>
            </div>    
        </div>    
    </div>    
@endsection

@section('style-head')
@parent
<link rel="stylesheet" href="{{ asset('plugins/dx/css/dx.common.css')}}" />
<link rel="stylesheet" href="{{ asset('plugins/dx/css/dx.light.css')}}" />
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('script-end')
@parent
<script src="{{ asset('plugins/dx/js/dx.all.js') }}"></script>
<script type="text/javascript" src="{{ asset('daterangepicker/daterangepicker.js')}}"></script>

<script>
    $(function(){
        let master = JSON.parse('{!! $item !!}');
        let metas = JSON.parse('{!! $item->user_metas !!}');
        let account = JSON.parse('{!! $item->saldo !!}');
        let data = {
            master:[master],
            metas:metas,
            account:[account],
            activity:[]
        }
        console.log(data);
        
        
        LoadUser(data.master, data.metas, data.account, data.activity);

        $('#sdate').daterangepicker({
			singleDatePicker: true,
    		showDropdowns: true,
			locale: {
				format: 'YYYY-MM-DD'
			}
		});
        $('#edate').daterangepicker({
			singleDatePicker: true,
    		showDropdowns: true,
			locale: {
				format: 'YYYY-MM-DD'
			}
        });
        
        $('#form-view-disbursment').submit(function(e){
            e.preventDefault();
            let formData = $(this).serializeArray();
            

            $.ajax({
                type: 'POST',
                url:'{{route('report.disbursment')}}',
                data: formData,
                dataType: "json",
                success: function (data){
                    console.log(data);
                    loadPembayaran(data);
                }
            });
            
        });

        function LoadUser(master, metas, account, activity){
            $("#griduser").dxDataGrid({
                dataSource: master,
                keyExpr: "nofas",
                showColumnLines: true,
                showRowLines: true,
                showBorders: true,
                columnResizingMode: "nextColumn",
                columnMinWidth: 100,
                columnAutoWidth: true,
                groupPanel: { 
                    visible: true 
                },
                grouping: {
                    autoExpandAll: false
                },
                paging: {
                    pageSize: 10
                },
                pager: {
                    showPageSizeSelector: true,
                    allowedPageSizes: [5, 10, 20],
                    showInfo: true
                },
                editing: {
                    refreshMode: "reshape",
                    mode: "popup",
                    allowUpdating: true,
                },
                allowColumnReordering: true,
                rowAlternationEnabled: true,
                "export":{
                    enabled:true,
                    fileName:"Data Pengajuan Pickme"
                },
                filterRow: {
                    visible: false,
                    applyFilter: "auto"
                },
                headerFilter: {
                    visible: false
                },
                onRowUpdating: function(e) {
                    swal({
                        title: "Apakah anda yakin ingin melanjutkan proses ini ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((value) => {
                        if (value) 
                        {
                            swal("Masukan password keamanan anda: ", {
                                content: {
                                    element: "input",
                                    attributes: {
                                    placeholder: "Type your password",
                                    type: "password",
                                    autocomplete:"off"
                                    },
                                }
                            })
                            .then((password) => {
                                
                                e.newData.id = e.oldData.id;
                                e.newData.password = password;
                                e.newData.type = 'edit';

                                $.ajax({
                                    type: 'POST',
                                    
                                    data: e.newData,
                                    dataType: "json",
                                    success: function (data) {
                                        swal ( "Yeay" ,  data.message,  data.status );
                                        $("#form-view-userpiko").submit();
                                        return false;
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                        return false;
                                    }
                                });

                                return false;

                            });
                        }

                    });

                },
                columns: [
                    {
                        dataField: "email",
                        caption:"Email",
                        alignment: "left",
                        width:200,
                    },
                    {
                        dataField: "phonenumber",
                        caption:"Phone Number",
                        alignment: "left",
                        width:250,
                    },
                    {
                        dataField: "isactived",
                        caption:"Status User",
                        alignment: "left",
                        width:170,
                        cellTemplate: function(container, options) {
                            if(options.data.isactived !== undefined) {
                                if(options.data.isactived == 0) {
                                    $('<span class="btn btn-default btn-sm tooltip-info">DEFAULT</span>').appendTo(container);
                                }
                                else  if(options.data.isactived == 1) {
                                    $('<span class="btn btn-success btn-sm tooltip-info">AKTIF</span>').appendTo(container);
                                }
                                else  if(options.data.isactived == 2) {
                                    $('<span class="btn btn-danger btn-sm tooltip-close">TOLAK</span>').appendTo(container);
                                }
                            }

                        }
                    },
                    

                ],
                masterDetail: {
                    enabled: true,
                    template: function(container, options) { 
                        var pickmeData = options.data;

                        $("<div>")
                            .addClass("master-detail-caption")
                            .html('<h5>DETIL META :</h5><br>')
                            .appendTo(container);

                        $("<div>")
                            .dxDataGrid({
                                columnAutoWidth: true,
                                showBorders: true,
                                filterRow: {
                                    visible: true,
                                    applyFilter: "auto"
                                },
                                headerFilter: {
                                    visible: true
                                },
                                editing: {
                                    refreshMode : "reshape",
                                    mode : "popup",
                                    allowUpdating : true,
                                    allowDeleting : true,
                                    allowAdding : true,
                                },
                                paging: {
                                    pageSize: 10
                                },
                                onRowUpdating: function(e) {

                                    e.newData.id = e.oldData.id;

                                    swal({
                                        title: "Apakah anda yakin ingin melanjutkan proses ini ?",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then((value) => {
                                        if (value) 
                                        {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.newData.id = e.oldData.id;
                                                e.newData.password = password;
                                                e.newData.type = 'edit';

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-meta-crud',
                                                    data: e.newData,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                        }

                                    });

                                },
                                onRowRemoving : function(e) {
                                
                                if (e.cancel == false) {
                                        swal("Masukan password keamanan anda: ", {
                                            content: {
                                                element: "input",
                                                attributes: {
                                                placeholder: "Type your password",
                                                type: "password",
                                                autocomplete:"off"
                                                },
                                            }
                                        })
                                        .then((password) => {
                                            
                                            e.data.type = 'delete';
                                            e.data.password = password;

                                            $.ajax({
                                                type: 'POST',
                                                url: 'users-meta-crud',
                                                data: e.data,
                                                dataType: "json",
                                                success: function (data) {
                                                    swal ( "Yeay" ,  data.message,  data.status );
                                                    $("#form-view-userpiko").submit();
                                                    return false;
                                                },
                                                error: function(jqXHR, textStatus, errorThrown) {
                                                    swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                    return false;
                                                }
                                            });

                                            return false;

                                        });
                                }

                                },
                                onRowInserting: function(e) {
                                
                                swal({
                                        title: "Apakah anda yakin ingin melanjutkan proses ini ?",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then((value) => {
                                        if (value) 
                                        {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.data.type = 'insert';
                                                e.data.password = password;
                                                e.data.meta_users = pickmeData.id;

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-meta-crud',
                                                    data: e.data,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                        }

                                    });

                                },
                                columns: [
                                    {
                                        dataField: "id",
                                        caption:"ID",
                                        alignment: "left",
                                        allowEditing: false,
                                        width:100,
                                    },
                                    {
                                        dataField: "meta_key",
                                        caption:"KEY",
                                        alignment: "left",
                                        allowEditing: true,
                                        width:400,
                                    },
                                    {
                                        dataField: "meta_value",
                                        caption:"VALUE",
                                        alignment: "left",
                                        allowEditing: true,
                                        width:400,
                                    },
                                    {
                                        dataField: "meta_users",
                                        caption:"USER ID",
                                        alignment: "center",
                                        allowEditing: false,
                                        width:100,
                                    },
                                ],
                                dataSource: new DevExpress.data.DataSource({
                                    store: new DevExpress.data.ArrayStore({
                                        key: "id",
                                        data: metas
                                    }),
                                    // filter: ["nofas", "=", pickmeData.nofas]
                                })
                            }).appendTo(container);

                        $("<div>")
                            .addClass("master-detail-caption")
                            .html('<br><h5>DETIL ACCOUNT :</h5><br>')
                            .appendTo(container);

                        $("<div>")
                            .dxDataGrid({
                                columnAutoWidth: true,
                                showBorders: true,
                                filterRow: {
                                    visible: false,
                                    applyFilter: "auto"
                                },
                                headerFilter: {
                                    visible: false
                                },
                                editing: {
                                    refreshMode: "reshape",
                                    mode: "popup",
                                    allowUpdating: true,
                                    allowDeleting   : true,
                                },
                                paging: {
                                    pageSize: 10
                                },
                                onRowUpdating: function(e) {

                                    swal({
                                        title: "Apakah anda yakin ingin melanjutkan proses ini ?",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then((value) => {
                                        if (value) {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.newData.id = e.oldData.id;
                                                e.newData.password = password;
                                                e.newData.type = 'edit';

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-account-crud',
                                                    data: e.newData,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                        }

                                    });

                                },
                                onRowRemoving : function(e) {
                                
                                    if (e.cancel == false) {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.data.type = 'delete';
                                                e.data.password = password;

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-account-crud',
                                                    data: e.data,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                    }

                                },
                                columns: [
                                    {
                                        dataField: "user_id",
                                        caption:"ID",
                                        alignment: "left",
                                        allowEditing: false,
                                        width:100,
                                    },
                                    {
                                        dataField: "saldo",
                                        caption:"Saldo",
                                        alignment: "left",
                                        allowEditing: false,
                                        width:150,
                                    },
                                    
                                    {
                                        dataField: "no_anggota",
                                        caption:"Anggota",
                                        alignment: "left",
                                        allowEditing: false,
                                        width:150,
                                    },
                                ],
                                dataSource: new DevExpress.data.DataSource({
                                    store: new DevExpress.data.ArrayStore({
                                        key: "id",
                                        data: account
                                    }),
                                    // filter: ["nofas", "=", pickmeData.nofas]
                                })
                            }).appendTo(container);

                        $("<div>")
                            .addClass("master-detail-caption")
                            .html('<br><h5>DETIL ACTIVITY :</h5><br>')
                            .appendTo(container);

                        $("<div>")
                            .dxDataGrid({
                                columnAutoWidth: true,
                                showBorders: true,
                                filterRow: {
                                    visible: false,
                                    applyFilter: "auto"
                                },
                                headerFilter: {
                                    visible: false
                                },
                                editing: {
                                    refreshMode : "reshape",
                                    mode : "popup",
                                    allowUpdating: true,
                                    allowDeleting : true,
                                    allowAdding : true,
                                },
                                paging: {
                                    pageSize: 10
                                },
                                onRowUpdating: function(e) {

                                    swal({
                                        title: "Apakah anda yakin ingin melanjutkan proses ini ?",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then((value) => {
                                        if (value) 
                                        {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.newData.users_id = pickmeData.id;
                                                e.newData.password = password;
                                                e.newData.type = 'edit';

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-activity-crud',
                                                    data: e.newData,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                        }

                                    });

                                },
                                onRowRemoving : function(e) {
                                
                                    if (e.cancel == false) {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.data.type = 'delete';
                                                e.data.password = password;
                                                
                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-activity-crud',
                                                    data: e.data,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                    }

                                },
                                onRowInserting: function(e) {
                                
                                    swal({
                                        title: "Apakah anda yakin ingin melanjutkan proses ini ?",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then((value) => {
                                        if (value) 
                                        {
                                            swal("Masukan password keamanan anda: ", {
                                                content: {
                                                    element: "input",
                                                    attributes: {
                                                    placeholder: "Type your password",
                                                    type: "password",
                                                    autocomplete:"off"
                                                    },
                                                }
                                            })
                                            .then((password) => {
                                                
                                                e.data.type = 'insert';
                                                e.data.password = password;
                                                e.data.users_id = pickmeData.id;

                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'users-activity-crud',
                                                    data: e.data,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        swal ( "Yeay" ,  data.message,  data.status );
                                                        $("#form-view-userpiko").submit();
                                                        return false;
                                                    },
                                                    error: function(jqXHR, textStatus, errorThrown) {
                                                        swal ( "Oops" ,  jqXHR.responseText,  'error' );
                                                        return false;
                                                    }
                                                });

                                                return false;

                                            });
                                        }

                                    });

                                },
                                columns: [
                                    {
                                        dataField: "users_id",
                                        caption:"USER ID",
                                        alignment: "left",
                                        allowEditing: false,
                                        width:100,
                                    },
                                    {
                                        dataField: "users_activity",
                                        caption:"ACTIVITY",
                                        alignment: "left",
                                        allowEditing: true,
                                        width:150,
                                    },
                                ],
                                dataSource: new DevExpress.data.DataSource({
                                    store: new DevExpress.data.ArrayStore({
                                        key: "users_id",
                                        data: activity
                                    }),
                                    // filter: ["nofas", "=", pickmeData.nofas]
                                })
                            }).appendTo(container);

                        $("<div>")
                            .addClass("master-detail-caption")
                            .html('<br><h5>ACTION LIST : </h5><br>\
                                    <div class="btn-group">\
                                            <a class="btn btn-lg btn-success" href="javascript:void(0);" id="'+pickmeData.id+'" onclick="RstUserPickme(this.id)">\
                                            <i class="glyphicon glyphicon-refresh pull-left"></i><span>Reset User<br><small>Perbaharui data </small></span></a> \
                                            <a class="btn btn-lg btn-danger" href="javascript:void(0);" id="'+pickmeData.id+'" onclick="RmUserPiko(this.id)">\
                                        <i class="glyphicon glyphicon-trash pull-left"></i><span>Hapus User <br><small>Menghapus semua data user</small></span></a> \
                                    </div>')
                            .appendTo(container);

                    },
                }
            });
        }
    })
</script>
@endsection

