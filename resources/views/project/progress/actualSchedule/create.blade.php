<!-- Model -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="actualScheduleForm" name="actualScheduleForm" action="{{route('actualVsScheduledProgress.store')}}" class="form-horizontal">
                    <input type="hidden" name="actual_schedule_id" id="actual_schedule_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Contractor</label>
                                <select id="pr_contractor_id" name="pr_contractor_id" class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($prContractors as $prContractor)
                                    <option value="{{$prContractor->id}}" {{(old("pr_contractor_id")==$prContractor->id? "selected" : "")}}>{{$prContractor->contractor_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Month</label><br>
                                <input type="text" id="month" name="month" value="{{ old('month') }}" class="form-control date-picker" readonly>
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Schedule Progress</label>
                                <input type="text" name="schedule_progress" id="schedule_progress" data-validation="required" value="{{old('schedule_progress')}}" class="form-control notCapital">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Actual Progress</label>
                                <input type="text" name="actual_progress" id="actual_progress" value="{{old('actual_progress')}}" class="form-control notCapital">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Current Month Progress</label>
                                <input type="text" name="current_month_progress" id="current_month_progress" value="{{old('current_month_progress')}}" class="form-control notCapital">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        formFunctions();
    </script>
</div> <!--End Modal  -->
<style>
    .modal-dialog {
        max-width: 80%;
        display: flex;
    }

    .ui-timepicker-container {
        z-index: 1151 !important;
    }
</style>
<!-- End Modal -->
<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-success float-right" id="creatActualSchedule" data-toggle="modal">Add Progress</button>
        <h4 class="card-title" style="color:black">List of Progress</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:30%">Contractor Name</th>
                        <th style="width:30%">Month</th>
                        <th style="width:30%">Schedule Progress</th>
                        <th style="width:10%">Actual Progress</th>
                        <th style="width:10%">Current Month Progress</th>
                        <th style="width:5%">Edit</th>
                        <th style="width:5%">Delete</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
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

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('actualVsScheduledProgress.create') }}",
                },
                columns: [{
                        data: 'contractor_name',
                        name: 'contractor_name'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'schedule_progress',
                        name: 'schedule_progress'
                    },
                    {
                        data: 'actual_progress',
                        name: 'actual_progress'
                    },
                    {
                        data: 'current_month_progress',
                        name: 'current_month_progress'
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

                ]
            });

            $('#creatActualSchedule').click(function(e) {
                $('#json_message_modal').html('');
                $('#pr_contractor_id').val('').trigger('change');
                $('#actual_schedule_id').val("");
                $('#actualScheduleForm').trigger("reset");
                $('#ajaxModel').modal('show');


            });

            $('body').unbind().on('click', '.editActualSchedule', function() {
                var actual_schedule_id = $(this).data('id');
                $.get("{{ url('hrms/project/actualVsScheduledProgress') }}" + '/' + actual_schedule_id + '/edit', function(data) {
                    $('#json_message_modal').html('');
                    $('#actual_schedule_id').val(data.id);
                    $('#pr_contractor_id').val(data.pr_contractor_id).trigger('change');;
                    $('#month').val(data.month);
                    $('#schedule_progress').val(data.schedule_progress);
                    $('#actual_progress').val(data.actual_progress);
                    $('#current_month_progress').val(data.current_month_progress);
                    $('#ajaxModel').modal('show');
                });


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
                    data: $('#actualScheduleForm').serialize(),
                    url: "{{ route('actualVsScheduledProgress.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#ajaxModel').modal('hide');
                        $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
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

            $('body').on('click', '.deleteActualSchedule', function() {
                var actual_schedule_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('actualVsScheduledProgress.store') }}" + '/' + actual_schedule_id,
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
        }); // end function

    }); //End document ready function
</script>

</div>
