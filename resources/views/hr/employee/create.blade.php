@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Human Resource</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">New Employee</a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-12">
		            <div style="margin-top:10px; margin-right: 10px;">
		                <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
		            </div>
		                 
		            <div class="card-body">
		                <form id= "formEmployee" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Employee Information</h3>
		                        
		                        <hr class="m-t-0 m-b-40">

		                        <div class="row">
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">First Name<span class="text_requried">*</span></label><br>

		                                       	<input type="text"  name="first_name" value="{{ old('first_name') }}"  class="form-control" data-validation="required" placeholder="Enter First Name">
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Last Name<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control " data-validation="required" placeholder="Enter Last Name" >
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Father Name<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-control " data-validation="required" placeholder="Enter Father Name" >
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!--/End Row-->

		                        <div class="row">
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Date of Birth<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control date_input" data-validation="required" readonly>
												
												<br>
		                                        @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">CNIC<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text" name="cnic" id="cnic" pattern="[0-9.-]{15}" title= "13 digit without dash" value="{{ old('cnic') }}" class="form-control" data-validation="required" placeholder="Enter CNIC without dash" >
		                                        <span id="cnicCheck" class="float-right" style="color:red"></span>
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">CNIC Expiry<span class="text_requried">*</span></label>
		                                            
		                                        <input type="text" id="cnic_expiry" name="cnic_expiry" value="{{ old('cnic_expiry') }}" class="form-control date_input"  data-validation="required" readonly >
												
												<br>
		                                        @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!--/End Row-->

		                        <div class="row">
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Gender<span class="text_requried">*</span></label>
		                                        
	                                           	<select  name="gender_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($genders as $gender)
													<option value="{{$gender->id}}" {{(old("gender_id")==$gender->id? "selected" : "")}}>{{$gender->name}}</option>
                                                    @endforeach
                                                    
                                                </select>
												
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Marital Status<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="marital_status_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($maritalStatuses as $maritalStatus)
													<option value="{{$maritalStatus->id}}" {{(old("marital_status_id")==$maritalStatus->id? "selected" : "")}}>{{$maritalStatus->name}}</option>
                                                    @endforeach
                                                  
                                                </select>
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Religion<span class="text_requried">*</span></label>
		                                       
	                                           	<select  name="religion_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($religions as $religion)
													<option value="{{$religion->id}}" {{(old("religion_id")==$religion->id? "selected" : "")}}>{{$religion->name}}</option>
                                                    @endforeach
                                                  
                                                </select>
												
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
		                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Add Employee</button> 
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

	$('#cnic').blur(function(){ 
		$('#cnicCheck').fadeOut(); 
        var query = $(this).val();
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"{{ route('employee.cnic') }}",
          method:"Post",
          data:{query:query, _token:_token},
	         	success:function(data){
		          	if(data.status == 'Not Ok'){
		           		$('#cnicCheck').fadeIn();  
		                  $('#cnicCheck').html('This CNIC is already entered');
		            }
	          	}
         });
        }
    });


	//All Basic Form Implementatin i.e validation etc.
	formFunctions();

	$('#formEmployee').on('submit', function(event){
	 	//preventDefault work through formFunctions;
		url="{{route('employee.store')}}";
		$('.fa-spinner').show();	
	   	submitFormAjax(this, url);
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
	                resetForm();
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