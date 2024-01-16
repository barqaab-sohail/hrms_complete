@if($hrContacts->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Contact Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Contact Type</th>
						<th>Address</th> 
						<th>Mobile</th> 
						<th>Email</th> 
 						@can('hr edit contact')
						<th colspan="2" class="text-center"style="width:10%"> Actions </th> 
						@endcan 

					</tr>
					</thead>
					<tbody>
						@foreach($hrContacts as $hrContact)
							<tr>
								<td>{{$hrContact->hrContactType->name}}</td>
								<td>{{$hrContact->house??''}} {{$hrContact->street??''}} {{$hrContact->town??''}} {{$hrContact->state->name??''}} {{$hrContact->country->name??''}}</td>
								<td>{{$hrContact->mobile->mobile??''}}</td>
								<td>{{$hrContact->email->email??''}}</td>
								
								@can('hr edit contact')
								 <td class="text-center">
								 <a class="btn btn-success btn-sm" id="editContact" href="{{route('contact.edit',$hrContact->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								 <td class="text-center">
								 @can('hr edit record')
								 <form  id="deleteContact{{$hrContact->id}}" action="{{route('contact.destroy',$hrContact->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
								 </form>
								 @endcan
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
	
	isUserData(window.location.href, "{{URL::to('/hrms/employee/user/data')}}");
	 

	$("form").submit(function (e) {
         e.preventDefault();
    });
	

	$('a[id^=edit]').click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        getAjaxData(url);
    });

  	$("form[id^=deleteContact]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	 refreshTable("{{route('contact.table')}}",300);
  	 
    });


});
</script>
