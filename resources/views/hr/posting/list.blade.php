@if($hrPostings->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Transfer/Posting Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						
						<th>Description / Remarks</th>
						<th>Effective Date</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Delete</th>
						
					</tr>
					</thead>
					<tbody>
						@foreach($hrPostings as $posting)
							<tr>
								
								<td>{{$posting->remarks}}</td>
								<td>{{date('M d, Y', strtotime($posting->effective_date))}}</td>
								
								@can('hr edit posting')
								<td class="text-center">
								 <a class="btn btn-success btn-sm" id="editEducation" href="{{route('posting.edit',$posting->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								@endcan
								 
								@can('hr delete posting')
								 <td class="text-center">
								 <form id="deletePosting{{$posting->id}}" action="{{route('posting.destroy',$posting->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash"></i></button>
								 </form>

								 </td>
								@endcan
								
	
							</tr>
						@endforeach
					
					 
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<hr>  
@endif
<script>
$(document).ready(function() {
	 $("form").submit(function (e) {
         e.preventDefault();
      });

	 $('a[id^=edit]').click(function (e){
        e.preventDefault();
       
        var url = $(this).attr('href');
        
        getAjaxData(url);
       
      });


  	$("form[id^=deletePosting]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	refreshTable("{{route('posting.table')}}",1000);
    });




});
</script>
