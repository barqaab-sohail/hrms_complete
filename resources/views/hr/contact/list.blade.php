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
						<th>House No</th>
						<th>Street No</th>
						<th>Town/Village</th>
						<th>City</th>
						<th>Province</th>
						<th>Country</th>
						 
 
						<th colspan="2" class="text-center"style="width:10%"> Actions </th> 
 

					</tr>
					</thead>
					<tbody>
						@foreach($hrContacts as $hrContact)
							<tr>
								<td>{{$hrContact->hrContactType->name}}</td>
								<td>{{$hrContact->house}}</td>
								<td>{{$hrContact->street}}</td>
								<td>{{$hrContact->town}}</td>
								<td>{{$hrContact->city->name}}</td>
								<td>{{$hrContact->state->name}}</td>
								<td>{{$hrContact->country->name}}</td>
								

								 <td class="text-center">
								 <a class="btn btn-info btn-sm" id="editContact" href="{{route('contact.edit',$hrContact->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
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
        console.log('edit');
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
