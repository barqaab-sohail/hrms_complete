@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">List of Employees</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Designation/Position</th>
					<th>Project/Office</th>
					<th>Date of Birth</th>
					<th>Status</th>
					<th>CNIC</th>
					<th>Date of Joining</th>
					<th>Mobile</th>

					@can('hr edit documentation')
					<th class="text-center"style="width:5%">Edit</th>
					@endcan

					@role('Super Admin')
					<th class="text-center"style="width:5%">Delete</th>
					@endrole
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
  		"aaSorting": [],
	  	ajax: {
	   	url: "{{ route('employee.index') }}",
	  	},
	  	columns: [
		   {data: 'employee_no', name: 'employee_no'},
		   {data: 'full_name', name: 'full_name'},
		   {data: 'designation', name: 'designation'},
		   {data: 'project', name: 'project'},
		   {data: 'date_of_birth', name: 'date_of_birth'},
		   {data: 'hr_status_id', name: 'status', orderable: true},
		   {data: 'cnic', name: 'cnic'},
		   {data: 'date_of_joining', name: 'date_of_joining'},
		   {data: 'mobile', name: 'mobile'},
		   @can('hr edit documentation')
		   {data: 'edit',name: 'edit', orderable: false, searchable: false },
		   @endcan
		   @role('Super Admin')
		   {data: 'delete',name: 'delete', orderable: false, searchable: false }
		   @endrole
	  	]
 	});





});


</script>

@stop