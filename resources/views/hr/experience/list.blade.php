@if($hrExperiences->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Experience Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Organization</th>
						<th>Job Title</th>
						<th>From</th>
						<th>To</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Delete</th>
						
					</tr>
					</thead>
					<tbody>
						@foreach($hrExperiences as $experience)
							<tr>
								<td>{{$experience->organization}}</td>
								<td>{{$experience->job_title}}</td>
								<td>{{$experience->from}}</td>
								<td>{{$experience->to}}</td>
								@can('hr edit education')
								<td class="text-center">
								 <a class="btn btn-success btn-sm" id="editExperience" href="{{route('experience.edit',$experience->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								@endcan
								 
								@can('hr delete education')
								 <td class="text-center">
								 <form id="deleteExperience{{$experience->id}}" action="{{route('experience.destroy',$experience->id)}}" method="POST">
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


  	$("form[id^=deleteExperience]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	refreshTable("{{route('experience.table')}}",500);
    });




});
</script>
