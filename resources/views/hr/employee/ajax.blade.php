
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
</div>
     
<div class="card-body">
    <form id= "formEditEmployee" method="post" action="{{route('employee.update',$data->id)}}"  class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
        <div class="form-body">
                
            <h3 class="box-title">Edit Employee Information</h3>
            
            <hr class="m-t-0 m-b-40">

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">First Name<span class="text_requried">*</span></label><br>

                           	<input type="text"  name="first_name" value="{{ old('first_name', $data->first_name) }}"  class="form-control" data-validation="required" placeholder="Enter First Name">
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Last Name<span class="text_requried">*</span></label>
                            
                            <input type="text" name="last_name" value="{{ old('last_name', $data->last_name) }}" class="form-control " data-validation="required" placeholder="Enter Last Name" >
                        </div>
                    </div>
                </div>
                 <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Father Name<span class="text_requried">*</span></label>
                            
                            <input type="text" name="father_name" value="{{ old('father_name', $data->father_name) }}" class="form-control " data-validation="required" placeholder="Enter Father Name" >
                        </div>
                    </div>
                </div>
            </div><!--/End Row-->

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Date of Birth<span class="text_requried">*</span></label>
                            
                            <input type="text" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $data->date_of_birth) }}" class="form-control date_input" data-validation="required" readonly>
							
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
                            
                            <input type="text" name="cnic" id="cnic" pattern="[0-9.-]{15}" title= "13 digit Number without dash" value="{{ old('cnic',$data->cnic) }}" class="form-control" data-validation="required" placeholder="Enter CNIC without dash" >
                        </div>
                    </div>
                </div>
                 <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">CNIC Expiry<span class="text_requried">*</span></label>
                                
                            <input type="text" id="cnic_expiry" name="cnic_expiry" value="{{ old('cnic_expiry',$data->cnic_expiry) }}" class="form-control date_input"  data-validation="required" readonly >
							
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
								<option value="{{$gender->id}}" {{(old("gender_id",$data->gender_id)==$gender->id? "selected" : "")}}>{{$gender->name}}</option>
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
								<option value="{{$maritalStatus->id}}" {{(old("marital_status_id",$data->marital_status_id)==$maritalStatus->id? "selected" : "")}}>{{$maritalStatus->name}}</option>
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
								<option value="{{$religion->id}}" {{(old("religion_id",$data->religion_id)==$religion->id? "selected" : "")}}>{{$religion->name}}</option>
                                @endforeach
                              
                            </select>
							
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Employee ID</label>
                                
                            <input type="text" id="employee_no" name="employee_no" value="{{ old('employee_no', $data->employee_no) }}" class="form-control"  placeholder="Enter Employee No">
							
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <img src="{{asset('storage/'.$data->employeePicture())}}" onerror="this.src ='{{asset('Massets/images/default.png')}}';" alt="user" class="profile-pic" width="50%"/>
                        </div>
                    </div>
                </div>
                 <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12" style="text-align:center; color:black; font-weight: bold;">
                            <br>
                            {!! '<img src="data:image/png;base64,'. DNS2D::getBarcodePNG($data->employee_no,'QRCODE',5,5). '" alt="barcode" />' !!}
                            <br>
                            {{$data->employee_no}}
                        </div>
                    </div>
                </div>
            </div><!--/End Row-->
        </div> <!--/End Form Boday-->

        <hr>
        @can('hr edit employee information')
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" id="submitEditEmployee"  class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit Employee</button>        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </form>
</div> <!-- end card body -->    

<script type="text/javascript">
   isUserData(window.location.href, "{{URL::to('/hrms/employee/user/data')}}");
</script>
