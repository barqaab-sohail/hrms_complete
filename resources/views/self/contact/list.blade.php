@if($contacts->count()!=0)
<br>
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">List of Contacts</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					
					<th>Name</th>
					<th>Designation</th>

					<th>Mobile-1</th>
					<th>Mobile-2</th>
					<th>Mobile-3</th>
					
					<th class="text-center"style="width:5%">Edit</th> 
					<th class="text-center"style="width:5%">Delete</th>

					
				</tr>
				</thead>
				<tbody>
					@foreach($contacts as $contact)
						<tr>
							
							<td>{{$contact->name}}</td>
							<td>{{$contact->designation}}</td>
							<td>{{$contact->mobiles->get(0)->mobile}}</td>
							<td>{{$contact->mobiles->get(1)->mobile??''}}</td>
							<td>{{$contact->mobiles->get(2)->mobile??''}}</td>

							
							
							
							<td class="text-center">
								<a class="btn btn-success btn-sm" id="editContact{{$contact->id}}" href="{{route('selfContact.edit',$contact->id)}}" title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
							</td>
							<td class="text-center">
								
								 <form  id="formDeleteContact{{$contact->id}}" action="{{route('selfContact.destroy',$contact->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
								 </form>
							
								 </td>
														
						</tr>
					@endforeach
				
				</tbody>
			</table>
		</div>
	</div>
</div>
@endif

<script>
	$(document).ready(function() {
		$('a[id^=editContact]').click(function (e){
        e.preventDefault();
       	var url = $(this).attr('href');
       	getAjaxData(url);
        refreshTable("{{route('selfContact.table')}}",500);	     
       
     	});

	  	$("form[id^=formDeleteContact]").submit(function(e) { 
	  	e.preventDefault();
	  	var url = $(this).attr('action');
	  	$('.fa-spinner').show(); 

	  	submitForm(this, url);
	  	resetForm();
	  	refreshTable("{{route('selfContact.table')}}",500);
	  	 
	    });

	
            $('#myTable').DataTable({
                stateSave: false,
        
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
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
        		}
            });
            
    });
</script>

