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
				<tbody>
					@foreach($employees as $employee)
						<tr>
							<td>{{$employee->employee_no}}</td>
							<td>{{$employee->first_name}} {{$employee->last_name}}</td>
							<td>{{casualLeave($employee->id)}}</td>
							<td> </td>
														
						</tr>
					@endforeach
					
				</tbody>
			</table>
		</div>
		
	</div>
</div>


<script>
$(document).ready(function() {
	
            $('#myTable').DataTable({
                stateSave: false,
        		"order": false,
        		
        			
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                             columns: [ 0, 1]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                             columns: [ 0, 1]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                             columns: [ 0, 1]
                        }
                    },
                ],
               
         //        scrollY:        "400px",
      			// scrollX:        true,
        		scrollCollapse: true,
        		paging:         false,
        		fixedColumns:   {
            		leftColumns: 1,
            		rightColumns:2
        		}
            });
            
        });


</script>

@stop