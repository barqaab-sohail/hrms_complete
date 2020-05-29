@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Projects</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">Edit Project Detail</a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
				<div class="col-lg-2">
					@include('layouts.vButton.prButton')
				</div>


		        <div class="col-lg-10 addAjax">
		            @include('project.detail.ajax')
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
	$(document).on('submit','#formProject', function(e){	
  //$('#formProject').on('submit', function(event){
      
    // prevent default call from function formFunctions()
	 	var url = "{{route('project.update',$data->id)}}";
   		console.log('Submit');
		$('.fa-spinner').show();
	   	submitForm(this, url);
	}); //end submit




	// Vertical Button function to load page through ajax 
	$('a[id^=add]').click(function(e){
		var url = $(this).attr('href');
		var id = $(this).attr('id');
		e.preventDefault();
		console.log('get');
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