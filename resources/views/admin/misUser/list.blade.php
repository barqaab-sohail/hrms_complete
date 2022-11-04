@extends('layouts.master.master')
@section('title', 'MIS User Rights')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')

<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-success float-right" id="createMisUser" data-toggle="modal">Add Office</button>
        <h4 class="card-title" style="color:black">List of Users</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:25%">Name</th>
                        <th style="width:25%">Email Address</th>
                        <th style="width:15%">Status</th>
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
                    url: "{{ route('misUser.index') }}",
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
                        data: 'is_allow_mis',
                        name: 'is_allow_mis'
                    },
                ]
            });


            $('body').unbind().on('click', '.editMisUser', function() {
                var user_id = $(this).data('id');
                var is_allow_mis = $(this).data('mis-user');
                var con = confirm("Are You sure want to change rights !");
                if (con) {
                    $.ajax({
                        data: {
                            userId: user_id,
                            isAllowMis: is_allow_mis,
                        },
                        url: "{{ route('misUser.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.message + '</strong></div>');
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
                }

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
                    data: $('#misUserForm').serialize(),
                    url: "{{ route('misUser.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#ajaxModel').modal('hide');
                        $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.message + '</strong></div>');
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

        }); // end function

    }); //End document ready function
</script>

</div>

@stop