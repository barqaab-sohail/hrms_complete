@if($userPermissions->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">User Permission Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Permission Name</th>
						<th> Actions </th> 
					</tr>
					</thead>
					<tbody>
						@foreach($userPermissions as $userPermission)
							<tr>
								<td>{{$userPermission->name}}</td>
								 <td class="text-center">
								 @can('hr edit record')
								 <form  id="deletePermission{{$userPermission->id}}" action="{{route('userLogin.destroy',$userPermission->id)}}" method="POST">
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
	  $('#myTable').DataTable({
                stateSave: false,
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    },
                ],
                scrollY:        "300px",
      			scrollX:        true,
        		scrollCollapse: true,
        		paging:         false,
        		
            });

	 $("form").submit(function (e) {
         e.preventDefault();
      });


  	$("form[id^=deletePermission]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 
  	submitForm(this, url);
  	refreshTable("{{route('userLogin.table')}}");
  	 	 
    });

});
</script>
