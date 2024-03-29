@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Search User Log</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Search User Log</h4>
		<hr>
		<form id="auditSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('audit.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

				<div class="row Search" >
					<div class="col-md-4">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12">
		                   		<label class="control-label text-right">User Name</label><br>
		                   		<select  name="user"  class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($users as $user)
									<option value="{{$user->id}}" {{(old("user")==$user->id? "selected" : "")}}>{{employeeFullName($user->hrEmployee->id)}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-5">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12">
		                   		<label class="control-label text-right">Model Name</label><br>
		                   		<select  name="model"  class="form-control searchSelect">
		                            <option value=""></option>
		                            @foreach($models as $model)
									<option value="{{$model}}">{{$model}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>          
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Number of Records</label><br>
		                   		<input type="text" name="total_records" id="total_records" value="{{ old('total_records') }}" class="form-control" placeholder="Enter Total Number of Records" data-validation="required">
		                   		
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
  			
        	$(this).val('').select2('val', 'All');
    	});
    	$(this).addClass('searchSelect');
  	});

	$('#total_records').keyup(function(event) {

	    // skip for arrow keys
	    if(event.which >= 37 && event.which <= 40) return;

	    // format number
	    $(this).val(function(index, value) {
	      return value
	      .replace(/\D/g, "")
	      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
	      ;
	    });
  	});

	//submit function
      $("#auditSearch").submit(function(e) { 
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
	           data: $('#auditSearch').serialize(),
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