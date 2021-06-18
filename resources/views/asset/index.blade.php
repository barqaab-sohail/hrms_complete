@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of Assets</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">List of Assets</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					<th>Id</th>
					<th>Asset Code</th>
					<th>Description</th>
					<th>Asset Condition</th>
					<th class="text-center"style="width:5%">Edit</th> 
					<th class="text-center"style="width:5%">Delete</th>
				
				</tr>
				</thead>
				<tbody>
				@if($assets->count()!=0) 
					@foreach($assets as $asset)
						<tr>
							<td>{{$asset->id}}</td>
							<td>{{$asset->asset_code??''}}</td>
							<td>{{$asset->description}}</td>
							<td>{{$asset->asCondition->name}}</td>
							
							<td class="text-center">
								<a class="btn btn-info btn-sm" href="{{route('asset.edit',$asset->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
							</td>
							<td class="text-center">
								 @role('Super Admin')
								 <form  id="formDeleteContact{{$asset->id}}" action="{{route('asset.destroy',$asset->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
								 </form>
								 @endrole
								 </td>
														
						</tr>
					@endforeach
				@endif
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
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
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