@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Asset Management</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">New Asset</a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-12">
		            <div style="margin-top:10px; margin-right: 10px;">
		                <button type="button" onclick="window.location.href='{{route('asset.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Asset</button>
		            </div>
		            <div class="card-body">
		                <form id= "formAsset" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Asset Detail</h3>
		                        
		                        <hr class="m-t-0 m-b-40">
                     
		                        <div class="row">
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Class<span class="text_requried">*</span></label>
		                                        
	                                           	<select  name="as_class_id"  id= "as_class_id" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($asClasses as $class)
													                         <option value="{{$class->id}}" {{(old("as_class_id")==$class->id? "selected" : "")}}>{{$class->name}}</option>
                                                    @endforeach     
                                              </select>
                                              @can('asset add class')
                                              <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#assetClassModal"><i class="fas fa-plus"></i>
                                              </button>
                                              @endcan 
												
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-3">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Sub Classes<span class="text_requried">*</span></label> 
	                                           	<select  name="as_sub_class_id"  id="as_sub_class_id"class="form-control selectTwo" data-validation="required" data-placeholder="First Select Class">  
                                              </select>
                                              @can('asset add class')
                                              <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#assetSubClassModal"><i class="fas fa-plus"></i>
                                              </button>
                                              @endcan 
		                                    </div>
		                                </div>
		                            </div>
		                           
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Asset Code</label>
		                                            
		                                        <input type="text" id="asset_code" name="asset_code" value="{{ old('asset_code') }}" class="form-control" readonly>
												
		                                    </div>
		                                </div>
		                            </div>

		                            <div class="col-md-5">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Description</label>
		                                            
		                                        <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control exempted" data-validation="required">
												
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!--/End Row-->

		                        <div class="row">
		                            <div class="col-md-2"></div>
		                            <div class="col-md-5">
		                                 <div class="form-group row">
					                        <center >
					                        <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="100" >
					                        
					                        </input>
					                        <input type="file"  name="document" id="view" data-validation="required" class="form-control" hidden>
	
					                        <h6 id="h6" class="card-title m-t-10">Click On Image to Add Image<span class="text_requried">*</span></h6>
					                        </center>
					                       
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
		                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Add Asset</button> 
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </form>
                    @can('asset add class')
                    @include('asset.classModal')
                    @include('asset.subClassModal')
                    @endcan
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

	$('#formAsset').on('submit', function(event){
	 	//preventDefault work through formFunctions;
		url="{{route('asset.store')}}";
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

	//Add Sub_Class

	$('#as_class_id').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('hrms/asset/sub_classes')}}"+"/"+cid,

             success:function(res)
             {       
                  
                  if(res)
                  {
                    $("#as_sub_class_id").empty();
                      $("#as_sub_class_id").append('<option value="">Select Sub Classes</option>');
                      $.each(res,function(key,value){
                          $("#as_sub_class_id").append('<option value="'+key+'">'+value+'</option>');
                          
                      });
                       $('#as_sub_class_id').select2('destroy');
                       $('#as_sub_class_id').select2(
                       	 {width: "100%",
       					 theme: "classic"});

                  }
             }

          });//end ajax
        }
    });

	//get asset code

	$('#as_sub_class_id').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('hrms/asset/as_code')}}"+"/"+cid,

             success:function(res)
             {       
                if(res)
                  {
                    $("#asset_code").val(res.assetCode);
                  }
             }

          });//end ajax
        }
    });


    

  	//Image Perview

	$("#view").change(function(){
            var fileName = this.files[0].name;
            var fileType = this.files[0].type;
            var fileSize = this.files[0].size;
            //var fileType = fileName.split('.').pop();
            
        //Restrict File Size Less Than 1MB
        if (fileSize> 1024000){
            alert('File Size is bigger than 1MB');
            $(this).val('');
        }else{
            //Restrict File Type
            if ((fileType =='image/jpeg') || (fileType=='image/png')){
               
                readURL(this);
                document.getElementById("h6").innerHTML = "Image is Attached";
            }else if(fileType=='application/pdf'){
                readURL(this);// for Default Image
                
                document.getElementById("pdf").src="{{asset('Massets/images/document.png')}}";  
                $( "#pdf" ).show();
            }else{
                alert('Only PDF, JPG and PNG Files Allowed');
            $(this).val('');
            }
        }
        
    });

        function readURL(input) {
            var fileName = input.files[0].name;
            var fileType = input.files[0].type;
                                
            //Read URL if image
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                    reader.onload = function (e) {
                        $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow').attr('width','100%');
                    }
                    reader.readAsDataURL(input.files[0]);
            }
        }
            
        $("#wizardPicturePreview" ).click (function() {
           $("input[id='view']").click();
        });
	 

});
</script>


@stop