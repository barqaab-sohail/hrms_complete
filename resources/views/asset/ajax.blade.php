

<div style="margin-top:10px; margin-right: 10px;">
    <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Asset</button>
</div>
     
<div class="card-body">
    <form id= "formEditAsset" method="post" action="{{route('asset.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
        <div class="form-body">
                
            <h3 class="box-title">Edit Asset Detail</h3>
            
            <hr class="m-t-0 m-b-40">
 
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Class<span class="text_requried">*</span></label>
                            
                           	<select  name="as_class_id"  id= "as_class" class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($asClasses as $class)
								<option value="{{$class->id}}" {{(old("as_class_id", $data->asClass->as_class_id)==$class->id? "selected" : "")}}>{{$class->name}}</option>
                                @endforeach     
                            </select>
							
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Sub Classes<span class="text_requried">*</span></label> 
                           	<select  name="as_sub_class_id"  id="as_sub_class"class="form-control selectTwo" data-validation="required" data-placeholder="First Select Class"> 
                           	<option value="{{$data->as_sub_class_id}}" {{(old("as_sub_class_id", $data->asSubClass->id)==$data->as_sub_class_id? "selected" : "")}}>{{$data->asSubClass->name}}</option> 
                            </select>
                        </div>
                    </div>
                </div>
               
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Asset Code</label>
                                
                            <input type="text" id="asset_code" name="asset_code" value="{{ old('asset_code', $data->asset_code) }}" class="form-control" readonly>
							
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Description</label>
                                
                            <input type="text" id="description" name="description" value="{{ old('description', $data->description) }}" class="form-control" data-validation="required">
							
                        </div>
                    </div>
                </div>
            </div><!--/End Row-->

            <div class="row">
                 <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Purchase Cost</label>
                            <input type="text" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost',$data->asPurchase->purchase_cost) }}" class="form-control" placeholder="Enter Total Cost">
                           	
                 
							
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Purchase Date<span class="text_requried">*</span></label>
                            
                            <input type="text" id="purchase_date" name="purchase_date" value="{{ old('purchase_date',$data->asPurchase->purchase_date) }}" class="form-control date_input" data-validation="required" readonly>
							
							<br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                        </div>
                	</div>
                </div>

                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Purchase Condition<span class="text_requried">*</span></label>
                            
                           	<select  name="as_purchase_condition_id" class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($asPurchaseConditions as $asPurchaseCondition)
								<option value="{{$asPurchaseCondition->id}}" {{(old("as_purchase_condition_id", $data->asPurchaseCondition->id)==$asPurchaseCondition->id? "selected" : "")}}>{{$asPurchaseCondition->name}}</option>
                                @endforeach     
                            </select>
							
                        </div>
                    </div>
                </div>

                 <div class="col-md-5">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Ownership<span class="text_requried">*</span></label>
                            
                           	<select  name="client_id" class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($asOwnerships as $asOwnership)
								<option value="{{$asOwnership->id}}" {{(old("client_id", $data->asOwnership->id)==$asOwnership->id? "selected" : "")}}>{{$asOwnership->name}}</option>
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
                           	<label class="control-label text-right">Asset Location<span class="text_requried">*</span></label>
                            
                           	<select  name="asset_location" id="asset_location" class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                <option value="1"@if($data->asLocationFirst) selected="selected" @endif>Placed at</option>
                                 <option value="2"@if($data->asAllocationFirst) selected="selected" @endif>Handover to</option>  
                            </select>
							
                        </div>
                    </div>
                </div>
                
                @if(!$data->asLocationFirst && !$data->asAllocationFirst) 
                	<div class="col-md-3 blank">
             
                </div>
                  @endif
                <div class="col-md-3 @if(!$data->asLocationFirst) hide @endif office">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Office<span class="text_requried">*</span></label>
                            
                           	<select  name="office_id" id="office_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($offices as $office)
								<option value="{{$office->id}}" {{(old("office_id",$data->asLocationFirst->office_id??'')==$office->id? "selected" : "")}}>{{$office->name}}</option>
                                @endforeach     
                            </select>
							
                        </div>
                    </div>
                </div>
                <div class="col-md-3 @if(!$data->asAllocationFirst) hide @endif  employee">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Employee<span class="text_requried">*</span></label>
                            
                           	<select  name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($employees as $employee)
								<option value="{{$employee->id}}" {{(old("hr_employee_id",$data->asAllocationFirst->hr_employee_id??'')==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}}</option>
                                @endforeach     
                            </select>
							
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Asset Condition<span class="text_requried">*</span></label>
                            
                           	<select  name="as_condition_type_id" class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($asConditionTypes as $asConditinoType)
								<option value="{{$asConditinoType->id}}" {{(old("as_condition_type_id",$data->asCondition->id)==$asConditinoType->id? "selected" : "")}}>{{$asConditinoType->name}}</option>
                                @endforeach     
                            </select>
							
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                     <div class="form-group row">
                        <center >

                        
                        <img src="{{asset(isset($data->asDocumentation->file_name)? 'storage/'.$data->asDocumentation->path.$data->asDocumentation->file_name: 'Massets/images/document.png') }}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="100" >
                       
                        <input type="file"  name="document" id="view" @if(!$data->asDocumentation->file_name)data-validation="required" @endif class="form-control" hidden>

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
</div> <!-- end card body -->    




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

	$('#as_class').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('hrms/asset/sub_classes')}}"+"/"+cid,

             success:function(res)
             {       
                  
             	console.log(res);
                  if(res)
                  {
                    $("#as_sub_class").empty();
                      $("#as_sub_class").append('<option value="">Select Sub Classes</option>');
                      $.each(res,function(key,value){
                          $("#as_sub_class").append('<option value="'+key+'">'+value+'</option>');
                          
                      });
                       $('#as_sub_class').select2('destroy');
                       $('#as_sub_class').select2(
                       	 {width: "100%",
       					 theme: "classic"});

                  }
             }

          });//end ajax
        }
    });


  //get asset code

  $('#as_sub_class').change(function(){
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
    

    $('#asset_location').change(function(){
    	 var option = $(this).val();
    	 $('#hr_employee_id').val('').select2('val', 'All');
    	 $('#office_id').val('').select2('val', 'All');
    	if(option==1){
    		$(".blank").addClass('hide');
    		$(".office").removeClass('hide');
    		$("#office_id").attr("data-validation", "required");
    		$("#hr_employee_id").removeAttr("data-validation");
    		$(".employee").addClass('hide');
    		$('#hr_employee_id').val('').select2('val', 'All');

    	} else if(option==2){
    		$(".blank").addClass('hide');
    		$(".employee").removeClass('hide');
    		$("#hr_employee_id").attr("data-validation", "required");
    		$("#office_id").removeAttr("data-validation");
    		$(".office").addClass('hide');
    		$('#office_id').val('').select2('val', 'All');
    	} else{
    		
    		$(".employee").addClass('hide');
    		$(".office").addClass('hide');
    		$("#office_id").removeAttr("data-validation");
    		$("#hr_employee_id").removeAttr("data-validation");
    	}

    });

    $('#purchase_cost').keyup(function(event) {

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
            }else{
                alert('Only JPG and PNG Files Allowed');
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
