
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "formContact" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('contact.store')}}" enctype="multipart/form-data">
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Contact Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Contact Type<span class="text_requried">*</span></label>
                                 <select  id="hr_contact_type_id"   name="hr_contact_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($hrContactTypes as $hrContactType)
                                    <option value="{{$hrContactType->id}}" {{(old("hr_contact_type_id")==$hrContactType->id? "selected" : "")}}>{{$hrContactType->name}}</option>
                                    @endforeach 
                                </select>
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">House No.</label><br>

                                <input type="text"  name="house" value="{{ old('house') }}"  class="form-control" placeholder="Enter House No">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Street No/Society</label>
                                
                                <input type="text" name="street" value="{{ old('street') }}" class="form-control" placeholder="Enter Street No and Society">

                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Town/Village<span class="text_requried">*</span></label>
                                
                               <input type="text" name="town" value="{{ old('town') }}" class="form-control" placeholder="Enter Town or Village" data-validation="required">

                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Tehsil</span></label>
                                
                               <input type="text" name="tehsil" value="{{ old('tehsil') }}" class="form-control" placeholder="Enter Tehsil">
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">City<span class="text_requried">*</span></label>
                                <select class="form-control selectTwo" name="city_id" data-placeholder="First Select Province" data-validation="required"  id="city">
                                </select>
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Province<span class="text_requried">*</span></label>
                                <select class="form-control selectTwo" name="state_id" data-placeholder="First Select Country" data-validation="required" id="state">
                                </select>  
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Country<span class="text_requried">*</span></label><br>
                                <select  name="country_id" id="country" data-validation="required" class="form-control selectTwo">
                                    <option value=""></option>
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{(old("country_id")==$country->id? "selected" : "")}}>{{$country->name}}</option>
                                    @endforeach     
                                </select> 
                                    
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Mobile No.<span class="text_requried">*</span></label>
                                
                               <input type="text" name="mobile" value="{{ old('mobile') }}" class="form-control" placeholder="Enter Mobile No" data-validation="required">
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Landline No.</label>
                                
                               <input type="text" name="landline" value="{{ old('landline') }}" class="form-control" placeholder="Enter Landline No.">
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Email</label>
                                
                               <input type="email" name="email" value="{{ old('emal') }}" class="form-control" placeholder="Enter Email Address">
                                
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
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Contact</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
             <div class="col-md-12">
            @if($hrContacts->count()!=0)                    
            
                @include('hr.contact.list')    
            
            @endif
            </div>
        </div>
	</div> <!-- end card body -->    



