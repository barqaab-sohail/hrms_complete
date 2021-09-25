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
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					<th>Sr. No.</th>
					<th>Employee Name</th>
					<th>Designation/Position</th>
					<th>Category</th>
					<th>CNIC</th>
					<th>HOD</th>
					<th>Date of Joining</th>
					<th>Employee Id</th>
					<th>Mobile</th>
					
					<th class="text-center"style="width:5%">Edit</th> 
					<th class="text-center"style="width:5%">Delete</th>
				
				</tr>
				</thead>
				<tbody>
					@php
						$serial = 1;
					@endphp

					@foreach($employees as $employee)
						<tr>
							<td>{{$serial++}}</td>
							<td>{{$employee->first_name}} {{$employee->last_name}}</td>
							<td>{{$employee->employeeDesignation->last()->name??''}}</td>
							<td>{{$employee->employeeCategory->last()->name??''}}</td>
							<td>{{$employee->cnic}}</td>
							<td>{{employeeFullName($employee->hod->hr_manager_id??'')}}</td>
							<td>{{$employee->employeeAppointment->formatted_joining_date??''}}</td>
							<td>{{$employee->employee_no}}</td>
							<td>{{$employee->hrContactMobile->mobile??''}}</td>
							
							
							<td class="text-center">
								<a class="btn btn-success btn-sm" href="{{route('employee.edit',$employee->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
							</td>
							<td class="text-center">
								 @role('Super Admin')
								 <form  id="formDeleteContact{{$employee->id}}" action="{{route('employee.destroy',$employee->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
								 </form>
								 @endrole
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
	
            $('#myTable').DataTable({

                stateSave: false,
        
                dom: 'Blfrtip',
				columnDefs: [ { type: 'date', 'targets': [5] } ],
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                           columns: [ 0, 1, 2,3,4,5,6,7,8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8]
                        }
                    },
                ],
               
                scrollY:        "300px",
      			scrollX:        true,
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