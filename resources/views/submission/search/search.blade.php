@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Search Submission</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Search Submission</h4>
		<hr>
		<form id="submissionSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('submission.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">
            	<div class="row" >
		            <div class="col-md-6">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Name of Client</label><br>
		                   		<select  name="client_id"  id="client_id" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($clients as $client)
									<option value="{{$client->id}}" {{(old("client_id")==$client->id? "selected" : "")}}>{{$client->name}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div> 
		            <div class="col-md-3">
		            	<!--/span 5-2 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Date From</label><br>
		                   		 <input type="text" id="from_date" name="from_date" value="{{ old('from_date') }}" class="form-control date_input" readonly>     
	                            <br>
	                            <i class="fas fa-trash-alt text_requried"></i>
		                   		
		                    </div>
		                </div>
		            </div>  
		            <div class="col-md-3">
		            	<!--/span 5-2 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Date To</label><br>
		                   		 <input type="text" id="to_date" name="to_date" value="{{ old('to_date') }}" class="form-control date_input" readonly>     
	                            <br>
	                            <i class="fas fa-trash-alt text_requried"></i>
		                   		
		                    </div>
		                </div>
		            </div>        
		        </div>
		        <div class="row" >
		        	<div class="col-md-6">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Submission Status</label><br>
		                   		<select  name="sub_status_id"  id="sub_status_id" class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($subStatuses as $subStatus)
									<option value="{{$subStatus->id}}" {{(old("sub_status_id")==$subStatus->id? "selected" : "")}}>{{$subStatus->name}}</option>
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
    	
	//submit function
      $("#submissionSearch").submit(function(e) { 

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
	           data: $('#submissionSearch').serialize(),
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