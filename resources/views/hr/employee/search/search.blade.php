@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Search</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Search</h4>
		<hr>
		<form id="employeeSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('employee.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">
            	<div class="row" >
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Employee</label><br>
		                   		<select  name="employee"  id="employee" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($employees as $employee)
									<option value="{{$employee->id}}" {{(old("employee")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employeeDesignation->last()->name??''}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Documents</label><br>
		                   		<input type="text"  name="document_name" id="document_name"  value="{{ old('document_name') }}"  class="form-control" placeholder="Enter Document Name">
		                    </div>
		                </div>
		            </div>     
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Blood Group</label><br>
		                   		<select  name="blood_group"  id="blood_group" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($bloodGroups as $bloodGroup)
									<option value="{{$bloodGroup->id}}" {{(old("blood_group")==$bloodGroup->id? "selected" : "")}}>{{$bloodGroup->name}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of HOD</label><br>
		                   		<select  name="manager"  id="manager" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($managers as $manager)
									<option value="{{$manager->id}}" {{(old("manager")==$manager->id? "selected" : "")}}>{{$manager->first_name}} {{$manager->last_name}} - {{$manager->employeeDesignation->last()->name??''}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>            
		        </div>
				<div class="row" >
					<div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Category</label><br>
		                   		<select  name="category"  id="category" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($categories as $category)
									<option value="{{$category->id}}" {{(old("category")==$category->id? "selected" : "")}}>Category - {{$category->name}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>     
		            <!--Span-2
		             -->
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Degree</label><br>
		                   		<select  name="degree"  id="degree" class="form-control searchSelect" >
		                            <option value=""></option>
		                            
		                            @foreach($degrees as $degree)
									<option value="{{$degree->id}}" {{(old("degree")==$degree->id? "selected" : "")}}>{{$degree->degree_name}}</option>
		                            @endforeach
		                          
		                        </select>
		                    </div>
		                </div>
		            </div>     
		             <!--Span-3
		             -->
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Designation/Position</label><br>
		                   		<select  name="designation"  id="designation" class="form-control searchSelect" >
		                            <option value=""></option>
		                            
		                            @foreach($designations as $designation)
									<option value="{{$designation->id}}" {{(old("designation")==$designation->id? "selected" : "")}}>{{$designation->name}}</option>
		                            @endforeach
		                          
		                        </select>
		                    </div>
		                </div>
		            </div>     
		            <!--Span-4
		             -->
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Project</label><br>
		                   		<select  name="project"  id="project" class="form-control searchSelect" >
		                            <option value=""></option>
		                            
		                            @foreach($projects as $project)
									<option value="{{$project->id}}" {{(old("project")==$project->id? "selected" : "")}}>{{$project->project_no??''}} - {{$project->name}}</option>
		                            @endforeach
		                          
		                        </select>
		                    </div>
		                </div>
		            </div>     
		        </div>
		    </div>

	        <div class="form-actions">
	            <div class="row">
	                <div class="col-md-6">
	                    <div class="row"> 
	                       <div class="col-md-offset-3 col-md-9">
	                            <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Search</button>
	                            
	                        </div>
	                     
	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>
		                           
		<hr>
		<div class="row">
            <div class="col-md-12 table-container">
           
            
            </div>
        </div>
		
	</div>
</div>


<script>
$(document).ready(function(){
	$('.fa-spinner').hide();
	$('select').select2();
	formFunctions();

	$('select').change(function(){	
		
			$(this).removeClass('searchSelect');
			if($(this).attr("id") != "employee"){
				$('#document_name').val('');
			}
	  		$('.searchSelect').each(function () {
	  			if($(this).val()){
	        	$(this).val('').select2('val', 'All');
	        	}
	    	});
	    	$(this).addClass('searchSelect');
	   
  	});

  	$('#document_name').focus(function(){
  		$('.searchSelect').each(function () {
        	if($(this).val() && $(this).attr("id") != "employee"){
        		$(this).val('').select2('val', 'All');
        	}
    	});
  	});

    	

	//submit function
      $("#employeeSearch").submit(function(e) { 

      e.preventDefault();
      $('.fa-spinner').show();
      var url = $(this).attr('action');
      
	      //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
	        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
	            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

	            if (token) {
	                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
	            }
	        });
	     
	       // ajax request
	        $.ajax({
	           url:url,
	           method:"GET",
	           data: $('#employeeSearch').serialize(),
	           //dataType:'JSON',
	           contentType: false,
	           cache: false,
	           processData: false,
	           success:function(data){
	          
      			$('div.table-container').html(data);
	            $('.fa-spinner').hide();

	           },
	            error: function (jqXHR, textStatus, errorThrown){
	                if (jqXHR.status == 401){
	                    location.href = "{{route ('login')}}"
	                    }      
	                var test = jqXHR.responseJSON // this object have two more objects one is errors and other is message.
	                
	                var errorMassage = '';

	                //now saperate only errors object values from test object and store in variable errorMassage;
	                $.each(test.errors, function (key, value){
	                errorMassage += value + '<br>';  
	                });
	                 $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
	                 $('html,body').scrollTop(0);
	                $('.fa-spinner').hide();                   
	                    
	            }//end error
	    }); //end ajax
      
    });


});

</script>



@stop