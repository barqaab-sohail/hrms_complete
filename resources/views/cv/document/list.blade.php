@if($documentIds->count()!=0)  
<hr>
<div class="card">
	<div class="card-body">
		<h2 class="card-title">CV Document Detail</h2>
		
		<div class="table-responsive m-t-40">
			
			<table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
				<thead>
				
					<tr>
						<th>Document Name</th>
						<th>View</th>
						@can('edit cv record')			 
						<th class="text-center"style="width:5%">Edit</th>	
						<th class="text-center"style="width:5%">Delete</th>
						@endcan
					</tr>
				</thead>
				<tbody>
					@foreach($documentIds as $documentId)
					<tr>
						<td>{{$documentId->document_name}}</td>
						@if(($documentId->extension == "jpg")||($documentId->extension == "jpeg")||($documentId->extension == "png"))
						<td><img  id="ViewIMG" src="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" href="{{asset(isset($documentId->file_name)?  'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30/></td>
						@elseif ($documentId->extension == 'pdf')
						<td><img  id="ViewPDF" src="{{asset('Massets/images/document.png')}}" href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30/></td>
						@else
						<td><a href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30><i class="fa fa-download" aria-hidden="true"></i>Download</a></td>
						@endif
						

						
						
						@can('cv edit record')
						@if($documentId->document_name != 'Original CV')
						<td class="text-center">
						
						 <a class="btn btn-success btn-sm" id="editDocument" href="{{route('cvDocument.edit',$documentId->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
						
						 </td>
						
						  
						 <td class="text-center">
						 <form id="deleteDocument{{$documentId->id}}" method="POST">
						 @method('DELETE')
						 @csrf
						 <button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
						 </form>

						 </td>
						 @endif
						 @endcan
													
					</tr>
					@endforeach
						
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
    $(document).ready(function(){


	     //function view from list table
        $(function(){
        $('#ViewPDF, #ViewIMG').EZView();
    	});




		$("form").submit(function (e) {
         e.preventDefault();
      	});

		$(document).on('submit','#deleteDocument{{$documentId->id}}',function(e) {
	  	e.preventDefault();
	  	var url = "{{route('cvDocument.destroy',$documentId->id)}}";
	  	$('.fa-spinner').show(); 
	  	submitForm(this, url);
	  	resetForm();
	  	refreshTable("{{route('cvDocument.table')}}",900);
	 
	    });


	    
	});
</script>
@endif