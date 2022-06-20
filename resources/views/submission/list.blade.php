@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
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
	                <form id="submissionForm" name="submissionForm" action="{{route('submission.store')}}"class="form-horizontal">
	                   <input type="hidden" name="submission_id" id="submission_id">
	                   	<div class="row">
	                   		<div class="col-md-2">
			                   	<div class="form-group">
			                        <label class="control-label text-right">Submission Type<span class="text_requried">*</span></label><br>
			                          <select  name="sub_type_id"  id="sub_type_id" class="form-control selectTwo" data-validation="required" readonly>
			                          </select>
			                    </div>
			                </div>
			                <div class="col-md-2">
				                <div class="form-group">
			                        <label class="control-label text-right">Division<span class="text_requried">*</span></label><br>
			                        <select  name="sub_division_id"  id="sub_division_id" class="form-control selectTwo" data-validation="required" readonly>
			                        </select>
			                    </div>
			                </div>
			                <div class="col-md-2">
				                <div class="form-group">
			                        <label class="control-label text-right">Submission No</label>
			                        <input type="text" name="submission_no" id="submission_no" value="{{ old('submission_no') }}" class="form-control" readonly>    
		                    	</div>
			                </div>
			                <div class="col-md-6">
			                	<div class="form-group">
		                        	<label class="control-label text-right">Client Name<span class="text_requried">*</span></label><br>
		                          	<select  name="client_id"  id="client_id" class="form-control selectTwo" data-validation="required" readonly>
		                          	</select>
		                          	<button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#clientModal"><i class="fas fa-plus"></i>
		                    	</div>
			                </div>
			            </div>
	                    
	                    <div class="form-group" id="hideDiv">
                        	<label class="control-label text-right">EOI Reference</label>
                        	<select  name="eoi_reference_id"  id="eoi_reference_id" class="form-control selectTwo" data-validation="required" readonly>
                        	</select>   
	                    </div>
	                    <div class="form-group">
	                        <label class="control-label text-right">Project Name</label>
	                        <input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}" class="form-control"  placeholder="Please enter Project Name">    
	                    </div>
	                    <div class="row">
			                <div class="col-md-2">
				                <div class="form-group">
			                        <label class="control-label text-right">Submission Date<span class="text_requried">*</span></label><br>
			                         <input type="text" id="submission_date" name="submission_date" value="{{ old('submission_date') }}" class="form-control date_input" readonly>     
                            		<br>
                            		<i class="fas fa-trash-alt text_requried"></i>
			                    </div>
			                </div>
			                <div class="col-md-2">
				                <div class="form-group">
			                        <label class="control-label text-right">Submission Time</label>
			                        <input type="text" id="submission_time" name="submission_time" value="{{ old('submission_time') }}" class="form-control time_input" readonly>     
		                          	<br>
		                          	<i class="fas fa-trash-alt text_requried"></i>   
		                    	</div>
			                </div>
			                <div class="col-md-8">
			                	<div class="form-group">
		                        	<label class="control-label text-right">Submission Address<span class="text_requried">*</span></label><br>
		                          	<input type="text" name="address"  id="address" value="{{old('address')}}" class="form-control">
		                          	
		                    	</div>
			                </div>
			            </div>
			            <div class="row">
			                <div class="col-md-2">
			                  	<div class="form-group">
			                        <label class="control-label text-right">Current Status</label>
			                        <select  name="sub_status_id"  id="sub_status_id" class="form-control selectTwo" readonly>
                        			</select>
			                	</div>
			                </div>
			                <div class="col-md-10">
			                  	<div class="form-group">
			                        <label class="control-label text-right">Comments</label>
			                        <input type="text" name="comments" id="comments" value="{{ old('comments') }}" class="form-control exempted" data-validation="length"  data-validation-length="max190" placeholder="Please enter Comments">    
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
			<button type="button" class="btn btn-success float-right"  id ="createSubmission" data-toggle="modal">Add Submission</button>
			<h4 class="card-title" style="color:black">List of Submissions</h4>
			<div class="table-responsive m-t-40">	
				<table id="myTable" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th style="width:5%">Submission No</th>
						<th style="width:45%">Name of Project</th>
						<th style="width:30%">Client Name</th>
						<th style="width:5%">Division</th>
						<th style="width:5%">Submission Type</th>
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
	 @include('submission.client.clientModal')
	<script>
	$(document).ready(function() {

		$('#hideDiv').hide();
		$(function () {
	      	$.ajaxSetup({
	          headers: {
	              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	          }
	    	});

		    var table = $('#myTable').DataTable({
		  		processing: true,
		  		serverSide: true,
		  		"aaSorting": [],
			  	ajax: {
			   	url: "{{ route('submission.index') }}",
			  	},
			  	columns: [
			  		{data: 'submission_no', name: 'submission_no'},
			  		{data: 'project_name', name: 'project_name'},
			  		{data: 'client_id', name: 'client_id'},
			  		{data: 'sub_division_id', name: 'sub_division_id'},
				   	{data: 'sub_type_id', name: 'sub_type_id'},
				   	{data: 'edit',name: 'edit', orderable: false, searchable: false },
				   	@role('Super Admin')
				   	{data: 'delete',name: 'delete', orderable: false, searchable: false }
				   	@endrole
			  	]
	 		});

	 		$('#createSubmission').click(function (e) {
	 			$(".selectTwo").select2({
	        		width: "100%",
		        	theme: "classic",
	        	});
	 			$('#json_message_modal').html('');
		        $('#submissionForm').trigger("reset");

	 			$.get("{{ url('hrms/submission/create') }}" , function (data) {
	        	
	        		$("#sub_division_id").empty();
	              	$("#sub_division_id").append('<option value="">Select Division</option>');
	        		$.each(data.divisions, function (key, value){
	        			$("#sub_division_id").append('<option value="'+value.id+'">'+value.name+'</option>');
	        		});
	        		$("#client_id").empty();
	              	$("#client_id").append('<option value="">Select Client</option>');
	        		$.each(data.clients, function (key, value){
	        			$("#client_id").append('<option value="'+value.id+'">'+value.name+'</option>');
	        		});
	        		$("#sub_type_id").empty();
	              	$("#sub_type_id").append('<option value="">Select Submission Type</option>');
	        		$.each(data.subTypes, function (key, value){
	        			$("#sub_type_id").append('<option value="'+value.id+'">'+value.name+'</option>');
	        		});
	        		$("#sub_status_id").empty();
	              	$("#sub_status_id").append('<option value="">Select Current Status</option>');
	        		$.each(data.subStatuses, function (key, value){
	        			$("#sub_status_id").append('<option value="'+value.id+'">'+value.name+'</option>');
	        		});
	        		$("#sub_financial_type_id").empty();
	              	$("#sub_financial_type_id").append('<option value="">Select Financial Type</option>');
	        		$.each(data.subFinancialTypes, function (key, value){
	        			$("#sub_financial_type_id").append('<option value="'+value.id+'">'+value.name+'</option>');
	        		});
	        		$("#sub_cv_format_id").empty();
	              	$("#sub_cv_format_id").append('<option value="">Select Cv Format</option>');
	        		$.each(data.subCvFormats, function (key, value){
	        			$("#sub_cv_format_id").append('<option value="'+value.id+'">'+value.name+'</option>');
	        		});

	          	$('#ajaxModel').modal('show');
	      		});
	 		});
	 		$('#sub_type_id').change(function(){
	    		var subTypeId = $(this).val();
	    		if(subTypeId ==3){
	    			$('#hideDiv').show();
	    		}else{
	    			$('#hideDiv').hide();
	    		}
	    		$.get("{{ url('hrms/submission/eoiReference') }}", function (data) {
	    			$("#eoi_reference_id").empty();
			        $("#eoi_reference_id").append('<option value="">Select EOI Reference</option>');
	    			$.each(data, function (key, value){
	        			$("#eoi_reference_id").append('<option value="'+value.id+'">'+value.project_name+'</option>');
	        		});

	    		});
	    		$.get("{{ url('hrms/submission/submissionNo') }}"+"/"+subTypeId, function (data) {
	    			$("#submission_no").val(data);
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
		          data: $('#submissionForm').serialize(),
		          url: "{{ route('submission.store') }}",
		          type: "POST",
		          dataType: 'json',
		          success: function (data) {
		              $('#ajaxModel').modal('hide');
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

	    	$('body').on('click', '.deleteSubmission', function () {
		        var submission_id = $(this).data("id");
		        var con = confirm("Are You sure want to delete !");
		        if(con){
		          $.ajax({
		            type: "DELETE",
		            url: "{{ route('submission.store') }}"+'/'+submission_id,
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

@stop