<!-- Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Employee Input</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i>
</div>
           <form id="inputForm"  class="form-horizontal form-prevent-multiple-submits form-prevent-multiple-submits" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Name of Employee<span class="text_requried">*</span></label><br>
                          <select  id="hr_employee_id"   name="hr_employee_id"  class="form-control" data-validation="required">
                            <option></option>
                            @foreach($hrEmployees as $employee)
                            <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}}</option>
                            @endforeach  
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body" id="designation_div">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Designation<span class="text_requried">*</span></label><br>
                        <select  id="hr_designation_id"   name="hr_designation_id"  class="form-control" data-validation="required">
                            <option></option>
                            @foreach($hrDesignations as $hrDesignation)
                            <option value="{{$hrDesignation->id}}" {{(old("hr_designation_id")==$hrDesignation->id? "selected" : "")}}>{{$hrDesignation->name}}</option>
                            @endforeach  
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                   <div class="form-body" id="office_div">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Office<span class="text_requried">*</span></label><br>
                        <select  id="office_department_id"   name="office_department_id"  class="form-control" data-validation="required">
                            <option></option>
                            @foreach($officeDepartments as $department)
                            <option value="{{$department->id}}" {{(old("office_department_id")==$department->id? "selected" : "")}}>{{$department->name}}</option>
                            @endforeach  
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Input<span class="text_requried">*</span></label><br>
                        <input type="text" name="input" id="input" value="{{ old('input') }}" class="form-control" data-validation="required number" data-validation-allowing="range[0.03;1.0],float">
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Remarks</label><br>
                        <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control">
                      </div>
                    </div>                                                                
                  </div>
                  <input type="text" name="pr_detail_id" id="pr_detail_id" class="form-control" hidden required>
                  <input type="text" name="input_month_id" id="input_month_id" class="form-control" hidden required>
                  <input type="text" name="input_id" id="input_id" class="form-control" hidden>
                  
                  <hr>
                  <div class="form-actions">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="row">
                                  <div class="col-md-offset-3 col-md-9">
                                      <button type="submit" id="saveBtn" class="btn btn-success btn-prevent-multiple-submits"><i class="spinner fa fa-spinner fa-spin" ></i>Save</button>   
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

<script>

$(document).ready(function(){
    $.validate({
    validateHiddenInputs: true,
    });

   $('#hr_employee_id, #hr_designation_id, #office_department_id').select2({
        dropdownParent: $('#projectModal'),
        width: "100%",
        theme: "classic"
    });
    $('.hideDiv').hide();


});
</script>
<!--end Model--> 