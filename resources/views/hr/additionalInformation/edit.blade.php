
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "formAdditionalInformation" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('additionalInformation.update',session('hr_employee_id'))}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Additional Information</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Driving Licence No.</label><br>

                               	<input type="text"  name="licence_no" id="licence_no" value="{{ old('licence_no', $employee->hrDriving->licence_no??'') }}"  class="form-control excempted" placeholder="Enter Licence No">
                            </div>
                        </div>
                    </div>
                    
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Licence Expiry Date</label>
                                
                                <input type="text" name="licence_expiry" id="licence_expiry" value="{{ old('licence_expiry',$employee->hrDriving->licence_expiry??'') }}" class="form-control date_input" readonly>

                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Passport No.</label><br>

                                <input type="text"  name="passport_no" value="{{ old('passport_no', $employee->hrPassport->passport_no??'') }}"  class="form-control excempted" placeholder="Enter Passport No">
                            </div>
                        </div>
                    </div>
                    
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Passport Expiry Date</label>
                                
                                <input type="text" name="passport_expiry" value="{{ old('passport_expiry',$employee->hrPassport->passport_expiry??'') }}" class="form-control date_input" readonly>

                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
                 <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Blood Group</label><br>

                                 <select  name="blood_group_id" id="blood_group_id" class="form-control selectTwo">
                                    <option value=""></option>
                                @foreach($bloodGroups as $bloodGroup)
                                    <option value="{{$bloodGroup->id}}" {{(old("blood_group_id",$employee->hrBloodGroup->blood_group_id??'')==$bloodGroup->id? "selected" : "")}}>{{$bloodGroup->name}}</option>
                                @endforeach     
                                </select> 
                            </div>
                        </div>
                    </div>
                                                         
                    <!--/span-->
                    <div class="col-md-9">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Disability</label>
                                
                                <input type="text" name="detail" value="{{ old('detail',$employee->hrDisability->detail??'') }}" class="form-control" data-validation="length" data-validation-length="max190"  placeholder="If you have any disbility, please describe here">

                                
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
          @include('hr.appointment.salModal')
	</div> <!-- end card body -->    
  

    <style>

    input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>


<script>
$(document).ready(function(){
  
    //submit function
        $('#licence_no').on('change',function(){

            if($(this).val() !=''){
                $('#licence_expiry').attr('data-validation','required');
            }else {
                $('#licence_expiry').removeAttr('data-validation');
            }
        });

         $('#licence_expiry').on('change',function(){
             console.log('OK');
            if($(this).val() !=''){
                $('#licence_no').attr('data-validation','required');
            }else {
                $('#licence_no').removeAttr('data-validation');
            }
        });
 
        $("#formAdditionalInformation").submit(function(e) { 
           

            e.preventDefault();
            var url = $(this).attr('action');
            $('.fa-spinner').show(); 
            submitForm(this, url);

        });

         $('#marks_obtain, #total_marks').on('change',function(){

        if($(this).val() !=''){
        $(this).attr('data-validation','number');
        }else {
             $(this).removeAttr('data-validation');
        }
        });
});
</script>





