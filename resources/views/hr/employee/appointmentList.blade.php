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
					<th>Father's Name</th>
					<th>CNIC</th>
					<th>Category</th>
										
					<th class="text-center"style="width:5%">Edit</th> 
					@role('Super Admin')
					<th class="text-center"style="width:5%">Delete</th>
					@endrole
					
				</tr>
				</thead>
				<tbody>
					@foreach($employees as $employee)
						<tr>
							<td>{{$employee->id}}</td>
							<td>{{$employee->first_name}} {{$employee->last_name}}</td>
							<td>{{$employee->father_name}}</td>
							<td>{{$employee->cnic}}</td>
							<td>{{$employee->hrAppointment->category??''}}</td>
							
							
							<td class="text-center">
								<a class="btn btn-info btn-sm" href="{{route('employee.edit',$employee->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
							</td>
							@role('Super Admin')
							<td class="text-center">
								 
								 <form  id="formDeleteContact{{$employee->id}}" action="{{route('employee.destroy',$employee->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
								 </form>
							</td>
							@endrole
														
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
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
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