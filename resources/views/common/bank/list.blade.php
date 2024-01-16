@extends('layouts.master.master')
@section('title', 'Bank Accounts List')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
    <div class="card-body">

        <button type="button" class="btn btn-success float-right" id="createBank" data-toggle="modal">Add Bank Account</button>
        <br>
        <table class="table table-striped data-table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Bank Name</th>
                    <th>Account No.</th>
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
                    <form id="bankForm" name="bankForm" action="{{route('employeeBank.store')}}" class="form-horizontal">
                        <input type="hidden" name="employee_bank_id" id="employee_bank_id">

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Name of Employee<span class="text_requried">*</span></label>
                                <select name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->employee_no}} - {{$employee->first_name}} {{$employee->last_name}} - {{$employee->designation}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Name of Bank<span class="text_requried">*</span></label>
                                <select name="bank_id" id="bank_id" class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-right">Account No<span class="text_requried">*</span></label>
                            <input type="text" name="account_no" id="account_no" value="{{ old('account_no') }}" class="form-control" data-validation="required">
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

            $('#hr_employee_id, #bank_id').select2({
                dropdownParent: $('#ajaxModel'),
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
                    ajax: "{{ route('allBankAccounts.list') }}",
                    columns: [{
                            data: "hr_employee_id",
                            name: 'hr_employee_id'
                        },
                        {
                            data: "bank_id",
                            name: 'bank_id'
                        },
                        {
                            data: 'account_no',
                            name: 'account_no'
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

                });

                $('#createBank').click(function() {
                    $('#json_message_modal').html('');
                    $('#saveBtn').val("create-extension");
                    $('#employee_bank_id').val('');
                    $('#bankForm').trigger("reset");
                    $('#bank_id').val('').trigger("change");
                    $('#hr_employee_id').val('').trigger("change");
                    $('#modelHeading').html("Create New Bank");
                    $('#ajaxModel').modal('show');
                });
                $('body').unbind().on('click', '.editEmployeeBank', function() {
                    var employee_bank_id = $(this).data('id');

                    $('#json_message_modal').html('');
                    $.get("{{ url('hrms/employeeBank') }}" + '/' + employee_bank_id + '/edit', function(data) {
                        $('#modelHeading').html("Edit Bank");
                        $('#saveBtn').val("edit-bank");
                        $('#ajaxModel').modal('show');
                        $('#hr_employee_id').val(data.hr_employee_id);
                        $('#employee_bank_id').val(data.id);
                        $('#account_no').val(data.account_no);
                        $('#bank_id').val(data.bank_id).trigger("change");


                    })
                });
                $('#saveBtn').unbind().click(function(e) {
                    $(this).attr('disabled', 'ture');

                    e.preventDefault();
                    $(this).html('Save');

                    $.ajax({
                        data: $('#bankForm').serialize(),
                        url: "{{ route('employeeBank.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('.btn-prevent-multiple-submits').removeAttr('disabled');
                            $('#bankForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
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

                $('body').on('click', '.deleteEmployeeBank', function() {

                    var employee_bank_id = $(this).data("id");
                    var con = confirm("Are You sure want to delete !");
                    if (con) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('employeeBank.store') }}" + '/' + employee_bank_id,
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
</div>
@stop