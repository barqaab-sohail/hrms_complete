@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of Projects</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		@can('Super Admin')
		<div class="container" id='hideDiv'>
   			<h3 align="center">Import Excel File</h3>

			<form method="post" enctype="multipart/form-data" action="{{route('project.import')}}">
			{{ csrf_field() }}
			    <div class="form-group">
				    <table class="table">
					    <tr>
					    	<td width="40%" align="right"><label>Select File for Upload</label></td>
					       	<td width="30">
					        <input type="file" name="select_file" />
					       	</td>
					       	<td width="30%" align="left">
					        <input type="submit" name="upload" class="btn btn-success" value="Upload">
					       	</td>
					    </tr>
					    <tr>
					       	<td width="40%" align="right"></td>
					       	<td width="30"><span class="text-muted">.xls, .xslx Files Only</span></td>
					       	<td width="30%" align="left"></td>
					    </tr>
				    </table>
			    </div>
			</form>
		</div>


		<hr>
		@endcan

		<h4 class="card-title">List of Projects</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					<th>Project No</th>
					<th>Project Name</th>
					<th>Client Name</th>
					<th>Commencement Date</th>
					<th>JV/Independent</th>
					<th class="text-center"style="width:5%">Edit</th> 
					@role('Super Admin')
					<th class="text-center"style="width:5%">Delete</th>
					@endrole
				</tr>
				</thead>
				<tbody>	
					@if(Auth::User()->can('pr edit power') || Auth::User()->can('pr view power'))
						@php
						$projects = $projects->merge($powerProjects);
						@endphp
					@endif	
					@if(Auth::User()->can('pr edit water') || Auth::User()->can('pr view water'))
						@php
						$projects = $projects->merge($waterProjects);
						@endphp				
					@endif

					@foreach($projects as $project)

					
						<tr>
							<td>{{$project->project_no}}</td>
							<td><a href="{{route('project.edit',$project->id)}}" style="color:grey">{{$project->name}}</a></td>
							<td>{{$project->client->name??''}}</td>
							<td>{{$project->formatted_commencement_date}}</td>
							<td>{{$project->prRole->name??''}}</td>
														
							<td class="text-center">
								<a class="btn btn-success btn-sm" href="{{route('project.edit',$project->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
							</td>
							@role('Super Admin')
							<td class="text-center">
								
								 <form  id="formDeleteProject{{$project->id}}" action="{{route('project.destroy',$project->id)}}" method="POST">
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