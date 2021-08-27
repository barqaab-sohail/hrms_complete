@extends('layouts.master.master')
@section('title', 'Personal Contacts')
@section('Heading')

	<h3 class="text-themecolor">Contact Detail</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
		
		
	</ol>
	
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12">

            <div class="card card-outline-info addAjax">
            	<div style="margin-top:10px; margin-right: 10px;">
		                    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Contact</button>
				</div>
			
				<div class="row" id="hideDiv">
				
  		        	<div class="col-lg-12">

		                <div class="card-body">

		                    <form id="formSsContact" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                        {{csrf_field()}}
		                        <div class="form-body">
		                            
		                            <h3 class="box-title">Contact Detail</h3>
		                            <hr class="m-t-0 m-b-40">
		                            <!--row 1 -->
		                            <div class="row">
		                                <div class="col-md-3">
		                                 <!--/span 1-1 -->
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                       		<label class="control-label text-right">Name<span class="text_requried">*</span></label><br>
		                                       		<input type="text" id="name" name="name" data-validation="required length"  data-validation-length="max190" value="{{ old('name') }}"  class="form-control" placeholder="Enter Full Name" >
		                                       				                                       		
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 1-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 ">
		                                        	<label class="control-label">Designation</label>
		                                        
		                                            <input type="text"  id="designation"   name="designation" value="{{ old('designation') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Designation" >
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 1-3 -->
		                                <div class="col-md-6">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">Address</label>
		                                        
		                                            <input type="text" name="address" id="address"  value="{{ old('address') }}" class="form-control" placeholder="Enter address" >
		                                        </div>
		                                    </div>
		                                </div>
		                                
	                               
		                            </div>
		                            

		               				<!--row 2-->
		                            <div class="row" >
		                             
		                                <!--/span 2-1-->

		                                <div class="col-md-3 mobile" id="mobile_1" >
		                                
		                                	<div class="form-group row">
		                                		
		                                        <div class="col-md-8" >
		                                        	<label class="control-label text-right">Mobile Number<span class="text_requried">*</span></label>
		                                            <input type="text" name="mobile[]" id="mobile-1" value="{{old('mobile.0')}}" data-validation="required length" data-validation-length="max15" placeholder="0345-0000000" class="form-control" >

		                                        </div>
												<div class="col-md-4">
		                                        <br>
		                                        	
			                                        <div class="float-right">
			                                        <button type="button" name="add" id="add_mobile" class="btn btn-success add" >+</button>
													</div>
													
		                                        </div>
		                                        
		                                    </div>
		                                </div>
		                                <!--/span 2-2 -->
		                                <div class="col-md-3 email" id="email_1">
		                                    <div class="form-group row">
		                                        <div class="col-md-9">
		                                        	<label class="control-label text-right">Email</label><br>
		                                       		<input type="email"  id="email-1"  name='email[]' value="{{old('email.0')}}" class="form-control"  data-validation="email length"  data-validation-length="max190"  class="form-control" >

		                                        </div>
		                                        <div class="col-md-3">
		                                        <br>
			                                         <div class="float-right">
			                                        <button type="button" name="add" id="add_email" class="btn btn-success add" >+</button>
													</div>
		                                        </div>
												
		                                    </div>
		                                </div>
		                                <!--/span 2-3 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 ">
		                                        	<label class="control-label">Office Phone</label>
		                                        
		                                            <input type="text" id="office_phone" name="office_phone" value="{{ old('office_phone') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Office Phone Number" >
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 3-3 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 ">
		                                        	<label class="control-label">Office Fax</label>
		                                        
		                                            <input type="text"  id="office_fax" name="office_fax" value="{{ old('office_fax') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Fax Number" >
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
		                                        
		                                            <input type="text" name="office_address" id="office_address"  value="{{ old('office_address') }}" class="form-control" placeholder="Enter Office Address" >
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 4-2 -->
		                                <div class="col-md-6">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">Remarks</label>
		                                        
		                                            <input type="text" name="remarks" id="remarks"  value="{{ old('remarks') }}" class="form-control" placeholder="Enter Remarks" >
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
		                                            <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>
		                                            
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
        </div>
    </div>
 @push('scripts')
	

<script>

$(document).ready(function(){

 refreshTable("{{route('selfContact.table')}}",300);

 $("#hideDiv").hide();
 $("#hideButton").click(function(){
 	resetForm();
 	$("#hideDiv").toggle();
 })
 
	

formFunctions();



$('#formSsContact').on('submit', function(event){
    $('.fa-spinner').show();
 	var url = "{{ route('selfContact.store')}}"
 	event.preventDefault();
	submitForm(this, url,1);
	refreshTable("{{route('selfContact.table')}}",300);
	
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

        

@endpush

@stop
