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
					<th>Id</th>
					<th>Employee Name</th>
					<th>Designation</th>
					<th>Father Name</th>
					<th>Date of Birth</th>
					<th>CNIC</th>
					<th>Date of Joining</th>
					<th>Diviion</th>
					<th>Degree Name</th>
					<th>Education Year</th>
					<th>PEC No</th>
					<th>Expiry Date</th>
					<th>Employee No</th>
					<th>Mobile</th>
					<th>LandLine Number</th>
					<th>Emergency Number</th>
					<th>Blood Group</th>
					<th class="text-center"style="width:5%">Edit</th> 
				
				</tr>
				</thead>
				<tbody>
					@foreach($employees as $employee)
						<tr>
							<td>{{$employee->id}}</td>
							<td>{{$employee->first_name}} {{$employee->last_name}}</td>
							<td>{{$employee->employeeDesignation->last()->name??''}}</td>
							<td>{{$employee->father_name}}</td>
							<td>{{$employee->date_of_birth}}</td>
							<td>{{$employee->cnic}}</td>
							<td>{{date('d-M-Y', strtotime($employee->employeeAppointment->joining_date??''))}}</td>
							<td>{{$employee->hrDepartment->name??''}}</td>

							
							<td>
							{{$employee->degreeAbove12->implode('degree_name',' + ')??''
							}}</td>
							<td>{{$employee->degreeYearAbove12->implode('to',' + ')??''}}</td>
							<td>{{$employee->hrMembership->membership_no??''}}</td>
							<td>{{$employee->hrMembership->expiry??''}}</td>
							<td>{{$employee->employee_no??''}}</td>
							<td>{{$employee->hrContactMobile->mobile??''}}</td>
							<td>{{$employee->hrContactLandline->landline??''}}</td>
							<td>{{$employee->hrEmergency->mobile??''}}</td>
							<td>{{$employee->hrBloodGroup->name??''}}</td>
							<td class="text-center">
								<a class="btn btn-success btn-sm" href="{{route('employee.edit',$employee->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
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
                "ordering": false,
        
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8,9,10,11,12,13,14]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8,9,10,11,12,13,14]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8,9,10,11,12,13,14]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7,8,9,10,11,12,13,14]
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