@extends('layouts.master.master')
@section('title', 'Project Ledger Url')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-success float-right" id="createUrl" data-toggle="modal">Add Url</button>
        <br>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>URL</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
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
                <form id="projectRightsForm" name="projectRightsForm" action="{{route('projectRights.store')}}" class="form-horizontal">

                    <input type="hidden" name="link_id" id="link_id">
                    <div class="form-group">
                        <label class="control-label text-right">Project Name<span class="text_requried">*</span></label>
                        <select id="pr_detail_id" name="pr_detail_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($projects as $project)
                            <option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">URL<span class="text_requried">*</span></label>

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

        formFunctions();

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('projectRights.create') }}",
                columns: [{
                        data: "pr_detail_id",
                        name: 'pr_detail_id'
                    },
                    {
                        data: "url",
                        name: 'url'
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
                    [0, "desc"]
                ]
            });

            $('#createUrl').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("Create Rights");
                $('#link_id').val('');
                $('#hr_employee_id').val('');
                $('#pr_detail_id').val('');
                $('#progress').val('');
                $('#invoice').val('');
                $('#payment').val('');
                $('#hr_employee_id').trigger('change');
                $('#pr_detail_id').trigger('change');
                $('#progress').trigger('change');
                $('#invoice').trigger('change');
                $('#payment').trigger('change');
                $('#projectRightsForm').trigger("reset");
                $('#modelHeading').html("Create New Right");
                $('#ajaxModel').modal('show');
            });
            $('body').unbind().on('click', '.editProjectRight', function() {

                var link_id = $(this).data('id');
                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/projectRights') }}" + '/' + link_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Right");
                    $('#saveBtn').val("edit-right");
                    $('#ajaxModel').modal('show');
                    $('#link_id').val(data.id);
                    $('#hr_employee_id').val(data.hr_employee_id);
                    $('#hr_employee_id').trigger('change');
                    $('#pr_detail_id').val(data.pr_detail_id);
                    $('#pr_detail_id').trigger('change');
                    $('#progress').val(data.progress);
                    $('#progress').trigger('change');
                    $('#invoice').val(data.invoice);
                    $('#invoice').trigger('change');
                    $('#payment').val(data.payment);
                    $('#payment').trigger('change');
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
                    data: $('#projectRightsForm').serialize(),
                    url: "{{ route('projectRights.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        $('#projectRightsForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        if (data.error) {
                            $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                        } else {
                            $('#json_message').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
                        }
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

            $('body').on('click', '.deleteProjectRight', function() {

                var link_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('projectRights.store') }}" + '/' + link_id,
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
@stop