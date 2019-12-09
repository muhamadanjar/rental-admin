@extends('layouts.limitless.main')

@section('breadcrumb')
    @parent
    <span class="trail"><i class="fa fa-angle-right"></i></span>
    <span class="trail">Sinkronasi</span>
@endsection

@section('content-admin')
    <h2 class="page-title">Sinkronasi</h2>

    <form id="form1" method="post" action="dbsync_tonoc_ajax.php">
        <table>
            <tr>
				<td align="center">Property Server</td>
                <td align="Center">NOC Server</td>
            </tr>
            <tr>
                       <td>
                         <table width="400" border="1">
                            <tr>
                              <td>Database Name</td>
                              <td><input type="text" name="prop_db_name" id="prop_db_name" /></td>
                            </tr>
                            <tr>
                              <td>User name</td>
                              <td><input type="text" name="prop_db_username" id="prop_db_username" /></td>
                            </tr>
                            <tr>
                              <td>Password</td>
                              <td><input type="text" name="prop_db_password" id="prop_db_pawssword" /></td>
                            </tr>
                            <tr>
                              <td>Connection String</td>
                              <td><input type="text" name="prop_db_ipaddress" id="prop_db_ipaddress" /></td>
                            </tr>
                            <tr>
                              <td>Sync Property id</td>
                              <td><input type="text" name="prop_sync_id" id="prop_sync_id" /></td>
                            </tr>
                         </table>
                       </td>
                       <td>
                         <table width="400" border="1">
                            <tr>
                              <td>Database Name</td>
                              <td><input type="text" name="noc_db_name" id="noc_db_name" /></td>
                            </tr>
                            <tr>
                              <td>User name</td>
                              <td><input type="text" name="noc_db_username" id="noc_db_username" /></td>
                            </tr>
                            <tr>
                              <td>Password</td>
                              <td><input type="text" name="noc_db_password" id="noc_db_pawssword" /></td>
                            </tr>
                            <tr>
                              <td>Connection String</td>
                              <td><input type="text" name="noc_db_ipaddress" id="noc_db_ipaddress" /></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                         </table>
                       </td>
            </tr>
            <tr>
				<td> <input type="button" id="prop_to_noc" name="prop_to_noc" value="Start Sync to NOC" /></td>
				<td> <input type="button" id="noc_to_prop" name="noc_to_prop" value="Start Sync to Property" /></td>
            </tr>
        </table>
    </form>
    <div id="show_msg"></div>
@endsection
@section('style-head')
@parent
@endsection
@section('script-end')
@parent
<script type="text/javascript" src="{{ url('assets/plugins/selectize/js/selectize.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/jquery-ui/js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/jquery-ui/js/addon/timepicker/jquery-ui-timepicker.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/jquery-ui/js/jquery-ui-touch.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/inputmask/js/inputmask.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/touchspin/js/jquery.bootstrap-touchspin.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/javascript/backend/forms/element.js')}}"></script>

<script type="text/javascript" src="{{ url('assets/plugins/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/datatables/tabletools/js/dataTables.tableTools.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/datatables/js/datatables-bs3.js')}}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript" src="{{ url('js/sikko.js')}}"></script>
<script>
	$("document").ready(function(){
                //alert("this is submit");
		$("#prop_to_noc").click(function(){
            $("#show_msg").html("Loading.... please wait...");
			$.post("dbsync_tonoc_ajax.php", $("#form1").serialize(), function(data){
				$("#show_msg").html(data);
			});
		});
		$("#noc_to_prop").click(function(){
			$("#show_msg").html("Loading.... please wait...");
			$.post("dbsync_toprop_ajax.php", $("#form1").serialize(), function(data){
				$("#show_msg").html(data);
			});
		});
	});
</script>
@endsection
