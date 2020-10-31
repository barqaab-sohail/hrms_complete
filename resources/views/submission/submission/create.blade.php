@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Submissions</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">New Submission Detail</a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-12">
		            <div style="margin-top:10px; margin-right: 10px;">
		                <button type="button" onclick="window.location.href='{{route('submission.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Submission</button>
		            </div>
		                 
		            <div class="card-body">
		                <form id= "formSubmission" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Submission Detail</h3>
		                        
		                        <hr class="m-t-0 m-b-40">

		                        <div class="row">
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Division<span class="text_requried">*</span></label>
		                                        
	                                           	<select  name="sub_division_id" id="sub_division_id" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($divisions as $division)
													<option value="{{$division->code}}" {{(old("sub_division_id")==$division->id? "selected" : "")}}>{{$division->name}}</option>
                                                    @endforeach   
                                                </select>
												
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-2" id="submission_type_div">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Submission Type<span class="text_requried">*</span></label>
		                                       	<select  name="sub_type_id" id="submission_type" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($subTypes as $subType)
													<option value="{{$subType->id}}" {{(old("sub_type_id")==$subType->id? "selected" : "")}}>{{$subType->name}}</option>
                                                    @endforeach   
                                                </select>
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-6"  id="eoi_reference_div">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">EOI Reference</label>
		                                       	<select  name="eoi_reference"  id="eoi_reference_no" class="form-control selectTwo">
                                                    <option value=""></option>
                                                    @foreach($eoiReferences as $eoiReference)
													<option value="{{$eoiReference->id}}" {{(old("eoi_reference")==$eoiReference->id? "selected" : "")}}>{{$eoiReference->project_name}}</option>
                                                    @endforeach   
                                                </select>
		                                    </div>
		                                </div>
		                            </div>
		                           
		                         		                           
		                        </div><!--/End Row-->

		                        <div class="row">
		                            <div class="col-md-8">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Name of Project<span class="text_requried">*</span></label><br>

		                                       	<input type="text"  name="project_name" value="{{ old('project_name') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Enter Name of Submission Project">
		                                    </div>
		                                </div>
		                            </div>
		                           
		                             <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Client Name<span class="text_requried">*</span></label>
		                                       		<select  name="client_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($clients as $client)
													<option value="{{$client->id}}" {{(old("client_id")==$client->id? "selected" : "")}}>{{$client->name}}</option>
                                                    @endforeach
                                                    
                                                </select>
		                                        
		                                       
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!--/End Row-->

		                        <div class="row">
		                        	
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Submission Date<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text"  name="submission_date" value="{{ old('submission_date') }}" class="form-control date_input" data-validation="required" readonly>
												
												<br>
		                                        @can('submission edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Submission Time<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text"  name="submission_time" id="submission_time" value="{{ old('submission_time') }}" class="form-control time_input" data-validation="required" readonly>
												
												<br>
		                                        @can('submission edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-8">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Submission Address<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text"  name="address" value="{{ old('address') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Enter Address where document submitted">
												
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!--/End Row-->

		                        <div class="row">
		                            <div class="col-md-3">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Designation<span class="text_requried">*</span></label>
		                                       	<input type="text"  name="designation" value="{{ old('designation') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Designation of Client Representative">

		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                    	<label class="control-label text-right">Phone No.</label>
		                                    	<input type="text" name="landline" value="{{ old('landline') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
		                                       
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Fax No.</label>
		                                    	<input type="text" name="fax" value="{{ old('fax') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       <label class="control-label text-right">Mobile No.</label>
                                
				                               <input type="text" name="mobile" id="mobile" pattern="[0-9.-]{12}" title= "11 digit without dash" value="{{ old('mobile') }}" data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0345-0000000">		                                       
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-3">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
			                                    <label class="control-label text-right">Email</label>
                                
					                            <input type="email" name="email" value="{{ old('emal') }}" data-validation="length"  data-validation-length="max50" class="form-control" placeholder="Enter Email Address">
												
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
		                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Add Submission</button> 
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

	$("#eoi_reference_div").hide();

	$('#submission_type').change(function(){
 		if($('#submission_type').val() == '3'){ // or this.value == 'volvo'
    		$("#eoi_reference_div").show();
 		}else{
 			$('#eoi_reference_no').val('').select2('val', 'All');
 			$("#eoi_reference_div").hide();
 		}

	});


	//All Basic Form Implementatin i.e validation etc.
	formFunctions();
	$('#formSubmission').on('submit', function(event){
	 	//preventDefault work through formFunctions;
		url="{{route('submission.store')}}";
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
	                //resetForm();
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