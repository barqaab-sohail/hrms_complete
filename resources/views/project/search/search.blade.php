@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Search Project Document</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Search Project Document</h4>
		<hr>
		<form id="prDocumentSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('project.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

				<div class="row Search" >
					<div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Reference</label><br>
		                   		<input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}" class="form-control exempted" data-validation="length"  data-validation-length="max190" placeholder="Enter Document Reference" >
		                   		
		                        
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-7">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Descriptin</label><br>
		                   		<input type="text" name="description"  value="{{ old('description') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter Document Detail" >
		                   		
		                    </div>
		                </div>
		            </div>
		            <!--/span 5-2 -->
		            <div class="col-md-2">
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                    	<label class="control-label">Date</label>
		                    	<input type="text" name="document_date"  value="{{ old('document_date') }}" class="form-control date_input" data-validation="required"  autocomplete="off">

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
	                            <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Searcdh</button>
	                            
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

	//submit function
      $("#prDocumentSearch").submit(function(e) { 
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
	           data: $('#prDocumentSearch').serialize(),
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

    $(".date_input").datepicker({
     onSelect: function(value, ui) {
        $(this).siblings('i').show();
        var today = new Date(), 
            age = today.getFullYear() - ui.selectedYear;
        $('#age').text(age+' years total age');
    },
    dateFormat: 'D, d-M-yy',
    yearRange: '1935:'+ (new Date().getFullYear()+15),
    changeMonth: true,
    changeYear: true,

    });

});

</script>



@stop