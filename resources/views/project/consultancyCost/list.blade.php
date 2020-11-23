@if($prConsultancyCosts->count()!=0)  

<div class="card" id="myDataDiv">
	<div class="card-body">
		<h2 class="card-title">Consultancy Cost Detail</h2>
		
		<div class="table-responsive m-t-40">
			
			<table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
				<thead>
				
					<tr>
						<th>Cost Type</th>
						<th>Man-Month Cost</th>	
						<th>Direct Cost</th>
						<th>Tax</th>
						<th>Contingency</th>
						<th>Total Cost</th>		 
						<th class="text-center"style="width:5%">Edit</th>
						<th class="text-center" style="width:5%">Delete</th>
					</tr>
				</thead>
				<tbody>
					@foreach($prConsultancyCosts as $prConsultancyCost)
					<tr>
						<td>{{$prConsultancyCost->prConsultancyCostType->name}}</td>
						<td>{{number_format($prConsultancyCost->prConsultancyCostMm->man_month_cost)}}</td>
						<td>{{number_format($prConsultancyCost->prConsultancyCostDirect->direct_cost??0)}}</td>
						<td>{{number_format($prConsultancyCost->prConsultancyCostTax->tax_cost??0)}}</td>
						<td>{{number_format($prConsultancyCost->prConsultancyCostContingency->contingency_cost??0)}}</td>
						<td>
						{{number_format(
							($prConsultancyCost->prConsultancyCostMm->man_month_cost)+
							($prConsultancyCost->prConsultancyCostDirect->direct_cost??0)+
							($prConsultancyCost->prConsultancyCostTax->tax_cost??0)+
							($prConsultancyCost->prConsultancyCostContingency->contingency_cost??0)
						)}}
						</td>
						
						<td class="text-center">
						 <a class="btn btn-info btn-sm" id="editConsultancyCost" href="{{route('projectConsultancyCost.edit',$prConsultancyCost->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
						 </td>
						
						 <td class="text-center">
						 <form id="deleteConsultancyCost{{$prConsultancyCost->id}}" action="{{route('projectConsultancyCost.destroy',$prConsultancyCost->id)}}" method="POST">
						 @method('DELETE')
						 @csrf
						 <button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash-alt "></i></button>
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
    
		$("form").submit(function (e) {
         e.preventDefault();
      	});

      	$('a[id^=editConsultancyCost]').click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        getAjaxData(url);

      });


	  	$("form[id^=deleteConsultancyCost]").submit(function(e) { 
	  	e.preventDefault();
	  	var url = $(this).attr('action');  	
	  	submitForm(this, url);
	  	resetForm();
	  	refreshTable("{{route('projectConsultancyCost.table')}}",1000);
	  		 
	    });
	});
</script>
@endif