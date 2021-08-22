@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Active User List</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
		
		
	</ol>
@stop
@section('content')
	<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
			<h4 class="card-title">Total Login Users = {{$totalActiveUsers}}</h4>
			<button id="logoutAll" class="float-right btn btn-danger">Logout All Active Users</button>
			<div class="table-responsive m-t-40">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr>
						<th>Employee Name</th>
						<th>Email</th>
						<th>CNIC</th>
						<th>Father Name</th>
						<th>Last Login Time</th>
						<th>Last Login IP</th>
						<th class="text-center">Log Out</th>
						
					</tr>
					</thead>
					<tbody>
						@foreach($activeUsers as $employee)
							<tr>
								<td>{{$employee->first_name}} {{$employee->last_name}}</td>
								<td>{{$employee->email}}</td>
								<td>{{$employee->cnic}}</td>
								<td>{{$employee->father_name}}</td>
								<td>{{$employee->last_login_at}}</td>
								<td>{{$employee->last_login_ip}}</td>
								<td class="text-center">

								<form  id="logoutUser{{$employee->userId}}" action="{{route('logout.all')}}/{{$employee->userId}}" method="GET">
								 
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-sign-out-alt"></i></button>
								</form>
									
																		
								</td>
								
																						
							</tr>
						@endforeach
					
					 
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<script>
        $(document).ready(function() {
        	$('#logoutAll').click(function (e){
        		
        		e.preventDefault();
        		var url ="{{route('logout.all')}}";
        		getAjaxMessage(url);

        	});

        	
        	$('form[id^=logoutUser]').submit(function(e){
        		        		
        		var url = $(this).attr('action');
        		e.preventDefault();
        		getAjaxMessage(url);

        	});


            $('#myTable').DataTable({
                stateSave: false,
                scrollY:        "300px",
      			scrollX:        true,
        		scrollCollapse: true,
        		paging:         false,
                dom: 'Blfrti',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    },
                ]
            });
        });
 
	</script>
	
@stop