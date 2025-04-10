<div style="margin-top:10px; margin-right: 10px;">
    <button type="button" class="btn btn-success float-right" id="change_data" data-toggle="tooltip" title="Back to List">Change Data</button>
</div>

<div class="card-body">
    <form id="formAppointment" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('appointment.update',$id)}}" enctype="multipart/form-data">
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

                            <input type="text" name="reference_no" value="{{ old('reference_no', $hrEmployee->employeeAppointment->reference_no??'') }}" class="form-control exempted" data-validation="required" placeholder="Enter Appointment Letter Reference No">
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Joining Date<span class="text_requried">*</span></label>

                            <input type="text" name="joining_date" id="joining_date" value="{{ old('joining_date',$hrEmployee->employeeAppointment->joining_date??'') }}" class="form-control date_input" data-validation="required" readonly>

                            <br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan
                        </div>
                    </div>
                </div>
                <!--/span-->
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Duration</label>
                            <select name="duration" id="duration" class="form-control selectTwo">

                                <option value=""></option>
                                @for ($i = 1; $i < 13; $i++) <option value="{{$i}}">{{ $i }} Month</option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Expiry Date</label>
                            <input type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date',$hrEmployee->employeeAppointment->expiry_date??'') }}" class="form-control date_input" readonly>
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
                            <label class="control-label text-right">Designation/Position<span class="text_requried">*</span></label>
                            <select id="hr_designation_id" name="hr_designation_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentDesignation->id??''}}">{{$hrEmployee->employeeAppointment->appointmentDesignation->name??''}}</option>
                            </select>
                            @can('hr add designation')
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#designationModal"><i class="fas fa-plus"></i>
                            </button>
                            @endcan


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">HOD<span class="text_requried">*</span></label>
                            <select id="hr_manager_id" name="hr_manager_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentHOD->id??''}}">{{$hrEmployee->employeeAppointment->appointmentHOD->full_name??''}}</option>

                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Department<span class="text_requried">*</span></label>
                            <select id="hr_department_id" name="hr_department_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentDepartment->id??''}}">{{$hrEmployee->employeeAppointment->appointmentDepartment->name??''}}</option>

                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Category<span class="text_requried">*</span></label>
                            <select name="hr_category_id" id="hr_category_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentCategory->id??''}}">{{$hrEmployee->employeeAppointment->appointmentCategory->name??''}}</option>

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

                            <select id="hr_salary_id" name="hr_salary_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentSalary->id??''}}">{{$hrEmployee->employeeAppointment->appointmentSalary->total_salary??''}}</option>


                            </select>

                            @can('hr edit record')
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#salModal"><i class="fas fa-plus"></i>
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
                            <select name="hr_grade_id" id="hr_grade_id" class="form-control selectTwo">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentGrade->id??''}}">{{$hrEmployee->employeeAppointment->appointmentGrade->name??''}}</option>

                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Appointment Letter Type<span class="text_requried">*</span></label>
                            <select id="hr_letter_type_id" name="hr_letter_type_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentLetterType->id??''}}">{{$hrEmployee->employeeAppointment->appointmentLetterType->name??''}}</option>

                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Project<span class="text_requried">*</span></label>
                            <select id="pr_detail_id" name="pr_detail_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentProject->id??''}}">{{$hrEmployee->employeeAppointment->appointmentProject->name??''}}</option>
                            </select>


                        </div>
                    </div>
                </div>
            </div><!--/End Row-->

            <div class="row">
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Employee Type<span class="text_requried">*</span></label>
                            <select name="hr_employee_type_id" id="hr_employee_type_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentEmployeeType->id??''}}">{{$hrEmployee->employeeAppointment->appointmentEmployeeType->name??''}}</option>


                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Office<span class="text_requried">*</span></label>
                            <select id="office_id" name="office_id" class="form-control selectTwo" data-validation="required">
                                <option value="{{$hrEmployee->employeeAppointment->appointmentOffice->id??''}}">{{$hrEmployee->employeeAppointment->appointmentOffice->name??''}}</option>


                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Remarks</label>
                            <input type="text" name="remarks" value="{{ old('remarks',$hrEmployee->employeeAppointment->remarks??'') }}" data-validation="length" data-validation-length="max190" class="form-control" placeholder="Enter Remarks if any">


                        </div>
                    </div>
                </div>
            </div><!--/End Row-->
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="text" name="url" id="url" class="form-control" hidden>


                        </div>
                    </div>
                </div>
            </div><!--/End Row-->


        </div> <!--/End Form Boday-->

        <hr>
        @can('hr edit appointment')

        <div class="form-actions" id="action_div">
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
        @endcan
    </form>

    @include('hr.appointment.salModal')
    @can('hr add designation')
    @include('hr.appointment.designationModal')
    @endcan
</div> <!-- end card body -->


<style>
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>


<script>
    $(document).ready(function() {
        $("#action_div").hide();
        $("input").attr('readonly', true);
        $('select').attr('disabled', 'disabled');
        //Add Month Function
        function addMonths(date, months) {
            var d = date.getDate();
            date.setMonth(date.getMonth() + +months);
            if (date.getDate() != d) {
                date.setDate(0);
            }
            return date;
        }

        function addMonths(date, months) {
            var d = date.getDate();
            date.setMonth(date.getMonth() + +months);
            if (date.getDate() != d) {
                date.setDate(0);
            }
            return date;
        }

        // Automatic add Expiry
        $("#duration").change(function() {
            var addMonth = $(this).val();
            if (addMonth) {
                var joiningDate = $("#joining_date").val();
                //var addMonth = $(this).val();
                //var expiryDate = addMonths(new Date(joiningDate), addMonth) - 1;
                var expiryDate = addMonths(new Date(joiningDate), addMonth) - 1;
                var dateFormat = dateInDayMonthYear(expiryDate);
                $("#expiry_date").val(dateFormat);
                $(".fa-trash-alt").show();
            } else {
                $("#expiry_date").val('');
                $("#expiry_date").siblings('i').hide();
                //$(".fa-trash-alt").hide();
            }

        });

        isUserData(window.location.href, "{{URL::to('/hrms/employee/user/data')}}");

        //Add Comma only for Display
        $("#hr_salary_id option").each(function() {
            var test = this.text;
            test = test.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $(this).text(test);
        });

        //submit function
        $("#formAppointment").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            $('.fa-spinner').show();
            submitForm(this, url);
        });


        $('#change_data').click(function() {
            if ($("#hr_salary_id option").length == 1) {
                $.ajax({
                    type: "get",
                    url: "{{url('hrms/getAppointmentData')}}",

                    success: function(res) {
                        //console.log(JSON.stringify(res.salaries));
                        // console.log('lengt..'+($("#hr_salary_id option").length));
                        if (res) {
                            $.each(res.salaries, function(key, value) {
                                $("#hr_salary_id").append('<option value="' + value.id + '">' + value.total_salary + '</option>');
                            });

                            $.each(res.designations, function(key, value) {
                                $("#hr_designation_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.departments, function(key, value) {
                                $("#hr_department_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.categories, function(key, value) {
                                $("#hr_category_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.grades, function(key, value) {
                                $("#hr_grade_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.letterTypes, function(key, value) {
                                $("#hr_letter_type_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.projects, function(key, value) {
                                $("#pr_detail_id").append('<option value="' + value.id + '">' + value.project_no + ' - ' + value.name + '</option>');
                            });

                            $.each(res.offices, function(key, value) {
                                $("#office_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.employeeTypes, function(key, value) {
                                $("#hr_employee_type_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $.each(res.managers, function(key, value) {
                                $("#hr_manager_id").append('<option value="' + value.id + '">' + value.employee_no + ' - ' + value.first_name + ' ' + value.last_name + ', ' + value.designation + '</option>');
                            });


                        }
                    }
                }); //end ajax
            }
            if ($('#action_div:visible').length == 0) {
                $("input").attr('readonly', false);
                $('select').attr('disabled', false);
            } else {
                $("input").attr('readonly', true);
                $('select').attr('disabled', true);
            }
            $('#action_div').toggle();

        });


    });
</script>