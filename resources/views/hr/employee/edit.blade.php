@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Human Resource</h3>
	
		<h4>{{'Employee Name: '}} {{ucwords($data->first_name)}} {{ ucwords($data->last_name)}}</h4>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-2">
					@include('layouts.vButton.hrButton')
				</div>
      	
		        <div class="col-lg-10 addAjax">
		            @include('hr.employee.ajax')
		        </div> <!-- end col-lg-10 -->
		    </div> <!-- end row -->
        </div> <!-- end card card-outline-info -->
    </div> <!-- end col-lg-12 -->
</div> <!-- end row -->



@push('scripts')
<script>


$(document).ready(function() {
	
	$(document).on('click','#hr_salary_id',function(){
		$('.hideDiv').toggle();
	});


	$(document).on('click','i[id^=add]',function(){
		$('.hideDiv').toggle();
	});

	$(document).on('click','i[id^=store]',function(){
		var value = $(this).siblings('input').val();
		var url = $(this).attr('href');

		submitFormAjax(value, url);
	});

	formFunctions();


	//$('form[id^=form]').on('submit', function(event){
	$(document).on('submit','form[id^=form]', function(event){	
	 	var url = $(this).attr('action');
		
		//console.log(url);
		$('.fa-spinner').show();
		event.preventDefault();
	   	submitFormAjax(this, url);
	   	// console.log('OK');
	}); //end submit


	$('a[id^=add]').click(function(e){
		var url = $(this).attr('href');
		var id = $(this).attr('id');
		console.log(url);
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
        		$(".addAjax").html(data);
        		$('a[id^=add]').css('background-color','');
        		$('#'+id).css('background-color','#737373');
        		formFunctions();
        		console.log(data);
        		
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



@endpush

@stop