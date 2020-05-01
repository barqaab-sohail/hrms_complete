

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
						
						 
 

					</tr>
					</thead>
					<tbody>
						
							<tr>
								<td>testing</td>
	
								  						
							</tr>
					
					
					 
					
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
