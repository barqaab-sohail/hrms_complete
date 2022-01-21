@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">List of Leaves</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Designation/Position</th>
					<th>Leave From</th>
					<th>Leave To</th>
					<th>Total Days</th>
					<th>Leave Status</th>
					<th class="text-center"style="width:5%">Edit</th>
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
  		order: [[ 5, 'desc' ]],
	  	ajax: {
	   	url: "{{ route('leave.index') }}",
	  	},
	  	columns: [
		   {data: 'employee_no', name: 'employee_no'},
		   {data: 'full_name', name: 'full_name'},
		   {data: 'designation', name: 'designation'},
		   {data: 'from', name: 'from'},
		   {data: 'to', name: 'to'},
		   {data: 'days', name: 'days'},
		   {data: 'status', name: 'status'},
		   {data: 'edit',name: 'edit', orderable: false, searchable: false },
		   @role('Super Admin')
		   {data: 'delete',name: 'delete', orderable: false, searchable: false }
		   @endrole
	  	]
 	});





});


</script>

@stop