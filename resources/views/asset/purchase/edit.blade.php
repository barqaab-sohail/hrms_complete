

<div style="margin-top:10px; margin-right: 10px;">
    <button type="button" onclick="window.location.href='{{route('asset.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Asset</button>
</div>
     
<div class="card-body">
    <form id= "formPurchase" method="post" action="{{route('asPurchase.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
        <div class="form-body">
                
            <h3 class="box-title">Edit Purchase Detail</h3>
            
            <hr class="m-t-0 m-b-40">

            <div class="row">
                 <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Purchase Cost</label>
                            <input type="text" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost',$data->asPurchase->purchase_cost??'') }}" class="form-control" placeholder="Enter Total Cost">
                           	
                 
							
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Purchase Date<span class="text_requried">*</span></label>
                            
                            <input type="text" id="purchase_date" name="purchase_date" value="{{ old('purchase_date',$data->asPurchase->purchase_date??'') }}" class="form-control date_input" data-validation="required" readonly>
							
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
								<option value="{{$asPurchaseCondition->id}}" {{(old("as_purchase_condition_id", $data->asPurchaseCondition->id??'')==$asPurchaseCondition->id? "selected" : "")}}>{{$asPurchaseCondition->name}}</option>
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
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button> 
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

	$('#formPurchase').on('submit', function(event){
	 	//preventDefault work through formFunctions;
      var url = $(this).attr('action');
  
      $('.fa-spinner').show();
      submitForm(this, url);
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

  
	 

});
</script>
