@extends('layouts.master.master')
@section('title', 'BARQAAB CV Record')
@section('Heading')

	<h3 class="text-themecolor">CV Detail</h3>
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
						 


		                <div class="card-body">

		                    <form id="formCvDetail" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                        {{csrf_field()}}
		                        <div class="form-body">
		                            
		                            <h3 class="box-title">CV Detail</h3>
		                            <hr class="m-t-0 m-b-40">
		                            <!--row 1 -->
		                            <div class="row">
		                                <div class="col-md-3">
		                                 <!--/span 1-1 -->
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                       		<label class="control-label text-right">Full Name<span class="text_requried">*</span></label><br>
		                                       		<input type="text" id="full_name" name="full_name" data-validation="required length"  data-validation-length="max190" value="{{ old('full_name') }}"  class="form-control" placeholder="Enter Full Name" ><span class="help-block full_name-error"></span>
		                                       		<div id="nameList">
		                                       		</div>
		                                       		
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 1-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 ">
		                                        	<label class="control-label">Father Name</label>
		                                        
		                                            <input type="text"  name="father_name" value="{{ old('father_name') }}"  class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Father Name" >
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 1-3 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">CNIC</label>
		                                        
		                                            <input type="text" name="cnic" id="cnic" pattern="[0-9.-]{15}" title= "13 digit Number without dash" value="{{ old('cnic') }}" class="form-control" onkeyup='addHyphen(this)'  placeholder="Enter CNIC without dash" >
		                                            <span id="cnicCheck" class="float-right" style="color:red"></span>
		                                        </div>
		                                    </div>
		                                </div>
		                                 <!--/span 1-4 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">Date of Birth</label>
		                                        
		                                            <input type="text" id="date_of_birth"  name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control date_input" placeholder="Enter Date of Birth" readonly>
													 
		                                            <br>
		                                           <i class="fas fa-trash-alt text_requried"></i> 
		                                           <span id="age" class="float-right" style="color:red"></span>
		                                        </div>
		                                    </div>
		                                </div>
	                               
		                            </div>
		                             <!--row 2-->
		                            <div class="row">
		                                <!--/span 2-1 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">Job Starting Date<span class="text_requried">*</span></label>
		                                        
		                                            <input type="text" id="job_starting_date" name="job_starting_date"  value="{{ old('job_starting_date') }}" class="form-control date_input" data-validation="required" placeholder="Enter Date of Birth" readonly>
													 
		                                            <br>
		                                           <i class="fas fa-trash-alt text_requried"></i> 
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 2-1 -->
		                                <div class="col-md-6">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                       		<label class="control-label ">Address</label><br>
		                                       		<input type="text"  name="address" value="{{ old('address') }}"  class="form-control" class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Address" >
		                                        </div>
		                                    </div>
		                                </div>
		                               
		                                <!--/span 2-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                        	<label class="control-label text-right">Country<span class="text_requried">*</span></label><br>
		                                       		<select  name="country_id" id="country" data-validation="required" class="form-control">
			                                           	<option value=""></option>
			                                        @foreach($countries as $country)
														<option value="{{$country->id}}" {{(old("country_id")==$country->id? "selected" : "")}}>{{$country->name}}</option>
                                                    @endforeach 	
                                                    </select> 
                            	
		                                        </div>
		                                    </div>
		                                </div>
		                                
		                            </div>
		                     
		               <!--row 3-->
		                            <div class="row" >
		                            <!--/span 3-1 -->
		                           		<div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                        	<label class="control-label text-right">Province</label>
		                                       		<select class="form-control" name="state_id" id="state" data-placeholder="First Select Country" >
       												</select>
		                             
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 3-2 -->
		                                <div class="col-md-3">   	
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">City</label>
		                                       		 <select class="form-control" name="city_id" id="city" data-placeholder="First Select Province" >
      												  </select>
		                                       		
		                                        </div>
		                                    </div>
		                                </div>
		                                 <!--/span 3-3 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 remove_phone_div">
		                                        	<label class="control-label text-right">Email</label>
		                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" class="form-control"  data-validation="length"  data-validation-length="max190" placeholder="Enter Email Address " >
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 3-4 -->

		                                <div class="col-md-3 phone" id="phone_1" >
		                                
		                                	<div class="form-group row">
		                                		
		                                        <div class="col-md-8" >
		                                        	<label class="control-label text-right">Mobile Number<span class="text_requried">*</span></label>
		                                            <input type="text" name="phone[]" id="mobile" value="{{old('phone.0')}}" data-validation="required length" data-validation-length="max15" placeholder="0345-0000000" class="form-control" >

		                                        </div>
												<div class="col-md-4">
		                                        <br>
		                                        	
			                                        <div class="float-right">
			                                        <button type="button" name="add" id="add_phone" class="btn btn-success add" >+</button>
													</div>
													
		                                        </div>
		                                        
		                                    </div>
		                                </div>
		                             
		                            </div>

		                             <!--row 4-->
		                            <div class="row education" id='edu_1' >
		                                <div class="col-md-3">
		                                	<!--/span 4-1 -->
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                       		<label class="control-label text-right">Name of Degree<span class="text_requried">*</span></label><br>
		                                       			<select id="degree_name" name="degree_name[]" data-validation="required" class="form-control">
                                                       <option></option>
                                                        @foreach($degrees as $degree)
														<option value="{{$degree->id}}" {{(old("degree_name.0")==$degree->id? "selected" : "")}}>{{$degree->degree_name}}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <br>
					                                @can('cv edit record')
					                                <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#eduModal"><i class="fas fa-plus"></i>
		          									</button>
		          									@endcan 

		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 4-2 -->
		                                <div class="col-md-6">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 ">
		                                        	<label class="control-label">Name of Institute</label>
		                                            <input type="text" name="institute[]" value="{{old('institute.0')}}" class="form-control" class="form-control"  data-validation="length"  data-validation-length="max190"  placeholder="Enter Institute Name" >

		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 4-3 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-8">
		                                        	<label class="control-label text-right">Passing Year</label>
		                                        
		                                            <select  name="passing_year[]" class="form-control">

													<option value=""></option>
													@for ($i = 1958; $i <= now()->year; $i++)
    												<option value="{{$i}}" {{(old("passing_year.0")==$i? "selected" : "")}}>{{ $i }}</option>
													@endfor

													</select>

		                                        </div>
												<div class="col-md-4">
		                                        <br>
			                                        <div class="float-right">
			                                        <button type="button" name="add" id="add" class="btn btn-success add" >+</button>
													</div>
		                                        </div>
		                                    </div>
		                                </div>
		                                
		                            </div>
		                            
		                             <!--row 5-->
		                            <div class="row specialization" id='spe_1' >
		                                <div class="col-md-3">
		                                	<!--/span 5-1 -->
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                       		<label class="control-label text-right">Speciality<span class="text_requried">*</span></label><br>

		                                       		<select  name="speciality_name[]"  id=speciality_name data-validation="required" class="form-control" >
                                                        <option value=""></option>
                                                        
                                                        @foreach($specializations as $specialization)
														
														<option value="{{$specialization->id}}" {{(old("speciality_name.0")==$specialization->id? "selected" : "")}}>{{$specialization->name}}</option>

                                                        @endforeach
                                                      
                                                    </select>
                                                    @can('cv edit record')
					                                <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#spcModal"><i class="fas fa-plus"></i>
		          									</button>
		          									@endcan 

		                                       		

		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 5-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                        	<label class="control-label">Discipline<span class="text_requried">*</span></label>

		                                        	<select  name="discipline_name[]"  id="discipline_name" data-validation="required" class="form-control" >
                                                        <option value=""></option>
                                                        
                                                        @foreach($disciplines as $discipline)
														
														<option value="{{$discipline->id}}" {{(old("discipline.0")==$discipline->id? "selected" : "")}}>{{$discipline->name}}</option>

                                                        @endforeach
                                                      
                                                    </select>
                                                    @can('cv edit record')
					                                <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#disModal"><i class="fas fa-plus"></i>
		          									</button>
		          									@endcan 
		                                        
		                                            
		                                        </div>
		                                    </div>
		                                </div>
		                                  <!--/span 5-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                        	<label class="control-label">Stage<span class="text_requried">*</span></label>

		                                        	<select  name="stage_name[]"  id=stage_name data-validation="required" class="form-control" >
                                                        <option value=""></option>
                                                        
                                                        @foreach($stages as $stage)
														
														<option value="{{$stage->id}}" {{(old("stage_name.0")==$stage->id? "selected" : "")}}>{{$stage->name}}</option>

                                                        @endforeach
                                                      
                                                    </select>
		                                        
		                                            
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 5-3 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-8 required">
		                                        	<label class="control-label text-right">Years of Experience<span class="text_requried">*</span></label>
		                                        
		                                            <select data-validation="required" name="year[]"  class="form-control">

													<option value=""></option>
													@for ($i = 1; $i <= 50; $i++)
    												<option value="{{$i}}" {{(old("year.0")==$i? "selected" : "")}}>{{ $i }}</option>
													@endfor

													</select>

		                                        </div>
												<div class="col-md-4">
		                                        <br>
			                                        <div class="float-right">
			                                        <button type="button" name="add" id="add_spe" class="btn btn-success add" >+</button>
													</div>
		                                        </div>
		                                    </div>
		                                </div>
		                                
		                            </div>
		                            
		                <!--row 6-->
		                            <div class="row" >
		                                
		                            <!--/span 6-1 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 remove_div">
		                                        	<label class="control-label text-right">Foreign Experience</label>
		                                            <input type="number" id="foreign_experience" name="foreign_experience"  value="{{ old('foreign_experience') }}" class="form-control " >	 
		                                        </div>
		                                    </div>
		                                </div>
									<!--/span 6-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 remove_div">
		                                       		<label class="control-label text-right">Donor Experience</label><br>
		                                       		<input type="number"  name="donor_experience" value="{{ old('donor_experience') }}" class="form-control" >
		                                        </div>
		                                    </div>
		                                </div>
		                                 <!--/span 6-3 -->
		                                <div class="col-md-6 membership" id="membership_1">
		                                    <div class="form-group row">
		                                        <div class="col-md-6">
		                                        	<label class="control-label">Membership</label>

		                                        	<select  name="membership_name[]" id=membership_name class="form-control">
                                                        <option value=""></option>
                                                        
                                                        @foreach($memberships as $membership)
														
														<option value="{{$membership->id}}" {{(old("membership_name.0")==$membership->id? "selected" : "")}}>{{$membership->name}}</option>

                                                        @endforeach
                                                      
                                                    </select>
		                                        </div>
		                                        <div class="col-md-4">
		                                        	<label class="control-label text-right">Number</label>
		                                            <input type="text" name="membership_number[]" value="{{ old('membership_number.0') }}" class="form-control"  data-validation="length"  data-validation-length="max190"  class="form-control" >
		                                             
                                            
		                                        </div>
		                                        <div class="col-md-2 "> 
		                                        	<br>
		                                        	<div class="float-right">
		                                             <button type="button" name="add" id="add_mem" class="btn btn-success add" >+</button>
                                            		</div>
		                                        </div>
		                                    </div>
		                                </div>
		                               
		                            </div>
 						
 						<!--row 7-->
		                            <div class="row" >
		                                
		                                <!--/span 7-1 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12 required">
		                                        	<label for="barqaab_employment" class="control-label">BARQAAB Employee<span class="text_requried">*</span></label>

		                                        	<select  id="bqb" name="barqaab_employment" data-validation="required" class="form-control " >

                                                        <option value="">&nbsp;</option>
                                                        <option value="1" {{(old("barqaab_employment")=="1"? "selected":"")}}>Yes</option>
                                                        <option value="0" {{(old("barqaab_employment")=="0"? "selected":"")}}>No</option>
                                                                                                              
                                                    </select>
		                                        
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 7-2 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                        	<label class="control-label text-right">CV Submision Date</label>
		                                        
		                                            <input type="text" name="cv_submission_date" value="{{ old('cv_submission_date') }}" class="form-control date_input" readonly>
		                                             <br>
		                                           <i class="fas fa-trash-alt text_requried"></i> 
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 7-3 --> 
		                                 <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                       		<label class="control-label text-right">CV Reference</label><br>
		                                       		<input type="text"  name="ref_detail" value="{{ old('ref_detail') }}" class="form-control exempted"  data-validation="length"  data-validation-length="max190"  class="form-control" >
		                                        </div>
		                                    </div>
		                                </div>
		                                <!--/span 7-8 --> 
		                                 <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                       		<label class="control-label text-right">Comments</label><br>
		                                       		<input type="text"  name="comments" value="{{ old('comments') }}" class="form-control exempted"  data-validation="length"  data-validation-length="max190"  class="form-control" >
		                                        </div>
		                                    </div>
		                                </div>
		                                
		                            </div>
		                
   								<!--row 8-->
		                            <div class="row"  >
		                           		<!--/span 8-1 -->
										<div class="col-md-3 skill" id="skill_1">
		                                    <div class="form-group row">
		                                        <div class="col-md-9">
		                                        	<label class="control-label text-right">Other Skills</label><br>
		                                       		<input type="text"  name='skill[]' value="{{old('skill.0')}}" class="form-control"  data-validation="length"  data-validation-length="max190"  class="form-control" >

		                                        </div>
		                                        <div class="col-md-3">
		                                        <br>
			                                         <div class="float-right">
			                                        <button type="button" name="add" id="add_skill" class="btn btn-success add" >+</button>
													</div>
		                                        </div>
												
		                                    </div>
		                                </div>
		                              
		                            </div>

		                            <!--row 9-->
		                            <div class="row" >
		                           
										<!--/span 9-1 -->
		                                <div class="col-md-3">
		                                    <div class="form-group row">
		                                        <div class="col-md-12">
		                                       		<label class="control-label text-right">Attached CV<span class="text_requried">*</span></label><br>
		                                       		<input type="file" data-validation="required" id="cv" name="cv" value="{{ old('cv') }}"  class="form-control" ><span class="text_requried">doc, docx and pdf only</span>
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
		                                            <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Upload</button>
		                                            
		                                        </div>
		                                     
 

		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </form>
		        		</div>      
		        		@include('cv.detail.eduModal') 
		        		@include('cv.detail.spcModal')
		        		@include('cv.detail.disModal') 
		        	</div>
		        </div>
            </div>
        </div>
    </div>
 @push('scripts')
	

<script>

$(document).ready(function(){


	$('#cnic').blur(function(){ 
		$('#cnicCheck').fadeOut(); 
        var query = $(this).val();
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"{{ route('cv.cnic') }}",
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

	$('#full_name').keyup(function(){ 
        var query = $(this).val();
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"{{ route('autocomplete.fetch') }}",
          method:"GET",
          data:{query:query, _token:_token},
          success:function(data){
           $('#nameList').fadeIn();  
                    $('#nameList').html(data);
          }
         });
        }
    });


formFunctions();


$('#addDegree').click(function(){
	$('.hideDiv').toggle();
	$('#add_degree').val('');
    $('#add_level').val('').select2('val', 'All');
});
$('#store_degree').click(function(){
	var url = $(this).attr('href');
	var degree_name = $('#add_degree').val();
    var level = $('#add_level').val();
    var result = [];
    result.push({degree_name: degree_name, level: level});
    if((degree_name != '')&&(level != '')){
            submitFormWithoutDataForm(result, url);
    }else{
    	alert('Name of Degree and Level of Degree Required');
    }

});

$('#formCvDetail').on('submit', function(event){
    $('.fa-spinner').show();
 	var url = "{{ route('cv.store')}}"

 		event.preventDefault();
		submitForm(this, url,1);
	
}); //end submit


	$('select:not(.selectTwo)').chosen();

	$('#country').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('country/states')}}"+"/"+cid,

             //url:"http://localhost/hrms4/public/country/"+cid, **//Please see the note at the end of the post**
             success:function(res)
             {       
                  if(res)
                  {
                      $("#state").empty();
                     $("#city").empty();
                      $("#state").append('<option value="">Select State</option>');
                      $.each(res,function(key,value){
                          $("#state").append('<option value="'+key+'">'+value+'</option>');
                          
                      });
                       $('#state').chosen('destroy');
                       $('#state').chosen();

                  }
             }

          });//end ajax
        }
    }); 

    //get cities through ajax
    $('#state').change(function(){
    	var sid = $(this).val();
        if(sid){
          $.ajax({
             type:"get",
              url: "{{url('country/cities')}}"+"/"+sid,
             success:function(res)
             {       
                  if(res)
                  {
                      $("#city").empty();
                      $("#city").append('<option value="">Select City</option>');
                      $.each(res,function(key,value){
                          $("#city").append('<option value="'+key+'">'+value+'</option>');
                      });
                       $('#city').chosen('destroy');
                       $('#city').chosen();
                  }
             }

          });
        }

    });

	

	 $("#cv").change(function(){
	 	var fileType = this.files[0].type;
	 	var fileSize = this.files[0].size;

	 	if (fileSize> 4096000){
		        	alert('File Size is bigger than 4MB');
		        	$(this).val('');
		}else{

		    if((fileType!='application/vnd.openxmlformats-officedocument.wordprocessingml.document')&&(fileType!='application/msword')&&(fileType!='application/pdf')){

	 		alert('Only pdf, doc and docx attachment allowed');
	 		$(this).val('');
	 		}
	 	}

	 	
	 });
		
	 
	//Dynamic add education
		
		// Add new element
		 $("#add").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".education").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".education:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone education div and copy 
			$('.education').find('select').chosen('destroy');
		   	var clone = $("#edu_1").clone();
		  	clone.prop('id','edu_'+nextindex).find('input:text').val('');
		   	clone.find("#add").html('X').prop("class", "btn btn-danger remove remove_edu");
		   	clone.insertAfter("div.education:last");
			$('.education').find('select').chosen();
		   
		  }
		 
		});
		 // Remove element
		 $(document).on("click", '.remove_edu', function(){
		 $(this).closest(".education").remove();
 		}); 
		//Dynamic add specialization
		 // Add new element
		 $("#add_spe").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".specialization").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".specialization:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 10;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
			$('.specialization').find('select').chosen('destroy');
		   	var $clone = $("#spe_1").clone();
		  	$clone.prop('id','spe_'+nextindex).find('input:text').val('');
		   	$clone.find("#add_spe").html('X').prop("class", "btn btn-danger remove remove_spe");
		   	$clone.insertAfter("div.specialization:last");
		   	$('.specialization').find('select').chosen();
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_spe', function(){
		 $(this).closest(".specialization").remove();
		  
 		}); 
		 //Dynamic add Skill
		 // Add new element
		 $("#add_skill").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".skill").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".skill:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   	var $clone = $("#skill_1").clone();
		  	$clone.prop('id','skill_'+nextindex).find('input:text').val('');
		   	$clone.find("#add_skill").html('X').prop("class", "btn btn-danger remove remove_skill");
		   	$clone.insertAfter("div.skill:last");
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_skill', function(){
		 $(this).closest(".skill").remove();
		  
 		}); 
		 //Dynamic add membership
		 // Add new element
		 $("#add_mem").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".membership").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".membership:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   $('.membership').find('select').chosen('destroy');
		   	var $clone = $("#membership_1").clone();
		  	$clone.prop('id','membership_'+nextindex).find('input:text').val('');
		   	$clone.find("#add_mem").html('X').prop("class", "btn btn-danger remove remove_membership");
		   	$clone.find('.remove_div').remove();
		   	$clone.insertAfter("div.membership:last");
		   	$('.membership').find('select').chosen();
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_membership', function(){
		 $(this).closest(".membership").remove();
		  
 		}); 
		  //Dynamic add phone
		 // Add new element
		 $("#add_phone").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".phone").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".phone:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   	var $clone = $("#phone_1").clone();
		  	$clone.prop('id','phone'+nextindex).find('input:text').val('');
		   	$clone.find("#add_phone").html('X').prop("class", "btn btn-danger remove remove_phone");
		   	$clone.find('.remove_phone_div').remove();
		   	$clone.insertAfter("div.phone:last");
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_phone', function(){
		 $(this).closest(".phone").remove();
		  
 		}); 



		
	});	
	
</script>

        

@endpush

@stop
