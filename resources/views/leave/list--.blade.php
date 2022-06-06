@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">List of Leaves</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Designation/Position</th>
					<th>Leave From</th>
					<th>Leave To</th>
					<th>Leave Type</th>
					<th>Total Days</th>
					<th>Leave Status</th>
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
                <form id="leaveStatusForm" name="manageForm" action="{{route('manager.store')}}"class="form-horizontal">
                   <input type="hidden" name="leave_id" id="leave_id">
                   	<div class="form-group">
                        <label class="control-label text-right">HOD<span class="text_requried">*</span></label><br>
                          <select  name="manager_id"  id="manager_id" class="form-control selectTwo" data-validation="required" readonly>
                          </select>
                    </div>
                   
                    <div class="form-group">
                        <label class="control-label text-right">Leave Status<span class="text_requried">*</span></label><br>
                          <select  name="le_status_type_id"  id="le_status_type_id" class="form-control selectTwo" data-validation="required">
                          <option value="1">Approved</option>
                          <option value="2">Rejected</option>
                          </select>
                    </div>
                    <div class="form-group hide">
                        <label class="control-label text-right">Remarks</label>
                        <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Please enter remarks">    
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
		   	url: "{{ route('leave.index') }}",
		  	},
		  	columns: [
			   {data: 'employee_no', name: 'employee_no'},
			   {data: 'full_name', name: 'full_name'},
			   {data: 'designation', name: 'designation'},
			   {data: 'from', name: 'from'},
			   {data: 'to', name: 'to'},
			   {data: 'leave_type', name: 'leave_type'},
			   {data: 'days', name: 'days'},
			   {data: 'status', name: 'status'},
			   {data: 'edit',name: 'edit', orderable: false, searchable: false },
			   @role('Super Admin')
			   {data: 'delete',name: 'delete', orderable: false, searchable: false }
			   @endrole
		  	]
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

	   	$('#saveBtn').unbind().click(function (e) {
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