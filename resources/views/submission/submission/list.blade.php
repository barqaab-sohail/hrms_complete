@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of Submissions</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	

		<h4 class="card-title">List of Submissions</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					<th>Submission Name</th>
					<th>Submission Type</th>
					<th>Submission No</th>
					<th class="text-center"style="width:5%">Edit</th> 
					@role('Super Admin')
					<th class="text-center"style="width:5%">Delete</th>
					@endrole
				</tr>
				</thead>
				<tbody>
					
					@if(Auth::User()->can('sub edit power'))
							@php
							$submissions = $submissions->merge($powerSubmissions);
							@endphp
					@endif	
					@if(Auth::User()->can('sub edit water'))
							@php
							$submissions = $submissions->merge($waterSubmissions);
							@endphp				
					@endif



					@foreach($submissions as $submission)
					

						<tr>
							<td>{{$submission->project_name}}</td>
							<td>{{$submission->sub_type_id}}</td>
							<td>{{$submission->submission_no}}</td>
														
							<td class="text-center">
								<a class="btn btn-success btn-sm" href="{{route('submission.edit',$submission->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
							</td>
							@role('Super Admin')
							<td class="text-center">
								
								 <form  id="formDeleteSubmission{{$submission->id}}" action="{{route('submission.destroy',$submission->id)}}" method="POST">
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