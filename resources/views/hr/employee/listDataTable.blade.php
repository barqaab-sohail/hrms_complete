@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of Employees</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">List of Employees</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<!-- <th>Designation/Position</th>
					<th>Project/Office</th> -->
					<th>Date of Birth</th>
					<th>Status</th>
					<th>CNIC</th>
					<!-- <th>Date of Joining</th>
					<th>Mobile</th> -->
					
					<th class="text-center"style="width:5%">Edit</th> 
					<th class="text-center"style="width:5%">Delete</th>
				
				</tr>
				</thead>
			</table>
		</div>
		
	</div>
</div>


<script>
$(document).ready(function() {
	$('#myTable').DataTable({
  		processing: true,
  		serverSide: true,
	  	ajax: {
	   	url: "{{ route('employee.index') }}",
	  	},
	  	columns: [
		   {data: 'employee_no', name: 'employee_no'},
		   {data: 'first_name', name: 'first_name'},
		   {data: 'date_of_birth', name: 'date_of_birth'},
		   {data: 'hr_status_id', name: 'status'},
		   {data: 'cnic', name: 'cnic'},
		   {data: 'action',name: 'action',
		    orderable: false
		   }
	  	]
 	});





});


</script>

@stop