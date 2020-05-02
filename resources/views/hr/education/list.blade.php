

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Education Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Degree Name</th>
						<th>Institute</th>
						<th>Passing Year</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Delete</th>
						
					</tr>
					</thead>
					<tbody>
						@foreach($employee->education as $education)
							<tr>
								<td>{{$education->degree_name}}</td>
								<td>{{$education->pivot->institute}}</td>
								<td>{{$education->pivot->to}}</td>
								@can('hr edit education')
								<td class="text-center">
								 <a class="btn btn-info btn-sm" id="editEducation" href="{{route('education.edit',$education->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								@endcan
								 
								@can('hr delete education')
								 <td class="text-center">
								 <form id="deleteEducation{{$education->id}}" action="{{route('education.destroy',$education->id)}}" method="POST">
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

<script>
$(document).ready(function() {
	 $("form").submit(function (e) {
         e.preventDefault();
      });


  	$("form[id^=deleteContact]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	 refreshTable("{{route('education.table')}}");
    });

});
</script>
