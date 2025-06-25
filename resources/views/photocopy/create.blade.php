@extends('layouts.master.master')
@section('title', 'Photocopies')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<!-- Model -->
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
                <form id="photocopyForm" name="photocopyForm" action="{{route('photocopy.store')}}"
                    class="form-horizontal">
                    <input type="hidden" name="photocopy_id" id="photocopy_id">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label text-right">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Type</label>
                                <select name="type" id="type" class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    <option value="Black & White">Black & White</option>
                                    <option value="Color">Color</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn"
                            value="create">Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    formFunctions();
    </script>
</div>
<!--End Modal  -->
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
        <button type="button" class="btn btn-success float-right" id="createPhotocopy" data-toggle="modal">Add
            Photocopy</button>
        <h4 class="card-title" style="color:black">List of Photocopies</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:70%">Name</th>
                        <th style="width:20%">Type</th>
                        <th style="width:5%">Edit</th>
                        @role('Super Admin')
                        <th style="width:5%">Delete</th>
                        @endrole
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
                url: "{{ route('photocopy.index') }}",
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'Edit',
                    name: 'Edit',
                    orderable: false,
                    searchable: false
                },
                @role('Super Admin') {
                    data: 'Delete',
                    name: 'Delete',
                    orderable: false,
                    searchable: false
                }
                @endrole
            ]
        });

        $('#createPhotocopy').click(function(e) {
            $('#json_message_modal').html('');
            $('#photocopyForm').trigger("reset");
            $("#photocopy_id").val('');
            $("#type").val('').trigger('change');
            $('#ajaxModel').modal('show');

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
                data: $('#photocopyForm').serialize(),
                url: "{{ route('photocopy.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error: function(data) {
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
        $('body').unbind().on('click', '.editPhotocopy', function() {
            var photocopy_id = $(this).data('id');
            $('#json_message_modal').html('');
            $.get("{{ url('photocopy') }}" + '/' + photocopy_id + '/edit', function(data) {

                $('#modelHeading').html("Edit Photocopy");
                $('#saveBtn').val("edit-Photocopy");
                $('#ajaxModel').modal('show');
                $('#photocopy_id').val(data.id);
                $('#name').val(data.name);
                $('#type').val(data.type).trigger('change');;
            })
        });

        $('body').on('click', '.deletePhotocopy', function() {
            var photocopy_id = $(this).data("id");
            var con = confirm("Are You sure want to delete !");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('photocopy.store') }}" + '/' + photocopy_id,
                    success: function(data) {
                        table.draw();
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
    }); // end function

}); //End document ready function
</script>

</div>

@stop