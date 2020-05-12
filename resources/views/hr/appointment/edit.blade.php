
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "formAppointment" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('appointment.update',session('hr_employee_id'))}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Appointment Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Reference No.<span class="text_requried">*</span></label><br>

                               	<input type="text"  name="reference_no" value="{{ old('reference_no', $data->reference_no??'') }}"  class="form-control" data-validation="required" placeholder="Enter Appointment Letter Reference No">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Joining Date<span class="text_requried">*</span></label>
                                
                                <input type="text" name="joining_date" value="{{ old('joining_date',$data->joining_date??'') }}" class="form-control date_input" data-validation="required" readonly >

                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Expiry Date</label>
                                
                                <input type="text" name="expiry_date" value="{{ old('expiry_date',$data->expiry_date??'') }}" class="form-control date_input" readonly>

                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Designation<span class="text_requried">*</span></label>
                                 <select  id="hr_designation_id"   name="hr_designation_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($designations as $designation)
                                    <option value="{{$designation->id}}" {{(old("hr_designation_id",$employeeDesignation->id??'')==$designation->id? "selected" : "")}}>{{$designation->name}}</option>
                                    @endforeach 
                                </select>
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">HOD<span class="text_requried">*</span></label>
                                 <select  id="hr_manager_id"   name="hr_manager_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($managers as $manager)
                                    <option value="{{$manager->id}}" {{(old("hr_manager_id",$employeeHod->id??'')==$manager->id? "selected" : "")}}>{{$manager->first_name}} {{$manager->last_name}}</option>
                                    @endforeach  
                                </select>
                                 
                                
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Department<span class="text_requried">*</span></label>
                                 <select  id="hr_department_id"   name="hr_department_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($departments as $department)
                                    <option value="{{$department->id}}" {{(old("hr_department_id",$employeeDepartment->id??'')==$department->id? "selected" : "")}}>{{$department->name}}</option>
                                    @endforeach  
                                </select>
                                    
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Category<span class="text_requried">*</span></label>
                                <select  name="category"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    <option value="A" {{(old("category",$data->category??'')=='A'? "selected" : "")}}>A</option>
                                    <option value="B" {{(old("category",$data->category??'')=='B'? "selected" : "")}}>B</option>
                                    <option value="C" {{(old("category",$data->category??'')=='C'? "selected" : "")}}>C</option>
                                </select>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Salary<span class="text_requried">*</span></label>

                                <select  id="hr_salary_id"   name="hr_salary_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($salaries as $salary)
                                    <option value="{{$salary->id}}" {{(old("hr_salary_id",$employeeSalary->id??'')==$salary->id? "selected" : "")}}>{{$salary->total_salary}}</option>
                                    @endforeach
                              
                                </select>

                                @can('hr edit record')
                                <button type="button" class="btn btn-sm btn-info"  data-toggle="modal" data-target="#salModal"><i class="fas fa-plus"></i>
                                </button>
                                @endcan 
                                                           
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Grade</label>
                                <select  name="grade"  class="form-control selectTwo">
                                    <option value=""></option>
                                    @for ($i = 1; $i < 15; $i++)
                                    <option value="{{$i}}" {{(old("grade",$data->grade??'')==$i? "selected" : "")}}>{{ $i }}</option>

                                    @endfor
                                </select>
                                
                                
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Letter Type<span class="text_requried">*</span></label>
                                 <select  id="hr_letter_type_id"   name="hr_letter_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($letterTypes as $letterType)
                                    <option value="{{$letterType->id}}" {{(old("hr_letter_type_id",$data->hr_letter_type_id??'')==$letterType->id? "selected" : "")}}>{{$letterType->name}}</option>
                                    @endforeach  
                                </select>
                                    
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Project<span class="text_requried">*</span></label>
                                 <select  id="pr_detail_id"   name="pr_detail_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($projects as $project)
                                    <option value="{{$project->id}}" {{(old("pr_detail_id",$data->pr_detail_id??'')==$project->id? "selected" : "")}}>{{$project->name}}</option>
                                    @endforeach  
                                </select>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Remarks</label>
                                <input type="text"  name="remarks" value="{{ old('remarks',$data->remarks??'') }}"  class="form-control" placeholder="Enter Remarks if any">
                                
                               
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
          @include('hr.appointment.salModal')
	</div> <!-- end card body -->    
  

    <style>

    input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>


<script>

   console.log('ok');
    //submit function
 
        $("#formAppointment").submit(function(e) { 
            e.preventDefault();
            var url = $(this).attr('action');
            $('.fa-spinner').show(); 
            submitForm(this, url);

        });
</script>




