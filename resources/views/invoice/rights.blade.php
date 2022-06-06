@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor"></h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<div class="row">
            <div class="card-body">
                <form id="invoiceRightsForm" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-body">
                    <h3 class="box-title">Invoice Rights</h3>
                    <hr class="m-t-0 m-b-40">
                    	<div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       	<label class="control-label text-right">Employee Name<span class="text_requried">*</span></label><br>

                                       		<select  name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                                                <option value=""></option>
                                                @foreach($employees as $employee)
												<option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                                                @endforeach       
                                            </select>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-7">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       	<label class="control-label text-right">Project Name<span class="text_requried">*</span></label>
                                        
                                        <select  name="pr_detail_id" id="pr_detail_id" class="form-control selectTwo" data-validation="required">
                                                <option value=""></option>
                                                @foreach($projects as $project)
												<option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->name}}</option>
                                                @endforeach       
                                            </select>
                                    </div>
                                </div>
                            </div>
                             <!--/span-->
                            <div class="col-md-2">
                                <div class="form-actions">
			                        <div class="row">
			                            <div class="col-md-6">
			                                <div class="row">
			                                    <div class="col-md-offset-3 col-md-9">
			                                    	<br>
			                                        <button id="saveBtn" type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Add Rights</button> 
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
		                    	</div>
		                    </div><!--/End Row-->
                    <hr>
                    </div>
                </form>
            </div>
        </div>   
	</div>
		<table class="table table-bordered data-table">
    		<thead>
		      <tr>
		          <th>Employee Name</th>
		          <th>Project Name</th>
		          <th>Delete</th>
		      </tr>
    		</thead>
	   		<tbody> 
	    	</tbody>
	  	</table>
</div>



<script>
$(document).ready(function() {

formFunctions();
	$(function () {
      	$.ajaxSetup({
          	headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          	}
    	});


   
    	$('#saveBtn').unbind().click(function (e) {
	        e.preventDefault();
	        $(this).html('Save.......');
	         
		        $.ajax({
		          data: $('#invoiceRightsForm').serialize(),
		          url: "{{route('invoiceRights.store')}}",
		          type: "POST",
		          dataType: 'json',
		          success: function (data) {
		     
		     		table.draw();
		            //$('#invoiceRightsForm').trigger("reset");
		            
		            $('#saveBtn').html('Add Rights');
		        
		          },
		          error: function (data) {
		             
		              var errorMassage = '';
		              $.each(data.responseJSON.errors, function (key, value){
		                errorMassage += value + '<br>';  
		                });
		                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

		              $('#saveBtn').html('Save Changes');
		          }
		      	});
    	});

    	$('#hr_employee_id').change(function(){
    	$("#pr_detail_id").val('').select2('val', 'All');
		var cid = $(this).val();
      	var url = "{{url('invoice/invoiceRights')}}"+"/"+cid;
			table = $('.data-table').DataTable({
		        processing: true,
		        serverSide: true,
		        destroy: true,
		        ajax: url,
		        columns: [
		            {data: "fullName", name: 'hr_employee_id'},
		            {data: 'projectName', name: 'pr_detail_id'},
		            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
		        ]
	    	});
   		}); 

	});

	
	

    $('body').on('click', '.deleteRight', function () {
     
        var right_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('invoiceRights.store') }}"+'/'+right_id,
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

            
});
</script>

@stop