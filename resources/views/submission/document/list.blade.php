@if($documentIds->count()!=0)  
<hr>
<div class="card">
	<div class="card-body">
		<h2 class="card-title">Submission Document Detail</h2>
		
		<div class="table-responsive m-t-40">
			
			<table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
				<thead>
				
					<tr>
						<th>Document Name</th>
						<th>View</th>	 
						<th class="text-center"style="width:5%">Edit</th>	
						<th class="text-center"style="width:5%">Delete</th>
					</tr>
				</thead>
				<tbody>
					@foreach($documentIds as $documentId)
					<tr>
						<td>{{$documentId->description}}</td>
						@if(($documentId->extension == "jpg")||($documentId->extension == "jpeg")||($documentId->extension == "png"))
						<td><img  id="ViewIMG" src="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" href="{{asset(isset($documentId->file_name)?  'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30/></td>
						@elseif ($documentId->extension == 'pdf')
						<td><img  id="ViewPDF" src="{{asset('Massets/images/document.png')}}" href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30/></td>
						@else
						<td><a href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30><i class="fa fa-download" aria-hidden="true"></i>Download</a></td>
						@endif
						

						
						<td class="text-center">
						
						 <a class="btn btn-success btn-sm" id="editDocument" href="{{route('submissionDocument.edit',$documentId->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
						
						 </td>
						
						  
						 <td class="text-center">
						 <form id="deleteDocument{{$documentId->id}}" method="POST">
						 @method('DELETE')
						 @csrf
						 <button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
						 </form>

						 </td>
													
					</tr>
					@endforeach
						
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
   $(document).ready(function(){

	    $('#myDataTable').DataTable({
	                "order": [[ 2, "desc" ]],
	                stateSave: false,
	                dom: 'flrti',
	                scrollY:        "500px",
	      			scrollX:        true,
	        		scrollCollapse: true,
	        		paging:         false,
	        		fixedColumns:   {
	            		leftColumns: 1,
	            		rightColumns:2
	        		}
	    });

	     //function view from list table
        $(function(){

			 $('#ViewPDF, #ViewIMG').EZView();
		});



		$("form").submit(function (e) {
         e.preventDefault();
      	});

      	$('a[id^=editDocument]').click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        getAjaxData(url);

      });


	  	$("form[id^=deleteDocument]").submit(function(e) { 
	  	e.preventDefault();
	  	var url = $(this).attr('action');
	  	$('.fa-spinner').show(); 
	  	var folderUrl = $('.fa-folder-open').closest('a').attr('href');
	  	refreshTable(folderUrl,1000);
	  	submitForm(this, url);
	  	resetForm();
	  		 
	    });
	});
</script>
@endif