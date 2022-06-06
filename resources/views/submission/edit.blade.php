@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Submission Name</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">{{$data->project_name??''}}</a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-2">
	@include('layouts.vButton.subButton')
	</div>
</div>

<div class="row justify-content-end">
    <div class="col-lg-12 reducedCol">
        <div class="card card-outline-info">
			<div class="row">
				<!-- <div class="col-lg-2">
					
				</div>

 -->
		        <div class="col-lg-12 addAjax">
		            @include('submission.ajax')
		        </div> <!-- end col-lg-12 -->
		    </div> <!-- end row -->
        </div> <!-- end card card-outline-info -->
    </div> <!-- end col-lg-12 -->
</div> <!-- end row -->


<script>
$(document).ready(function() {

	//All Basic Form Implementatin i.e validation etc.
	formFunctions();

	//form submit
	$(document).on('submit','#formSubmission', function(e){	
  //$('#formProject').on('submit', function(event){
      
    // prevent default call from function formFunctions()
	 	var url = $(this).attr('action');;
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
        	$(".addAjax").empty();
            $(".addAjax").html(data);
        		$('a[id^=add]').css('background-color','');
        		$('#'+id).css('background-color','#737373');
        		formFunctions();
        		
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