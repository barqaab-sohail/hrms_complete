@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">Leave Balance</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Casual Leave</th>
					<th>Earned Leave</th>
					<th>Accumulative E/L</th>
				</tr>
				</thead>
			</table>
		</div>
		
	</div>
</div>


<script>



$(document).ready(function() {

	$(function () {
      	$.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    	});

	    var table = $('#myTable').DataTable({
	  		processing: true,
	  		serverSide: true,
		  	ajax: {
		   	url: "{{ route('leaveBalance.index') }}",
		  	},
		  	columns: [
			   {data: 'employee_no', name: 'employee_no'},
			   {data: 'full_name', name: 'full_name'},
			   {data: 'casual_leave', name: 'casual_leave'},
			   {data: 'annual_leave', name: 'annual_leave'},
			   {data: 'accumulative_annual_leave', name: 'accumulative_annual_leave'}
		  	]
 		});

 		
	   	

    	
     
  	}); // end function

  	


}); //End document ready function



</script>

@stop