@extends('layouts.master.master')
@section('title', 'Allowance List')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card-body">
    <button type="button" class="btn btn-success float-right" id="createAllowanceName" data-toggle="modal">Add New Allowance</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
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
                <form id="allowanceNameForm" name="allowanceNameForm" class="form-horizontal">
                    <input type="hidden" name="allowance_name_id" id="allowance_name_id">
                    <div class="form-group">
                        <label class="control-label text-right">Name<span class="text_requried">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" data-validation="required">
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
                ajax:"{{ route('allowanceName.loadData') }}",
                columns: [{
                        data: "name",
                        name: 'name'
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

            $('#createAllowanceName').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-Allowance");
                $('#allowance_name_id').val('');
                $('#modelHeading').html("Add Allowance");
                $('#allowanceNameForm').trigger("reset");
                $('#ajaxModel').modal('show');
            });
            $('body').unbind().on('click', '.editAllowanceName', function() {
                var allowance_name_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/misc/allowanceName') }}" + '/' + allowance_name_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Allowance");
                    $('#saveBtn').val("edit-Allowance");
                    $('#ajaxModel').modal('show');
                    $('#allowance_name_id').val(data.id);
                    $('#name').val(data.name);
                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');

                e.preventDefault();
                $(this).html('Save');
                console.log($('#allowanceNameForm').serialize());
                $.ajax({
                   
                    url: "{{ route('allowanceName.store') }}",
                     data: $('#allowanceNameForm').serialize(),
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#allowanceNameForm').trigger("reset");
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

            $('body').on('click', '.deleteAllowanceName', function() {

                var allowance_name_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('allowanceName.store') }}" + '/' + allowance_name_id,
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
@stop