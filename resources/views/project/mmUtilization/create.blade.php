<div class="card-body">
    <button type="button" class="btn btn-success float-right" id="createNewPosition" data-toggle="modal">Add New Utilization</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Month</th>
                <th>Manmonth</th>
                <th>Billing Rate</th>
                <th>Total</th>
                <th>Remarks</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="positionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="utilizationForm" name="utilizationForm" class="form-horizontal">
                    <input type="hidden" name="utilization_id" id="utilization_id">
                    <div class="form-group">
                        <label class="control-label text-right">Employee Name</label><br>
                        <select name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach ($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->employee_no}}-{{$employee->full_name}}-{{$employee->employeeDesignation->last()->name??''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Position<span class="text_requried">*</span></label><br>
                        <select name="pr_position_id" id="pr_position_id" class="form-control" data-validation="required">
                            <option value=""></option>
                            @foreach($positions as $position)
                            <option value="{{$position->id}}" {{(old("pr_position_id")==$position->id? "selected" : "")}}>{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Month</label>
                        <input type="text" name="month" id="month" value="{{ old('month') }}" class="form-control date-picker" data-validation="required" readonly>
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Man Month</label><br>
                        <input type="number" step="0.001" id="man_month" name="man_month" value="{{ old('man_month') }}" class="form-control" data-validation-allowing="range[0.001;1.00],float">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Remarks</label>
                        <input type="text" id="remarks" name="remarks" value="{{ old('remarks') }}" class="form-control">
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(function() {
            $('.date-picker').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'MM yy',
                onClose: function(dateText, inst) {
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                    if ($('.date-picker').val() != '') {
                        $('.date-picker').siblings('i').show();
                    } else {
                        $('.date-picker').siblings('i').hide();
                    }
                }
            });
            $('.date-picker').siblings('i').hide();


        });
        $("#hr_employee_id").change(function() {
            //const result = $("#hr_employee_id option:selected").text().split('-').pop();
            const result = $("#hr_employee_id option:selected").text().split('-');
            $("#nominated_person").val(result[1]);
        });

        $('#pr_position_id, #pr_position_type_id').select2({
            dropdownParent: $('#positionModal'),
            width: "100%",
            theme: "classic"
        });

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('projectPosition.create') }}",
                columns: [{
                        data: "nominated_person",
                        name: 'nominated_person'
                    },
                    {
                        data: "pr_position_id",
                        name: 'pr_position_id'
                    },
                    {
                        data: 'pr_position_type_id',
                        name: 'pr_position_type_id'
                    },
                    {
                        data: 'man_month',
                        name: 'man_month'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'delete',
                        name: 'delete',
                        orderable: false,
                        searchable: false
                    }

                ],
                order: [
                    [2, "desc"]
                ]
            });

            $('#createNewPosition').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-Position");
                $('#utilization_id').val('');

                $('#utilizationForm').trigger("reset");
                $('#pr_position_id').trigger('change');
                $('#hr_employee_id').trigger('change');
                $('#pr_position_type_id').trigger('change');
                $('#modelHeading').html("Create New Position");
                $('#positionModal').modal('show');
            });
            $('body').unbind().on('click', '.editPosition', function() {
                var utilization_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/projectPosition') }}" + '/' + utilization_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Position");
                    $('#saveBtn').val("edit-Position");
                    $('#positionModal').modal('show');
                    $('#utilization_id').val(data.id);
                    $('#hr_employee_id').val(data.hr_employee_id);
                    $('#hr_employee_id').trigger('change');
                    $('#pr_position_id').val(data.pr_position_id);
                    $('#pr_position_id').trigger('change');
                    $('#pr_position_type_id').val(data.pr_position_type_id);
                    $('#pr_position_type_id').trigger('change');
                    $('#nominated_person').val(data.nominated_person);
                    $('#man_month').val(data.man_month);


                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');
                //submit enalbe after 3 second
                setTimeout(function() {
                    $('.btn-prevent-multiple-submits').removeAttr('disabled');
                }, 3000);

                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $('#utilizationForm').serialize(),
                    url: "{{ route('projectPosition.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        $('#utilizationForm').trigger("reset");
                        $('#positionModal').modal('hide');
                        table.draw();

                    },
                    error: function(data) {

                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                        });
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deletePosition', function() {

                var utilization_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('projectPosition.store') }}" + '/' + utilization_id,
                        success: function(data) {
                            table.draw();
                            if (data.error) {
                                $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                            }

                        },
                        error: function(data) {

                        }
                    });
                }
            });

        });
    });
</script>
