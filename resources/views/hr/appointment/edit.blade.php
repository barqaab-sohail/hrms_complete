
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "addAppointment" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('appointment.update',22)}}" enctype="multipart/form-data">
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Appointment Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Reference No.<span class="text_requried">*</span></label><br>

                               	<input type="text"  name="reference_no" value="{{ old('reference_no') }}"  class="form-control" data-validation="required" placeholder="Enter Appointment Letter Reference No">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Joining Date<span class="text_requried">*</span></label>
                                
                                <input type="text" name="joining_date" value="{{ old('joining_date') }}" class="form-control date_input" data-validation="required" >

                                <br>
                                @can('hr_edit_record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Expiry Date</label>
                                
                                <input type="text" name="expiry_date" value="{{ old('expiry_date') }}" class="form-control date_input" >

                                <br>
                                @can('hr_edit_record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Designation<span class="text_requried">*</span></label>
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">HOD<span class="text_requried">*</span></label>
                                
                                
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Department<span class="text_requried">*</span></label>
                                    
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Category<span class="text_requried">*</span></label>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Salary<span class="text_requried">*</span></label>

                                <select  name="hr_salary_id"  class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($salaries as $salary)
                                <option value="{{$salary->id}}" {{(old("hr_salary_id",100)==$salary->id? "selected" : "")}}>{{$salary->total_salary}}</option>
                                @endforeach
                                
                                
                            </select>
                            <br>
                                @can('hr_edit_record')<i id="addSalary" class="fa fa-plus-square text_add" aria-hidden="true"></i>@endcan 
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Grade</label>
                                
                                
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Letter Type<span class="text_requried">*</span></label>
                                    
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Project<span class="text_requried">*</span></label>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row hideDiv">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <input type="number"  name="total_salary" value="{{ old('total_salary') }}"  class="form-control" data-validation="" placeholder="Total Salary">
                                <i class="fa fa-plus-square fa-2x text_add" id="storeSalary" href="{{route('salary.store')}}" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>            
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Remarks</label>
                                <input type="text"  name="remarks" value="{{ old('remarks') }}"  class="form-control" data-validation="required" placeholder="Enter Remarks if any">
                                
                               
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
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Appointment</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
	</div> <!-- end card body -->    
