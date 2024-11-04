<div class="card-body">

    <button type="button" class="btn btn-success float-right" id="createCompany" data-toggle="modal">Add
        Company</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Effective Date</th>
                <th>End Date</th>
                <th>Status</th>
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
                <div id="json_message_modal" align="left"><strong></strong><i hidden
                        class="fas fa-times float-right"></i> </div>
                <form id="companyForm" name="companyForm" action="{{route('employeeCompany.store')}}"
                    class="form-horizontal">
                    <input type="hidden" name="employee_company_id" id="employee_company_id">
                    <input type="hidden" name="hr_employee_id" id="hr_employee_id" value="{{$id}}">
                    <div class="form-group">
                        <label class="control-label text-right">Company Name<span class="text_requried">*</span></label>
                        <select id="partner_id" name="partner_id" data-validation="required"
                            class="form-control selectTwo">
                            <option></option>
                            @foreach($partners as $partner)
                            <option value="{{$partner->id}}" {{(old("partner_id")==$partner->id? "selected" : "")}}>
                                {{$partner->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label text-right">Effective Date<span
                                class="text_requried">*</span></label>
                        <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}"
                            class="form-control date_input" data-validation="required" readonly>
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">End Date<span class="text_requried">*</span></label>
                        <input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="form-control date_input" data-validation="required" readonly>
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Status<span class="text_requried">*</span></label>
                        <select id="status" name="status" data-validation="required" class="form-control selectTwo">
                            <option></option>
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                        </select>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn"
                            value="create">Save changes
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
            ajax: {
                url: "{{ route('employeeCompany.create') }}",
                data: {
                    hrEmployeeId: $("#hr_employee_id").val()
                }
            },
            columns: [{
                    data: "partner_id",
                    name: 'partner_id'
                },
                {
                    data: 'effective_date',
                    name: 'effective_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'status',
                    name: 'status'
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

        $('#createCompany').click(function() {
            $('#json_message_modal').html('');
            $('#saveBtn').val("create-company");
            $('#employee_company_id').val('');
            $('#companyForm').trigger("reset");
            $("#partner_id").val('').trigger('change');
            $("#status").val('').trigger('change');
            $('#modelHeading').html("Create New Company");
            $('#ajaxModel').modal('show');
        });
        $('body').unbind().on('click', '.editCompany', function() {
            var employee_company_id = $(this).data('id');

            $('#json_message_modal').html('');
            $.get("{{ url('hrms/employeeCompany') }}" + '/' + employee_company_id + '/edit',
                function(data) {
                    $('#modelHeading').html("Edit Company");
                    $('#saveBtn').val("edit-company");
                    $('#ajaxModel').modal('show');
                    $('#employee_company_id').val(data.id);
                    $('#partner_id').val(data.partner_id).trigger('change');
                    $('#status').val(data.status).trigger('change');
                    $('#effective_date').val(data.effective_date);
                    $('#end_date').val(data.end_date);


                })
        });
        $('#saveBtn').unbind().click(function(e) {
            $(this).attr('disabled', 'ture');

            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data: $('#companyForm').serialize(),
                url: "{{ route('employeeCompany.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('.btn-prevent-multiple-submits').removeAttr('disabled');
                    $('#companyForm').trigger("reset");
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
                    $('#json_message_modal').html(
                        '<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                        errorMassage + '</strong></div>');

                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteCompany', function() {

            var employee_company_id = $(this).data("id");
            var con = confirm("Are You sure want to delete !");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employeeCompany.store') }}" + '/' +
                        employee_company_id,
                    data: {
                        hrEmployeeId: $("#hr_employee_id").val()
                    },
                    success: function(data) {
                        table.draw();
                        clearMessage();
                        if (data.error) {
                            $('#json_message').html(
                                '<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                                data.error + '</strong></div>');
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