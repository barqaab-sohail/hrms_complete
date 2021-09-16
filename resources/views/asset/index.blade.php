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
					<th>Location/Allocation</th>
					<th>Barcode</th>
					<th>Image</th>
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
							<td>{{$asset->description??''}}</td>
							
							<td>{{isset($asset->asCurrentLocation->office_id)?officeName($asset->asCurrentLocation->office_id):employeeFullName($asset->asCurrentLocation->hr_employee_id??'')}}</td>
						
							<td><img src="data:image/png;base64,{{DNS1D::getBarcodePNG("$asset->asset_code", 'C39+',1,33,array(0,0,0), true)}}" alt="barcode" /></td>
							<td><img src="{{asset(isset($asset->asDocumentation->file_name)? 'storage/'.$asset->asDocumentation->path.$asset->asDocumentation->file_name: 'Massets/images/document.png') }}" class="img-round picture-container picture-src"  id="ViewIMG{{$asset->id}}"  title="" width="50" ></td>	
							<td class="text-center">
								<a class="btn btn-success btn-sm" href="{{route('asset.edit',$asset->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
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
                            columns: [ 0, 1, 2,3,4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Document Tittle',
                        customize: function (doc) {

                        	if (doc) {
	        					for (var i = 1; i < doc.content[1].table.body.length; i++) {
	 
	            					var tmptext = doc.content[1].table.body[i][0].text;
	            					tmptext = tmptext.substring(10, tmptext.indexOf("width=") - 2);
						            doc.content[1].table.body[i][0] = {
						                margin: [0, 0, 0, 12],
						                alignment: 'center',
						                image: tmptext,
						                width: 60,
						                height: 58
						            };
	        					}
    						}
                        },
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
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
        		},
        		
            });

	//function view from list table
        $(function(){
			$("[id^='ViewIMG']").EZView();
		});        
});


</script>

@stop