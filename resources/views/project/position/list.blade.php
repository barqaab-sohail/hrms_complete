@if($prPositions->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">List of Positions</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Position Name</th>
						<th>Total Man-Month</th>
						<th class="text-center"style="width:5%">Edit</th> 
						<th class="text-center"style="width:5%">Delete</th>
					</tr>
					</thead>
					<tbody>
						@foreach($prPositions as $prPosition)
							<tr>
								<td>{{$prPosition->name}}</td>
								<td>{{$prPosition->total_mm}}</td>

								 <td class="text-center">
								 <a class="btn btn-success btn-sm" id="editPosition" href="{{route('projectPosition.edit',$prPosition->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								 <td class="text-center">
								
								 <form  id="deletePosition{{$prPosition->id}}" action="{{route('projectPosition.destroy',$prPosition->id)}}" method="POST">
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

  	$("form[id^=deletePosition]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	refreshTable("{{route('projectPosition.table')}}",1000);
  	 
    });

    $('#myTable').DataTable({
        stateSave: false,

        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                }
            }, {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0, 1]
                }
            },
        ],
        scrollY:        "300px",
			scrollX:        true,
		scrollCollapse: true,
		paging:         false,
		
    });



});
</script>
