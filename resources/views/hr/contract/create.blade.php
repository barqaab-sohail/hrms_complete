<div class="card-body">

    <button type="button" class="btn btn-success float-right" id="createContract" data-toggle="modal">Add Extension</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>from</th>
                <th>to</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="contractForm" name="contractForm" action="{{route('employeeContract.store')}}" class="form-horizontal">
                    <input type="hidden" name="employee_contract_id" id="employee_contract_id">
                    <input type="hidden" name="hr_employee_id" id="hr_employee_id" value="{{$id}}">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Duration</label>
                            <select name="duration" id="duration" class="form-control selectTwo">
                                <option value=""></option>
                                <option value="6">Six Months</option>
                                <option value="1">One Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">From<span class="text_requried">*</span></label>
                        <input type="text" name="from" id="from" value="{{ old('from') }}" class="form-control date_input" data-validation="required" readonly>
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">To<span class="text_requried">*</span></label>
                        <input type="text" name="to" id="to" value="{{ old('to') }}" class="form-control date_input" data-validation="required" readonly>
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
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

        $("#duration").change(function() {
            var duration = $(this).val();
            if (duration == 1) {
                $("#from").val("2023-08-01");
                $("#to").val("2024-07-31");
            } else if (duration == 6) {
                $("#from").val("2023-08-01");
                $("#to").val("2024-01-31");
            } else {
                $("#from").val("");
                $("#to").val("");
            }
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
                ajax: {url:"{{ route('employeeContract.create') }}",
                data:{ hrEmployeeId: $("#hr_employee_id").val()}},
                columns: [{
                        data: "hr_employee_id",
                        name: 'hr_employee_id'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
                    {
                        data: 'to',
                        name: 'to'
                    },
                    {
                        data: 'Edit',
                        name: 'Edit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'Delete',
                        name: 'Delete',
                        orderable: false,
                        searchable: false
                    },

                ],
                order: [
                    [1, "desc"]
                ]
            });

            $('#createContract').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-extension");
                $('#employee_contract_id').val('');
                $('#contractForm').trigger("reset");
                $('#duration').val('').trigger("change");
                $('#modelHeading').html("Create New Extension");
                $('#ajaxModel').modal('show');
            });
            $('body').unbind().on('click', '.editContract', function() {
                var employee_contract_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/employeeContract') }}" + '/' + employee_contract_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Extension");
                    $('#saveBtn').val("edit-extension");
                    $('#ajaxModel').modal('show');
                    $('#employee_contract_id').val(data.id);
                    $('#from').val(data.from);
                    $('#to').val(data.to);
                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');

                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $('#contractForm').serialize(),
                    url: "{{ route('employeeContract.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#contractForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        clearMessage();
                    },
                    error: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                        });
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteContract', function() {

                var employee_contract_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('employeeContract.store') }}" + '/' + employee_contract_id,
                        data:{ hrEmployeeId: $("#hr_employee_id").val()},
                        success: function(data) {
                            table.draw();
                            clearMessage();
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