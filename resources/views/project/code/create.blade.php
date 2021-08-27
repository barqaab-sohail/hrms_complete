@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Projects</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-12">
		            <div style="margin-top:10px; margin-right: 10px;">
		                <button type="button" onclick="window.location.href='{{route('project.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Projects</button>
		            </div>
		                 
		            <div class="card-body">
		                <form id= "formProject" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Project Code Generator</h3>
		                        
		                        <hr class="m-t-0 m-b-40">
                     

		                        <div class="row">
		                           
		                            <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">year<span class="text_requried">*</span></label>
		                                        
	                                           	<select  name="year"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($years as $year)
													<option value="{{$year}}" {{(old("year")==$year? "selected" : "")}}>{{$year}}</option>
                                                    @endforeach
                                                    
                                                    
                                                </select>
												
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                           
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Project No</label>
		                                       
	                                           	<input type="text"  id="project_code" name="project_no" value="{{ old('project_no') }}" readonly class="form-control" >
												
		                                    </div>
		                                </div>
		                            </div>
		                           		                            
		                        </div><!--/End Row-->
		                    </div> <!--/End Form Boday-->

		                    <hr>

		                    <div class="form-actions">
		                        <div class="row">
		                            <div class="col-md-6">
		                                <div class="row">
		                                    <div class="col-md-offset-3 col-md-9">
		                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Generate Code</button> 
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </form>
		        	</div> <!-- end card body -->    
		        </div> <!-- end col-lg-12 -->
		    </div> <!-- end row -->
        </div> <!-- end card card-outline-info -->
    </div> <!-- end col-lg-12 -->
</div> <!-- end row -->


<script>
$(document).ready(function() {

	//All Basic Form Implementatin i.e validation etc.
	formFunctions();

	$('#formProject').on('submit', function(event){
	 	//preventDefault work through formFunctions;
	 	
		url="{{route('projectCode.store')}}";
		$('.fa-spinner').show();	
	   	submitFormAjax(this, url,1);
	}); //end submit

	//ajax function
    function submitFormAjax(form, url, reset=0){
        //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });
 
        var data = new FormData(form)

       // ajax request
        $.ajax({
           url:url,
           method:"POST",
           data:data,
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
               	if(reset == 1){
	                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
	               // resetForm();
	                $('#project_code').val(data.code);
		            $('html,body').scrollTop(0);
		            $('.fa-spinner').hide();
		            clearMessage();
               	}else{
               		location.href = data.url;
               	}       	
               	
               },
            error: function (jqXHR, textStatus, errorThrown) {
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
	}


	 

});
</script>


@stop