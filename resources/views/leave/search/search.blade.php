@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Leave Search</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Leave Search</h4>
		<hr>
		<form id="leaveSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('leave.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">
            	<div class="row" >
		            <div class="col-md-4">
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
		            <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Leave From</label>
                            <input type="text" id="from" name="from" value="{{ old('from') }}" class="form-control date_input"  readonly>
                              <i class="fas fa-trash-alt text_requried"></i>

                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Leave To</label>
                                <input type="text" id="to" name="to" value="{{ old('to') }}" class="form-control date_input"  readonly>

                                  <i class="fas fa-trash-alt text_requried"></i>
                            </div>
                        </div>
                    </div>
                   	<div class="col-md-4">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Leave Balance</label><br>
		                   		<select  name="leave_balance"  id="leave_balance" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($employees as $employee)
									<option value="{{$employee->id}}" {{(old("employee")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employeeDesignation->last()->name??''}}</option>
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
  		$('.searchSelect').each(function () {
  			if($(this).val()){
        	$(this).val('').select2('val', 'All');
        	}

    	});
    	$(this).addClass('searchSelect');
  	});
	$('#leave_balance').change(function(){
		$('#from').val('');
		$('#to').val('');
		$('.fa-trash-alt').hide();
	});

	$('#from, #to').focus(function(){	
    	if($('#leave_balance').val()){
    	$('#leave_balance').val('').select2('val', 'All');
    	}
  	});

	//submit function
      $("#leaveSearch").submit(function(e) { 

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
	           data: $('#leaveSearch').serialize(),
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