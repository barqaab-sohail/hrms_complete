@if($hrPromotions->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Promotion Detail</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Description / Remarks</th>
						<th>Effective Date</th>
						<th>Letter / Order</th>
						<th class="text-center">Edit</th>
						<th class="text-center">Delete</th>
						
					</tr>
					</thead>
					<tbody>
						@foreach($hrPromotions as $promotion)
							<tr>
								<td>{{$promotion->remarks}}</td>
								<td>{{date('M d, Y', strtotime($promotion->effective_date))}}</td>
								<td><img  id="ViewPDF" src="{{asset('Massets/images/document.png')}}" href="{{asset(isset($promotion->hrDocumentation->file_name)? 'storage/'.$promotion->hrDocumentation->path.$promotion->hrDocumentation->file_name: 'Massets/images/document.png') }}" width=30/></td>
								@can('hr edit promotion')
								<td class="text-center">
								 <a class="btn btn-success btn-sm" id="editEducation" href="{{route('promotion.edit',$promotion->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 </td>
								@endcan
								 
								@can('hr delete promotion')
								 <td class="text-center">
								 <form id="deletePromotion{{$promotion->id}}" action="{{route('promotion.destroy',$promotion->id)}}" method="POST">
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


  	$("form[id^=deletePromotion]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  	resetForm();
  	refreshTable("{{route('promotion.table')}}",1000);
    });

  	  //function view from list table
  	$(function(){

			 $('#ViewPDF, #ViewIMG').EZView();
	});


});
</script>
