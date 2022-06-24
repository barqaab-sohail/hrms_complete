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
	                <form id="degreeForm" name="degreeForm" action="{{route('degree.store')}}"class="form-horizontal">
	                   <input type="hidden" name="degree_id" id="degree_id">
	                   	<div class="row">
			                <div class="col-md-7">
				                <div class="form-group">
			                        <label class="control-label text-right">Degree Name<span class="text_requried">*</span></label>
			                        <input type="text" name="degree_name" id="degree_name" value="{{ old('degree_name') }}" class="form-control">    
		                    	</div>
			                </div>
			                <div class="col-md-2">
			                	<div class="form-group">
		                        	<label class="control-label text-right">Level</label><br>
		                        	<select  name="level"  id="level" class="form-control selectTwo" >

	                                <option value=""></option>
	                                @for ($i = 5; $i < 23; $i++)
	                                <option value="{{$i}}">{{$i}}</option>
	                                @endfor
	                                </select>
		                          	
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
			<button type="button" class="btn btn-success float-right"  id ="createDegree" data-toggle="modal">Add Degree</button>
			<h4 class="card-title" style="color:black">List of Degrees</h4>
			<div class="table-responsive m-t-40">	
				<table id="myTable" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th style="width:15%">Degree Name</th>
						<th style="width:20%">Level</th>
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
			   	url: "{{ route('degree.index') }}",
			  	},
			  	columns: [
			  		{data: 'degree_name', name: 'degree_name'},
			  		{data: 'level', name: 'level'},
				   	{data: 'edit',name: 'edit', orderable: false, searchable: false },
				   	@role('Super Admin')
				   	{data: 'delete',name: 'delete', orderable: false, searchable: false }
				   	@endrole
			  	]
	 		});

	 		$('#createDegree').click(function (e) {
	 			$('#degree_id').val('');
	 			$('#degree_name').val('');
			    $('#level').val('').change();
		        $('#ajaxModel').modal('show');
	 		});

	 		$('body').unbind().on('click', '.editDegree', function () {
			      var degree_id = $(this).data('id');
			      $.get("{{ url('hrms/misc/degree') }}" +'/' + degree_id +'/edit', function (data) {
			          $('#modelHeading').html("Edit Degree");
			          $('#saveBtn').val("edit-Degree");
			          $('#degree_id').val(data.id);
			          $('#degree_name').val(data.degree_name);
			          $('#level').val(data.level).change();
			          
			      }).done(function() {
			      	
			      	$('#ajaxModel').modal('show');
			      })
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
		          data: $('#degreeForm').serialize(),
		          url: "{{ route('degree.store') }}",
		          type: "POST",
		          dataType: 'json',
		          success: function (data) {
		              $('#ajaxModel').modal('hide');
		              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
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

	    	$('body').on('click', '.deleteDegree', function () {
		        var degree_id = $(this).data("id");
		        var con = confirm("Are You sure want to delete !");
		        if(con){
		          $.ajax({
		            type: "DELETE",
		            url: "{{ route('degree.store') }}"+'/'+degree_id,
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