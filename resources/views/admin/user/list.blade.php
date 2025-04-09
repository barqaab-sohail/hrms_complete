@extends('layouts.master.master')
@section('title', 'User')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="modal fade" id="userModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="userForm" name="userForm" class="form-horizontal">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-group">
                        <label class="control-label text-right">Employee Name</label><br>
                        <select name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach ($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->employee_no}}-{{$employee->first_name}} {{$employee->last_name}}-{{$employee->employeeCurrentDesignation?->name??''}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label text-right">Email<span class="text_requried">*</span></label><br>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control">
                    </div>


                    <div class="form-group">
                        <label class="control-label">User Status</label>
                        <select name="user_status" id="user_status" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            <option value="0">Not Registered</option>
                            <option value="1">Registered</option>
                            <option value="2">Blocked</option>
                        </select>
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
<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-success float-right" id="createNewUser" data-toggle="modal">Add New User</button>



        <h4 class="card-title" style="color:black">List of Users</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Edit</th>
                        <th>Delete</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                "aaSorting": [],
                ajax: {
                    url: "{{ route('addUser.index') }}",
                },
                columns: [{
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'edit',
                        name: 'edit'
                    },
                    {
                        data: 'delete',
                        name: 'delete'
                    },
                ]
            });

            $('#hr_employee_id, #user_status').select2({
                dropdownParent: $('#userModal'),
                width: "100%",
                theme: "classic"
            });

            $('#createNewUser').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-User");
                $('#user_id').val('');
                $('#userForm').trigger("reset");
                $('#hr_employee_id').trigger('change');
                $('#user_status').trigger('change');
                $('#modelHeading').html("Create New User");
                $('#userModal').modal('show');
            });
            $('body').unbind().on('click', '.editUser', function() {
                var user_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/admin/addUser') }}" + '/' + user_id + '/edit', function(data) {
                    console.log(data);
                    $('#modelHeading').html("Edit User");
                    $('#saveBtn').val("edit-user");
                    $('#userModal').modal('show');
                    $('#user_id').val(data.id);
                    if (data.mis_employee_user) {
                        $('#hr_employee_id').val(data.mis_employee_user.id);
                    } else {
                        $('#hr_employee_id').val(data.hr_employee.id);
                    }
                    $('#hr_employee_id').trigger('change');
                    $('#user_status').val(data.user_status);
                    $('#user_status').trigger('change');
                    $('#email').val(data.email);
                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');

                e.preventDefault();
                $(this).html('Save');
                $.ajax({
                    data: $('#userForm').serialize(),
                    url: "{{ route('addUser.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#userForm').trigger("reset");
                        $('#userModal').modal('hide');
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

            $('body').on('click', '.deleteUser', function() {

                var user_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('addUser.store') }}" + '/' + user_id,
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

@stop