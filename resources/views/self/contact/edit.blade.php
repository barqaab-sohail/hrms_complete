       

<div class="card card-outline-info ">
	<div style="margin-top:10px; margin-right: 10px;">
                <a style="color:white" id ="addContact" href="{{route('selfContact.create')}}"class="btn btn-success float-right">Go to Add Contact Page</a>
	</div>

	<div class="row">
	
        	<div class="col-lg-12">

            <div class="card-body">

                <form id="editFormSsContact" method="post" action="{{route('selfContact.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
                    @method('PATCH')
                    {{csrf_field()}}
                    <div class="form-body">
                        
                        <h3 class="box-title">Edit Contact Detail</h3>
                        <hr class="m-t-0 m-b-40">
                        <!--row 1 -->
                        <div class="row">
                            <div class="col-md-3">
                             <!--/span 1-1 -->
                                <div class="form-group row">
                                    <div class="col-md-12">
                                   		<label class="control-label text-right">Name<span class="text_requried">*</span></label><br>
                                   		<input type="text" id="name" name="name" data-validation="required length"  data-validation-length="max190" value="{{ old('name',$data->name??'') }}"  class="form-control" placeholder="Enter Full Name" >
                                   				                                       		
                                    </div>
                                </div>
                            </div>
                            <!--/span 1-2 -->
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                    	<label class="control-label">Designation</label>
                                    
                                        <input type="text"  id="designation"   name="designation" value="{{ old('designation',$data->designation??'') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Designation" >
                                    </div>
                                </div>
                            </div>
                            <!--/span 1-3 -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                    	<label class="control-label text-right">Address</label>
                                    
                                        <input type="text" name="address" id="address"  value="{{ old('address',$data->address??'') }}" class="form-control" placeholder="Enter address" >
                                    </div>
                                </div>
                            </div>
                            
                       
                        </div>
                        

           				<!--row 2-->
                        <div class="row" >
                         
                            <!--/span 2-1-->
                            @foreach ($data->mobiles as $key => $mobile)
                            <div class="col-md-3 mobile" id="mobile_1" >
                            
                            	<div class="form-group row">
                            		
                                    <div class="col-md-8" >
                                    	<label class="control-label text-right">Mobile Number<span class="text_requried">*</span></label>
                                        <input type="text" name="mobile[]" id="mobile-1" value="{{old('mobile.0',$mobile->mobile??'')}}" data-validation="required length" data-validation-length="max15" placeholder="0345-0000000" class="form-control" >

                                    </div>
									<div class="col-md-4">
                                    <br>
                                    	<div class="float-right">
			                            @if($key==0)
			                            <button type="button" name="add" id="add_mobile" class="btn btn-success add" >+</button>
			                            @else
			                            <button type="button" name="add" id="remove_mobile" class="btn btn-danger remove_mobile" >X</button>
			                            @endif
										</div>
						
                                    </div>
                                    
                                </div>
                            </div>
                            @endforeach
                            <!--/span 2-2 -->

                            @forelse ($data->emails as $key => $email)
                            <div class="col-md-3 email" id="email_1">
                                <div class="form-group row">
                                    <div class="col-md-9">
                                    	<label class="control-label text-right">Email</label><br>
                                   		<input type="text"  id="email-1"  name='email[]' value="{{old('email.0',$email->email??'')}}" class="form-control"  data-validation="length"  data-validation-length="max190"  class="form-control" >

                                    </div>
                                    <div class="col-md-3">
                                    <br>
                                      
                                    	<div class="float-right">
			                            @if($key==0)
			                            <button type="button" name="add" id="add_email" class="btn btn-success add" >+</button>
			                            @else
			                            <button type="button" name="add" id="remove_email" class="btn btn-danger remove_email" >X</button>
			                            @endif
										</div>
                                    </div>
									
                                </div>
                            </div>
                            @empty
                            <div class="col-md-3 email" id="email_1">
                                <div class="form-group row">
                                    <div class="col-md-9">
                                    	<label class="control-label text-right">Email</label><br>
                                   		<input type="text"  id="email-1"  name='email[]' value="{{old('email')}}" class="form-control"  data-validation="length"  data-validation-length="max190"  class="form-control" >

                                    </div>
                                    <div class="col-md-3">
                                    <br>
                                         <div class="float-right">
                                        <button type="button" name="add" id="add_email" class="btn btn-success add" >+</button>
										</div>
                                    </div>
									
                                </div>
                            </div>
                            @endforelse
                            <!--/span 2-3 -->
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                    	<label class="control-label">Office Phone</label>
                                    
                                        <input type="text" id="office_phone" name="office_phone" value="{{ old('office_phone',$data->office->office_phone??'') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Office Phone Number" >
                                    </div>
                                </div>
                            </div>
                            <!--/span 3-3 -->
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                    	<label class="control-label">Office Fax</label>
                                    
                                        <input type="text"  id="office_fax" name="office_fax" value="{{ old('office_fax',$data->office->office_fax??'') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Fax Number" >
                                    </div>
                                </div>
                            </div>
                            		                             
                        </div>

                        <!--row 4-->
                        <div class="row">
                          
                            <!--/span 4-1 -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                    	<label class="control-label text-right">Office Address</label>
                                    
                                        <input type="text" name="office_address" id="office_address"  value="{{ old('office_address',$data->office->office_address??'') }}" class="form-control" placeholder="Enter Office Address" >
                                    </div>
                                </div>
                            </div>
                            <!--/span 4-2 -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                    	<label class="control-label text-right">Remarks</label>
                                    
                                        <input type="text" name="remarks" id="remarks"  value="{{ old('remarks',$data->remarks??'') }}" class="form-control" placeholder="Enter Remarks" >
                                    </div>
                                </div>
                            </div>
                       
                        </div>
                        

                       
             
          <!-- row-10 -->
                     <hr>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row"> 
                                   <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit</button>
                                        
                                    </div>
                                 


                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                
    		</div>      
    		
    	</div>
    </div>

</div>

<div class="row">
	<div class="col-md-12 table-container">

	</div>
</div>
       
    

	

<script>

$(document).ready(function(){

$('#editFormSsContact').on('submit', function(event){
    $('.fa-spinner').show();
 	var url = $(this).attr('action');
 	event.preventDefault();
	submitForm(this, url);
	//refreshTable("{{route('selfContact.table')}}",300);
	
}); //end submit


	$('select:not(.selectTwo)').chosen();

	   	 //Dynamic add mobile
		 // Add new element
		 $("#add_mobile").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".mobile").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".mobile:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 3;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   	var $clone = $("#mobile_1").clone();
		  	$clone.prop('id','mobile_'+nextindex).find('input:text').val('');
		   	$clone.find("#add_mobile").html('X').prop("class", "btn btn-danger remove remove_mobile");
		   	$clone.find('.remove_mobile_div').remove();
		   	$clone.insertAfter("div.mobile:last");
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_mobile', function(){
		 $(this).closest(".mobile").remove();
		  
 		}); 


		 //Dynamic add Email
		 // Add new element
		 $("#add_email").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".email").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".email:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 3;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   	var $clone = $("#email_1").clone();
		  	$clone.prop('id','email_'+nextindex).find("input[type='email']").val('');
		   	$clone.find("#add_email").html('X').prop("class", "btn btn-danger remove remove_email");
		   	$clone.insertAfter("div.email:last");
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_email', function(){
		 $(this).closest(".email").remove();
		  
 		}); 
		 
		 
		
	});	
	
</script>

        




