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
                   	<div class="form-group">
                        <label class="control-label text-right">Submission Type<span class="text_requried">*</span></label><br>
                          <select  name="sub_type_id"  id="sub_type_id" class="form-control selectTwo" data-validation="required" readonly>
                          </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Client Name<span class="text_requried">*</span></label><br>
                          <select  name="client_id"  id="client_id" class="form-control selectTwo" data-validation="required" readonly>
                          </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Division<span class="text_requried">*</span></label><br>
                          <select  name="sub_division_id"  id="sub_division_id" class="form-control selectTwo" data-validation="required" readonly>
                          </select>
                    </div>
                     <div class="form-group" id="hideDiv">
                        <label class="control-label text-right">EOI Reference</label>
                        <select  name="eoi_reference_id"  id="eoi_reference_id" class="form-control selectTwo" data-validation="required" readonly>
                        </select>   
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Project Name</label>
                        <input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Please enter Project Name">    
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Submission No</label>
                        <input type="text" name="submission_no" id="submission_no" value="{{ old('submission_no') }}" class="form-control" readonly>    
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Comments</label>
                        <input type="text" name="comments" id="comments" value="{{ old('comments') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Please enter Comments">    
                    </div>
                    
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<div class="card">
	<div class="card-body">	
		<button type="button" class="btn btn-success float-right"  id ="createSubmission" data-toggle="modal" >Add Submission</button>
		<h4 class="card-title" style="color:black">List of Submissions</h4>
		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Submission No</th>
					<th>Name of Project</th>
					<th>Client Name</th>
					<th>Division</th>
					<th>Submission Type</th>
					<th class="text-center"style="width:5%">Edit</th>
					@role('Super Admin')
					<th class="text-center"style="width:5%">Delete</th>
					@endrole
				</tr>
				</thead>
			</table>
		</div>
		
	</div>
</div>


<script>



$(document).ready(function() {

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
		  		{data: 'client_name', name: 'client_name'},
		  		{data: 'division', name: 'division'},
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
          	$('#ajaxModel').modal('show');
      		});
 		});
 		$('#sub_type_id').change(function(){
    		var subTypeId = $(this).val();
    		$.ajax({
    			type:"get",
   			 	url: "{{url('hrms/submission/eoiReference')}}"+"/"+subTypeId,
    			success:function(res)
		        {       
		            if(res)
		            {
		              $("#eoi_reference_id").empty();
		              $("#eoi_reference_id").append('<option value="">Select EOI Reference</option>');
		              console.log(res);
		            }
		        }
		    });//end ajax
        });

 		$('body').unbind().on('click', '.editStatus', function () {
	      	var leave_id = $(this).data('id');
	      
	      	$('.hide').hide();
		    $('#json_message_modal').html('');
		    $("#manager_id").empty();

	    	$.get("{{ url('hrms/leaveStatus') }}" +'/' + leave_id +'/edit', function (data) {
	    	
	    		if(data.manager.id){
	    			var designation = data.manager.employee_designation[data.manager.employee_designation.length-1].name;
	    			$('#manager_id').append('<option value="'+data.manager.id+'">'+data.manager.first_name+' '+data.manager.last_name+' - '+designation+'</option>');
	    		}

	    		if(data.leaveStatus && data.leaveStatus.le_status_type_id == 2){
	    				$('#remarks').val(data.remarks);
	    				$("#le_status_type_id").val("2").change();
	    				$('.hide').show();
	    		}else{
	    			$("#le_status_type_id").val("1").change();
	    		}
			})         
	        
	        $('#ajaxModel').modal('show');
	        $('#leave_id').val(leave_id);

	   	});

	   	$('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
	        $.ajax({
	          data: $('#leaveStatusForm').serialize(),
	          url: "{{ route('leaveStatus.store') }}",
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

    	$('body').on('click', '.deleteLeave', function () {
	        var leave_id = $(this).data("id");
	        var con = confirm("Are You sure want to delete !");
	        if(con){
	          $.ajax({
	            type: "DELETE",
	            url: "{{ route('leave.store') }}"+'/'+leave_id,
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

  	$('#le_status_type_id').change(function (){

  		if($(this).val() == 2){
  			$('.hide').show();
  		}else{
  			$('#remarks').val('');
  			$('.hide').hide();
  		}

  	});


}); //End document ready function



</script>

@stop