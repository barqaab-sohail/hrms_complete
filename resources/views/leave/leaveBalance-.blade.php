@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Employees Leave Balance</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Employees Leave Balance</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Casual Leave</th>
					<th>Annual Leave</th>
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
            order: [[ 5, 'desc' ]],
            ajax: {
            url: "{{ route('leaveBalance.create') }}",
            },
            columns: [
               {data: 'employee_no', name: 'employee_no'},
               {data: 'full_name', name: 'full_name'},
               {data: 'casual_leave', name: 'casual_leave'},
               {data: 'annaul_leave', name: 'annaul_leave'},
            ]
        });
    });


</script>

@stop