<div class="card-body">
    <button type="button" class="btn btn-success float-right" id="createOffice" data-toggle="modal">Add New Office</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Office</th>
                <th>Effective Date</th>
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
                <form id="officeForm" name="officeForm" action="{{route('employeeOffice.store')}}" class="form-horizontal">
                    <input type="hidden" name="employee_office_id" id="employee_office_id">
                    <input type="hidden" name="hr_employee_id" id="hr_employee_id" value="{{$id}}">
                    <div class="form-group">
                        <label class="control-label text-right">Office<span class="text_requried">*</span></label><br>
                        <select name="office_id" id="office_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach ($offices as $office)
                            <option value="{{$office->id}}">{{$office->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>

                        <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" data-validation="required" readonly>

                        <br>
                        @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {url:"{{ route('employeeOffice.create') }}",
                data: {hrEmployeeId: $("#hr_employee_id").val()
                }},
                columns: [{
                        data: "office_id",
                        name: 'office_id'
                    },
                    {
                        data: 'effective_date',
                        name: 'effective_date'
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

            $('#createOffice').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-Office");
                $('#employee_office_id').val('');

                $('#officeForm').trigger("reset");
                $('#office_id').trigger('change');
                $('#modelHeading').html("Create New Office");
                $('#ajaxModel').modal('show');
            });
            $('body').unbind().on('click', '.editEmployeeOffice', function() {
                var employee_office_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/employeeOffice') }}" + '/' + employee_office_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Office");
                    $('#saveBtn').val("edit-Office");
                    $('#ajaxModel').modal('show');
                    $('#employee_office_id').val(data.id);
                    $('#office_id').val(data.office_id);
                    $('#office_id').trigger('change');
                    $('#effective_date').val(data.effective_date);
                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');

                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $('#officeForm').serialize(),
                    url: "{{ route('employeeOffice.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#officeForm').trigger("reset");
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

            $('body').on('click', '.deleteEmployeeOffice', function() {

                var employee_office_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('employeeOffice.store') }}" + '/' + employee_office_id,
                        data: {hrEmployeeId: $("#hr_employee_id").val()},
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