<div class="card-body">

    <form id="formCv" method="post" action= "{{route('cv.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
        @method('PATCH')
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
                       		<input type="text"  name="full_name" data-validation="required length"  data-validation-length="max190" value="{{old('full_name', $data->full_name)}}"  class="form-control" placeholder="Enter Full Name" >
                        </div>
                    </div>
                </div>
                <!--/span 1-2 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12 ">
                        	<label class="control-label">Father Name</label>
                        
                            <input type="text"  name="father_name" value="{{old('father_name', $data->father_name)}}"  data-validation="length"  data-validation-length="max190" class="form-control" placeholder="Enter Father Name" >
                        </div>
                    </div>
                </div>
                <!--/span 1-3 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">CNIC</label>
                        
                            <input type="text" name="cnic" id="cnic" pattern="[0-9.-]{15}" title= "13 digit Number without dash" value="{{old('cnic', $data->cnic)}}" class="form-control" placeholder="Enter CNIC without dash" >
                        </div>
                    </div>
                </div>
                 <!--/span 1-4 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">Date of Birth</label>
                        
                            <input type="text" id="date_of_birth" name="date_of_birth" value="{{old('date_of_birth', $data->date_of_birth)}}"  class="form-control date_input" readonly>
							 
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
                        
                            <input type="text" id="job_starting_date" name="job_starting_date" value="{{old('job_starting_date', $data->job_starting_date)}}" data-validation="required" class="form-control date_input" placeholder="Enter Date of Birth" readonly>
							 
                            <br>
                           <i class="fas fa-trash-alt text_requried"></i> 
                        </div>
                    </div>
                </div>

                 <!--/span 2-2 -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                       		<label class="control-label ">Address</label><br>
                       		<input type="text"  name="address" value="{{old('address', $data->cvContact->address)}}" data-validation="length"  data-validation-length="max190"  class="form-control" placeholder="Enter Address" >
                        </div>
                    </div>
                </div>
               
                <!--/span 2-3 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">Country<span class="text_requried">*</span></label><br>
                            
                            <select  name="country_id" id="country" data-validation="required" class="form-control">
                                <option value="">&nbsp;</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" {{(old("country_id",$data->cvContact->country_id)==$country->id? "selected" : "")}}>{{$country->name}}</option>
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
                        <div class="col-md-12">
                        	<label class="control-label text-right">Province</label>
                       		
                        	<select class="form-control" name="state_id" data-placeholder="First Select Country" id="state">
                        	<option value="">&nbsp;</option>
								@foreach($states as $state)
								<option value="{{$state->id}}" {{(old("state_id",$data->cvContact->state_id)==$state->id? "selected" : "")}}>{{$state->name}}</option>
                            @endforeach 	
								
                            </select>	
                       		
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                	<!--/span 3-2 -->
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">City</label>
                            <select class="form-control" name="city_id" data-placeholder="First Select Province" id="city">
                            <option value="">&nbsp;</option>
                                @foreach($cities as $city)
                                <option value="{{$city->id}}" {{(old("city_id",$data->cvContact->city_id)==$city->id? "selected" : "")}}>{{$city->name}}</option>
                            @endforeach     
                                
                            </select>   
                       	
                        </div>
                    </div>
                </div>
                <!--/span 3-3 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">Email</label>
                        
                            <input type="email" name="email" value="{{old('email', $data->cvContact->email)}}" data-validation="length"  data-validation-length="max190" class="form-control" placeholder="Enter Email Address " >
                        </div>
                    </div>
                </div>
                <!--/span 3-4 -->
                 
                @foreach ($data->cvPhone as $key => $phone)
                
                <div class="col-md-3 phone" id="phone_1">
                	<div class="form-group row">
                        <div class="col-md-8">
                        	<label class="control-label text-right">Mobile Number<span class="text_requried">*</span></label>
                            <input type="text" name="phone[]['{{$phone->id}}']" id="mobile" data-validation="required length"  data-validation-length="max190" value="{{$phone->phone}}"  class="form-control" placeholder="0345-0000000">

                        </div>
						<div class="col-md-4">
                        <br>
                            <div class="float-right">
                            @if($key==0)
                            <button type="button" name="add" id="add_phone" class="btn btn-success add" >+</button>
                            @else
                            <button type="button" name="add" id="remove_phone" class="btn btn-danger remove_phone" >X</button>
                            @endif
							</div>
                        </div>
                    </div>
                </div>
                
                @endforeach
            </div>

             <!--row 4-->
        	@foreach($data->cvEducation as $key => $education)
            <div class="row education" id='edu_1' >
			
                <div class="col-md-3">
                	<!--/span 4-1 -->
                    <div class="form-group row">
                        <div class="col-md-12">
                       		<label class="control-label text-right">Name of Degree<span class="text_requried">*</span></label><br>
                       			<select  name="degree_name[]" id="degree_name" class="form-control required">
                                <option value="">&nbsp;</option>
                                @foreach($degrees as $degree)
								<option value="{{$degree->id}}" @if($degree->id == $education->id) selected="selected" @endif>{{$degree->degree_name}}</option>
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
                        	<label class="control-label">Name of Institut</label>
                            <input type="text" name="institute[]" id="institute" value="{{$education->pivot->institute}}" data-validation="length"  data-validation-length="max190" class="form-control" placeholder="Enter Institute Name" >

                        </div>
                    </div>
                </div>
                <!--/span 4-3 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-8">
                        	<label class="control-label text-right">Passing Year</label>
                        
                            <select  name="passing_year[]" id="passing_year" class="form-control" >

							<option value="">&nbsp;</option>
							@for ($i = 1958; $i <= now()->year; $i++)
							<option value="{{$i}}" @if($i == $education->pivot->passing_year) selected="selected" @endif>{{ $i }}</option>
							@endfor

							</select>

                        </div>
                        <div class="col-md-4">
                        <br>
                            <div class="float-right">
                            @if($key==0)
                            <button type="button" name="add" id="add" class="btn btn-success add" >+</button>
                            @else
                            <button type="button" name="add" id="remove" class="btn btn-danger remove_edu" >X</button>
                            @endif
							</div>
                        </div>
						
                    </div>
                </div>
                
            </div>
        @endforeach

      

        <!--row 5-->
        @foreach($data->CvExperience as $key => $speciality)
            <div class="row specialization" id='spe_1' >
                <div class="col-md-3">
                	<!--/span 5-1 -->
                    <div class="form-group row">
                        <div class="col-md-12">
                       		<label class="control-label text-right">Speciality<span class="text_requried">*</span></label><br>

                       		<select  name="speciality_name[]" id="speciality_name" class="form-control required" >
                                 <option value="">&nbsp;</option>
                                
                                @foreach($specializations as $specialization)
								
								<option value="{{$specialization->id}}" 
								@if($specialization->id == $speciality->cv_specialization_id) selected="selected" @endif>{{$specialization->name}}</option>
                                @endforeach   
                            </select>
                            <br>
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
                        <div class="col-md-12 ">
                        	<label class="control-label">Discipline<span class="text_requried">*</span></label>

                        	<select  name="discipline_name[]" data-validation="required" id=discipline_name class="form-control required" >
                                <option value="">&nbsp;</option>
                                
                                @foreach($disciplines as $discipline)

								<option value="{{$discipline->id}}"  
								@if($discipline->id == $speciality->cv_discipline_id) selected="selected" @endif
								>{{$discipline->name}}</option>

                                @endforeach
                              
                            </select>
                        
                            
                        </div>
                    </div>
                </div>
                <!--/span 5-2 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12 ">
                        	<label class="control-label">Stage of Work<span class="text_requried">*</span></label>

                        	<select  name="stage_name[]" data-validation="required" id=stage_name class="form-control required" >
                                <option value="">&nbsp;</option>
                                
                                @foreach($stages as $stage)

								<option value="{{$stage->id}}"  
								@if($stage->id == $speciality->cv_stage_id) selected="selected" @endif
								>{{$stage->name}}</option>

                                @endforeach
                              
                            </select>
                        
                            
                        </div>
                    </div>
                </div>
                <!--/span 5-3 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-8">
                        	<label class="control-label text-right">Experience<span class="text_requried">*</span></label>
                        
                            <select  name="year[]" id="field_year" class="form-control required">

							<option value="">&nbsp;</option>
							@for ($i = 1; $i <= 50; $i++)
							<option value="{{$i}}" 
								@if($i == $speciality->year) selected="selected" @endif
							>{{ $i }}</option>
							@endfor
							</select>

                        </div>
                        <div class="col-md-4">
                        <br>
                            <div class="float-right">
                            @if($key==0)
                            <button type="button" name="add" id="add_spe" class="btn btn-success add" >+</button>
                            @else
                            <button type="button" name="add" id="remove_spe" class="btn btn-danger remove_spe" >X</button>
                            @endif
							</div>
                        </div>
						
                    </div>
                </div>
                
            </div>
        @endforeach
            
<!--row 6-->

            <div class="row" >
             <!--/span 6-1 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">Foreign Experienc</span></label>
                        
                            <input type="number" id="foreign_experience" name="foreign_experience" value="{{ old('foreign_experience',$data->foreign_experience) }}" class="form-control " >
							 
                        </div>
                    </div>
                </div>
			<!--/span 6-2 -->
                <div class="col-md-3">
                
                    <div class="form-group row">
                        <div class="col-md-12">
                       		<label class="control-label text-right">Donor Experience</label><br>
                       		<input type="number"  name="donor_experience" value="{{ old('donor_experience', $data->donor_experience )}}" class="form-control" >
                        </div>
                    </div>
                </div>
               
                @forelse($data->membership as $key => $member)      
            	<!--/span 6-3 -->
             	<div class="col-md-6 membership" id="membership_1">
                    <div class="form-group row">
                        <div class="col-md-6">
                        	<label class="control-label">Membership</label>
							
                        	<select  name="membership_name[]" id=membership_name class="form-control">
                                <option value="">&nbsp;</option>
                                
                                @foreach($memberships as $membership)
								
								<option value="{{$membership->id}}" @if($membership->id == $member->id) selected="selected" @endif>{{$membership->name}}</option>

                                @endforeach
                              
                            </select>
                        </div>
                        <div class="col-md-4">
                        	<label class="control-label text-right">Number</label>
                            <input type="text" name="membership_number[]" value="{{ old('membership_number.0', $member->pivot->membership_number) }}" class="form-control" >
                             
                    
                        </div>
                        <div class="col-md-2 ">
                        	<br>
                        	<div class="float-right">
                        	@if($key==0)
                             <button type="button" name="add" id="add_mem" class="btn btn-success add" >+</button>
                             @else
                             <button type="button" name="add" id="remove_mem" class="btn btn-danger remove_membership" >X</button>
                             @endif
                    		</div>
                        </div>
                    </div>
                </div>
               
               @empty
                 <!--/span 6-3 -->
                <div class="col-md-6 membership" id="membership_1">
                    <div class="form-group row">
                        <div class="col-md-6">
                        	<label class="control-label">Membership</label>

                        	<select  name="membership_name[]" id=membership_name class="form-control">
                               
                                
                                @foreach($memberships as $membership)
								 <option value=""></option>
								<option value="{{$membership->id}}" {{(old("membership_name.0")==$membership->id? "selected" : "")}}>{{$membership->membership_name}}</option>

                                @endforeach
                              
                            </select>
                        </div>
                        <div class="col-md-4">
                        	<label class="control-label text-right">Number</label>
                            <input type="text" name="membership_number[]" data-validation="length"  data-validation-length="max190" value="{{ old('membership_number.0') }}" class="form-control" >
                             
                    
                        </div>
                        <div class="col-md-2 "> 
                        	<br>
                        	<div class="float-right">
                             <button type="button" name="add" id="add_mem" class="btn btn-success add" >+</button>
                    		</div>
                        </div>
                    </div>
                </div>
                @endforelse

 
           
            </div>
	
	<!--row 7-->
            <div class="row" >
                
                <!--/span 7-1 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12 ">
                        	<label class="control-label">BARQAAB Employee<span class="text_requried">*</span></label>

                        	<select  name="barqaab_employment" class="form-control required" >

                                <option value="">&nbsp;</option>
                                <option value="1" @if($data->barqaab_employment == 1) selected="selected" @endif>Yes</option>
                                <option value="0" @if($data->barqaab_employment == 0) selected="selected" @endif>No</option>
                                                                                      
                            </select>
                        
                        </div>
                    </div>
                </div>
                <!--/span 7-2 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">CV Submision Date</label>
                        
                            <input type="text" name="cv_submission_date" value="{{ old('cv_submission_date', $data->cv_submission_date) }}" class="form-control date_input" readonly>
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
                       		<input type="text"  name="ref_detail" data-validation="length"  data-validation-length="max190" value="{{ old('ref_detail', $data->cvReference->cv_detail??'') }}" class="form-control exempted" >
                        </div>
                    </div>
                </div>
                <!--/span 7-4 --> 
                 <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Comments</label><br>
                            <input type="text"  name="comments" data-validation="length"  data-validation-length="max190" value="{{ old('comments', $data->comments) }}" class="form-control exempted" >
                        </div>
                    </div>
                </div>
                
            </div>

			<!--row 8-->
            <div class="row" >
           
			@foreach($data->cvSkill as $key => $skill)
				<!--/span 8-1 -->
                <div class="col-md-3 skill" id="skill_1">

                    <div class="form-group row">
                        <div class="col-md-9">
                        	<label class="control-label text-right">Other Skills</label><br>
                       		<input type="text"  name="skill_name[]['{{$skill->id}}']" value="{{$skill->skill_name}}" data-validation="length"  data-validation-length="max190" class="form-control" >

                        </div>
                        <div class="col-md-3">
                        <br>
                            <div class="float-right">
                            @if($key==0)
                            <button type="button" name="add" id="add_skill" class="btn btn-success add" >+</button>
                            @else
                            <button type="button" name="add" id="add_skill" class="btn btn-danger remove_skill" >X</button>
                            @endif
							</div>
                        </div>
						
                    </div>
                </div>
              @endforeach
            </div>
     
            <!--row 9-->
            <div class="row" >
           
				<!--/span 9-1 -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                       		<label class="control-label text-right">Attached CV</label><br>
                       		<input type="file"  id="cv" name="cv"  class="form-control" ><span class="text_requried">doc, docx and pdf only</span>
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
                            <button type="submit" id="submit"  class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit</button>
                            
                        </div>
                     


                    </div>
                </div>
            </div>
        </div>
    </form>
</div>       
@include('cv.detail.eduModal') 
@include('cv.detail.spcModal') 
@push('scripts')
<script>
    $(document).ready(function (){
    $('select').chosen('destroy');
    $('select:not(.selectTwo)').chosen();
    });
</script>
@endpush
@stack('scripts')

