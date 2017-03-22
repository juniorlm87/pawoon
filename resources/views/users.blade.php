<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Prog Test</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="page-header">
	  <h1>Manage Data Users <small>you can manage your user data here</small></h1>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">User Data</div>
			  <div class="panel-body">
				<form class="form-horizontal" id="frm" autocomplete="off">
					  <div class="form-group">
						<label for="uuid" class="col-sm-2 control-label">ID</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="uuid" name="uuid" placeholder="ID" readonly>
						</div>
					  </div>
					  <div class="form-group">
						<label for="nama" class="col-sm-2 control-label">Nama</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
						</div>
					  </div>
					  <div class="form-group">
						<label for="alamat" class="col-sm-2 control-label">Alamat</label>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat">
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						  <button type="submit" id="btnSubmit" class="btn btn-default">Submit</button>
						  <button type="reset" class="btn btn-danger">Clear</button>
						</div>
					  </div>
				</form>
			  </div>
			</div>
			</div>
		</div>	
		
		<div class="row">
			<div class="col-xs-12 col-md-8">
				<div class="panel panel-default">
					  <div class="panel-heading">Master Data Users</div>
					  <div class="panel-body">
							<table class="table table-striped" id="datagrid">
							  <thead>
								<tr>
									<th width="15%"></th>
									<th width="15%">ID</th>
									<th width="25%">Nama</th>
									<th width="40%">Alamat</th>
								</tr>
							  </thead>
							  <tbody>
								
							  </tbody>
							</table>
					  </div>
					</div>	
			</div>
		</div>
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script>
	$(function(){
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$.ajaxSetup({
		  headers: {
			  'X-CSRF-TOKEN': CSRF_TOKEN
		  }
		});
		loadGrid();
		$("#frm").submit(function(){
			var formdata=$("#frm").serializeArray();
			
			$("#btnSubmit").prop("disabled",true);
			$.ajax({
				url: '/userpro/'+($('#uuid').val()!="" ? "update":"insert"),
				type: 'POST',
				data: formdata,
				dataType: 'HTML',
				success: function (data) {
					$("#btnSubmit").prop("disabled",false);
					var obj=$.parseJSON(data);
					if(obj.data.error==0){
						document.getElementById('frm').reset();
						loadGrid();
					}
					alert(obj.data.message);
				}
			});
			return false;
		});
	});
	function loadGrid(){
		$.ajax({
				url: '/userpro',
				type: 'GET',
				dataType: 'JSON',
				success: function (data) {
					var obj=data;
					var datagrid=$("#datagrid").find("tbody");
					var str="";
					console.log(obj.data);
					
					$.each(obj.data,function(i,v){
						str+='<tr>';
						str+='<td><a href="javascript:void(0);" onclick="modify(this)"  class="btn btn-default btn-xs">Edit</a> <a href="javascript:void(0);" onclick="delete_data(this)" class="btn btn-danger btn-xs">Delete</a></td>';
						str+='<td>'+v.uuid+'</td>';
						str+='<td>'+v.nama+'</td>';
						str+='<td>'+v.alamat+'</td>';
						str+='</tr>';
					});
					
					datagrid.html(str);
					
				}
			});
	}
	function modify(e){
		var tr=$(e).closest('tr').find('td:nth-child(2)').text();
		$.ajax({
				url: '/userpro/show/'+tr,
				type: 'GET',
				dataType: 'JSON',
				success: function (data) {
					var obj=data;
					$.each(obj.data,function(i,v){
						$("#uuid").val(v.uuid);
						$("#nama").val(v.nama);
						$("#alamat").val(v.alamat);
					});
				}
		});
	}
	function delete_data(e){
		var ask=confirm('Are you sure want to remove this item?');
		if(ask==true){
		var tr=$(e).closest('tr').find('td:nth-child(2)').text();
		$.ajax({
				url: '/userpro/delete/'+tr,
				type: 'GET',
				dataType: 'JSON',
				success: function (data) {
					var obj=data;
					alert(obj.data.message);
					loadGrid();
				}
		});
		}
	}
	</script>
  </body>
</html>