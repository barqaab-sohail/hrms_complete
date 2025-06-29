@extends('layouts.master.master')
@section('title', 'Project Customer No')
@section('Heading')
<h3 class="text-themecolor">Project Customer No</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        @can('pr add customer no')
        <button type="button" class="btn btn-success float-right" id="createCustomerNo" data-toggle="modal">Add Customer No</button>
        @endcan
        <br>
        <div class="table-responsive">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th style="width:60%">Project Name</th>
                        <th style="width:20%">Customer No</th>
                        @can('pr add customer no')
                        <th style="width:10%">Edit</th>
                        <th style="width:10%">Delete</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
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
                <form id="customerNoForm" name="customerNoForm" action="{{route('projectCustomerNo.store')}}" class="form-horizontal">

                    <input type="hidden" name="customer_id" id="customer_id">
                    <div class="form-group">
                        <label class="control-label text-right">Project Name<span class="text_requried">*</span></label>
                        <select id="pr_detail_id" name="pr_detail_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($projects as $project)
                            <option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->project_no}} - {{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">

                        <label class="control-label">Customer No</label>

                        <input type="text" name="customer_no" id="customer_no" value="{{old('customer_no')}}" class="form-control exempted" data-validation="required" autocomplete="off">

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
<style>
    .modal-dialog {
        max-width: 90%;
        display: flex;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {

        formFunctions();


        var customerNo = document.getElementById('customer_no'),
            cleanPhoneNumber;

        //Clean copy and past text only digit
        cleanPhoneNumber = function(e) {
            e.preventDefault();
            var pastedText = '';
            if (window.clipboardData && window.clipboardData.getData) { // IE
                pastedText = window.clipboardData.getData('Text');
            } else if (e.clipboardData && e.clipboardData.getData) {
                pastedText = e.clipboardData.getData('text/plain');
            }
            this.value = pastedText.replace(/\D/g, '');
        };

        customerNo.onpaste = cleanPhoneNumber;


        //avoid blank space
        $(document).on('keydown', '#customer_no', function(e) {
            if (e.keyCode == 32) return false;
        });

        //allow only digit
        $('#customer_no').keypress(function(e) {

            var charCode = (e.which) ? e.which : event.keyCode

            if (String.fromCharCode(charCode).match(/[^0-9]/g))

                return false;

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
                ajax: "{{ route('projectCustomerNo.create') }}",
                columns: [{
                        data: "pr_detail_id",
                        name: 'pr_detail_id'
                    },
                    {
                        data: "customer_no",
                        name: 'customer_no'
                    },
                    @can('pr add customer no') {
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
                    @endcan
                ],

                order: [
                    [0, "desc"]
                ]
            });

            $('#createCustomerNo').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("Create Customer No");
                $('#customer_id').val('');
                $('#pr_detail_id').val('');
                $('#pr_detail_id').trigger('change');
                $('#customer_no').val('');
                $('#customerNoForm').trigger("reset");
                $('#modelHeading').html("Create New Customer No");
                $('#ajaxModel').modal('show');
            });
            $('body').unbind().on('click', '.editProjectCustomerNo', function() {

                var customer_id = $(this).data('id');
                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/projectCustomerNo') }}" + '/' + customer_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Customer No");
                    $('#saveBtn').val("edit-customer-no");
                    $('#ajaxModel').modal('show');
                    $('#customer_id').val(data.id);
                    $('#pr_detail_id').val(data.pr_detail_id);
                    $('#pr_detail_id').trigger('change');
                    $('#customer_no').val(data.customer_no);

                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');

                e.preventDefault();

                $(this).html('Save');

                $.ajax({
                    data: $('#customerNoForm').serialize(),
                    url: "{{ route('projectCustomerNo.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#customerNoForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        if (data.error) {
                            $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                        } else {
                            $('#json_message').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
                        }
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

            $('body').on('click', '.deleteProjectCustomerNo', function() {

                var customer_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('projectCustomerNo.store') }}" + '/' + customer_id,
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
