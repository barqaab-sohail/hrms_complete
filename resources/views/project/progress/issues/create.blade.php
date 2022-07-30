
	<!-- Model -->
	<div class="modal fade" id="ajaxModel" aria-hidden="true">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title" id="modelHeading"></h4>
	                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
	            </div>
	            <div class="modal-body">
	              <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
	                <form id="issueForm" name="issueForm" action="{{route('projectIssues.store')}}"class="form-horizontal">
	                   <input type="hidden" name="issue_id" id="issue_id">
	                   	<div class="row">
			                <div class="col-md-12">
				                <div class="form-group">
			                        <label class="control-label text-right">Description<span class="text_requried">*</span></label>
			                        <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control">    
		                    	</div>
			                </div>
	                    </div>
	                    <div class="row">
		                    <div class="col-md-6">
				                <div class="form-group">
			                        <label class="control-label text-right">Responsibility<span class="text_requried">*</span></label>
			                        <input type="text" name="responsibility" id="responsibility" value="{{ old('responsibility') }}" class="form-control">    
		                    	</div>
			                </div>
			                <div class="col-md-3">
				                <div class="form-group">
			                        <label class="control-label text-right">Status<span class="text_requried">*</span></label>
			                        <select  name="status"  id="status" class="form-control selectTwo" data-validation="required">
			                        	<option></option>
			                        	<option value="Pending">Pending</option>
			                        	<option value="Resolved">Resolved</option>
                                	</select> 
			                          
		                    	</div>
			                </div>
			                <div class="col-md-3 hideDiv">
			                	<div class="form-group">
		                        	<label class="control-label text-right">Resolve Date</label><br>
		                          	<input type="text" id="resolve_date" name="resolve_date" value="{{ old('resolve_date') }}" class="form-control date_input" readonly>     
                            		<br>
                            		<i class="fas fa-trash-alt text_requried"></i>
		                    	</div>
			                </div>
		                    
	                    </div>
	                    
	                    <div class="col-sm-offset-2 col-sm-10">
	                     <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save
	                     </button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	    <script>
	    	formFunctions();
	    </script>
	</div> <!--End Modal  -->
	<style>
		.modal-dialog {
	    	max-width: 80%;
		    display: flex;
		}
		.ui-timepicker-container{ 
     		z-index:1151 !important; 
  		}
	</style>
	<!-- End Modal -->
	<div class="card">
		<div class="card-body">	
			<button type="button" class="btn btn-success float-right"  id ="createIssue" data-toggle="modal">Add Issue</button>
			<h4 class="card-title" style="color:black">List of Issues</h4>
			<div class="table-responsive m-t-40">	
				<table id="myTable" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th style="width:60%">Description</th>
						<th style="width:10%">Responsibility</th>
						<th style="width:10%">Status</th>
						<th style="width:10%">Resolve Date</th>
						<th style="width:5%">Edit</th>
						@role('Super Admin')
						<th style="width:5%">Delete</th>
						@endrole
					</tr>
					</thead>
				</table>
			</div>	
		</div>
	</div>
	<script>
$(document).ready(function() {
	
  		$("#status").change(function (){
	  		if($(this).val()=="Resolved"){
	  			$(".hideDiv").show();
	  		}else{
	  			$(".hideDiv").hide();
	  			$("#resolve_date").val('');
	  		}
	    });
	
		$(function () {
	      	$.ajaxSetup({
	          headers: {
	              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	          }
	    	});

		    var table = $('#myTable').DataTable({
		  		processing: true,
		  		serverSide: true,
		  		
			  	ajax: {
			   	url: "{{ route('projectIssues.create') }}",
			  	},
			  	columns: [
			  		{data: 'description', name: 'description'},
			  		{data: 'responsibility', name: 'responsibility'},
			  		{data: 'status', name: 'status'},
			  		{data: 'resolve_date', name: 'resolve_date'},
				   	{data: 'edit',name: 'edit', orderable: false, searchable: false },
				   	@role('Super Admin')
				   	{data: 'delete',name: 'delete', orderable: false, searchable: false }
				   	@endrole
			  	]
	 		});

	 		$('#createIssue').click(function (e) {
	 			$('#json_message_modal').html('');
	 			
	        	$('#issue_id').val("");
		        $('#issueForm').trigger("reset");
		        $('#status').trigger("change");
	          	$('#ajaxModel').modal('show');
	          	
	          
	 		});

	 		$('body').unbind().on('click', '.editIssue', function () {
		      var issue_id = $(this).data('id');
		      $.get("{{ url('hrms/project/projectIssues') }}" +'/' + issue_id +'/edit', function (data) {
			      $('#json_message_modal').html('');
			      $('#issue_id').val(data.id);
			      $('#description').val(data.description);
			      $('#responsibility').val(data.responsibility);
			      $('#status').val(data.status).trigger('change');
			      $('#resolve_date').val(data.resolve_date);
			      $('#ajaxModel').modal('show');
		  	});

			    
			});

	 		
	 		
		   	$('#saveBtn').unbind().click(function (e) {
		   		$(this).attr('disabled','ture');
		        //submit enalbe after 3 second
		        setTimeout(function(){
		            $('.btn-prevent-multiple-submits').removeAttr('disabled');
		        }, 3000);
		   		
	        e.preventDefault();
	        $(this).html('Save');
	         
		        $.ajax({
		          data: $('#issueForm').serialize(),
		          url: "{{ route('projectIssues.store') }}",
		          type: "POST",
		          dataType: 'json',
		          success: function (data) {
		              $('#ajaxModel').modal('hide');
		              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
		              table.draw();
		          },
		          error: function (data) {
		              var errorMassage = '';
		              $.each(data.responseJSON.errors, function (key, value){
		                errorMassage += value + '<br>';  
		                });
		                $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

		              $('#saveBtn').html('Save Changes');
		          }
		      	});
	    	});

	    	$('body').on('click', '.deleteIssue', function () {
		        var issue_id = $(this).data("id");
		        var con = confirm("Are You sure want to delete !");
		        if(con){
		          $.ajax({
		            type: "DELETE",
		            url: "{{ route('projectIssues.store') }}"+'/'+issue_id,
		            success: function (data) {
		                table.draw();
		                if(data.error){
		                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
		                }
		  
		            },
		            error: function (data) {
		                
		            }
		          });
		        }
	    	});
	  	}); // end function

}); //End document ready function

	</script>

</div>
