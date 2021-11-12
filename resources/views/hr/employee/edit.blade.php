@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Human Resource</h3>
	
		<h4>{{'Employee Name: '}} {{ucwords($data->first_name)}} {{ ucwords($data->last_name)}}</h4>
@stop
@section('content')
<div class="row">
    <div class="col-lg-2">
      @include('layouts.vButton.hrButton')
    </div>
</div>


<div class="row justify-content-end">
    <div class="col-lg-12 reducedCol">
        <div class="card card-outline-info">
          <div class="row">
  		      <div class="col-lg-12 addAjax">
  		        @include('hr.employee.ajax')
  		      </div> <!-- end col-lg-12 -->
  		    </div> <!-- end row -->
        </div> <!-- end card card-outline-info -->
    </div> <!-- end col-lg-12 -->
</div> <!-- end row -->


<script>


$(document).ready(function() {
  formFunctions();
  isUserData(window.location.href, "{{URL::to('/hrms/employee/user/data')}}");

  

	//form submit
	$(document).on('submit','#formEditEmployee', function(e){	
  //$('#formEditEmployee').submit(function (e){ 
      
    // prevent default call from function formFunctions()
	 	var url = $(this).attr('action');
  
		$('.fa-spinner').show();
	   	submitForm(this, url);
	}); //end submit



// Vertical Button function to load page through ajax 
	$('a[id^=add]').click(function(e){
		var url = $(this).attr('href');
		var id = $(this).attr('id');
		e.preventDefault();

		$.ajax({
           url:url,
           method:"GET",
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
          		    if(data.status == 'Not Ok'){
                    $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                  }else{
                  $(".addAjax").html(data);
              		$('a[id^=add]').css('background-color','');
              		$('#'+id).css('background-color','#737373');
              		formFunctions();
        		      }
               },
            error: function (jqXHR, textStatus, errorThrown){
            	if (jqXHR.status == 401){
            		location.href = "{{route ('login')}}"
            		}      
                          

                }//end error
    	}); //end ajax	

	});


});
</script>



@stop