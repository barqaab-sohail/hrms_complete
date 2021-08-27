@if($hrExits->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Exit Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Employee Status</th>
						<th>Reason</th>
						
						 
 
						<th colspan="2" class="text-center"style="width:10%"> Actions </th> 
 

					</tr>
					</thead>
					<tbody>
						@foreach($hrExits as $hrExit)
							<tr>
								<td>{{$hrExit->hrStatus->name}}</td>
								<td>{{$hrExit->reason}}</td>
								

								 <td class="text-center">
								 <a class="btn btn-success btn-sm" id="editContact" href="{{route('exit.edit',$hrExit->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								 <td class="text-center">
								 @can('hr edit record')
								 <form  id="deleteExit{{$hrExit->id}}" action="{{route('exit.destroy',$hrExit->id)}}" method="POST">
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
        
        var url = $(this).attr('href');
        getAjaxData(url);

      });

  	$("form[id^=deleteExit]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	 refreshTable("{{route('exit.table')}}",500);
  	 
    });

});
</script>
