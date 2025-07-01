@extends('layouts.master.master')
@section('title', 'HR Reports')
@section('Heading')
<h3 class="text-themecolor">HR Reports Management</h3>
@stop
@section('content')

<!-- Modal for Create/Edit -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i></div>
                <form id="reportForm" name="reportForm" class="form-horizontal">
                    <input type="hidden" name="report_id" id="report_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-right">Report Name<span class="text_requried">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-right">Route Name<span class="text_requried">*</span></label>
                                <input type="text" name="route" id="route" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-right">Description</label>
                                <input class="form-control" id="description" name="description"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Display Order</label>
                                <input type="number" class="form-control" id="order" name="order" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<div class="card">
    <div class="card-body">
        @can('hr-reports-edit')
        <button type="button" class="btn btn-success float-right" id="createReport">Add New Report</button>
        @endcan
        <h4 class="card-title">List of HR Reports</h4>
        <div class="table-responsive m-t-40">
            <table id="reportsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        @canany(['hr-reports-edit', 'hr-reports-delete'])
                        <th>Order</th>
                        <th>Actions</th>
                        @endcanany
                       
                      
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#reportsTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: false, // Disables user sorting
        ajax: "{{ route('hr.reports.index') }}",
        columns: [
            { 
                data: 'name', 
                name: 'name',
                render: function(data, type, row) {
                     // Disable link if status is inactive
                     if (row.is_active == 0) {
                        return '<span class="text-muted">' + data + '</span>' +
                            (row.description ? '<br><small class="text-muted">' + row.description + '</small>' : '');
                    } else {
                        return '<a href="'+row.full_url+'" style="color:black">'+data+'</a>' +
                            (row.description ? '<br><small class="text-muted">'+row.description+'</small>' : '');
                    }
                }
            },
            { data: 'description', name: 'description', visible: false },
            { 
                data: 'is_active', 
                name: 'is_active',
                render: function(data) {
                    return data ? '<span class="badge badge-success">Active</span>' : 
                                  '<span class="badge badge-danger">Inactive</span>';
                }
            },
            @canany(['hr-reports-edit', 'hr-reports-delete'])
            { data: 'order', name: 'order' },
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
            @endcanany
         
        ]
    });

    // Create new report
    $('#createReport').click(function() {
        $('#json_message_modal').html('');
        $('#report_id').val('');
        $('#reportForm').trigger("reset");
        $('#modelHeading').html("Create New Report");
        $('#ajaxModel').modal('show');
    });

    // Edit report
    $('body').on('click', '.editReport', function() {
        var report_id = $(this).data('id');
        $('#json_message_modal').html('');
        
        $.get("{{ route('hr.reports.index') }}" + '/' + report_id + '/edit', function(data) {
            $('#modelHeading').html("Edit Report");
            $('#ajaxModel').modal('show');
            $('#report_id').val(data.id);
            $('#name').val(data.name);
            $('#route').val(data.route);
            $('#description').val(data.description);
            $('#is_active').val(data.is_active).change();
            $('#order').val(data.order);
        });
    });

    // Save report (create/update)
    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('Saving...').attr('disabled', true);
        
        var formData = $('#reportForm').serialize();
        var url = $('#report_id').val() ? 
                  "{{ route('hr.reports.index') }}/" + $('#report_id').val() :
                  "{{ route('hr.reports.store') }}";
        var method = $('#report_id').val() ? 'PUT' : 'POST';

        $.ajax({
            data: formData,
            url: url,
            type: method,
            dataType: 'json',
            success: function(data) {
                $('#reportForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();
                $('#saveBtn').html('Save').removeAttr('disabled');
                
                // Show success message
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
            },
            error: function(data) {
                var errorMassage = '';
                $.each(data.responseJSON.errors, function(key, value) {
                    errorMassage += value + '<br>';
                });
                $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
                $('#saveBtn').html('Save').removeAttr('disabled');
            }
        });
    });

    // Delete report
    $('body').on('click', '.deleteReport', function() {
        var report_id = $(this).data("id");
        if(confirm("Are you sure you want to delete this report?")) {
            $.ajax({
                type: "DELETE",
                url: "{{ route('hr.reports.index') }}/" + report_id,
                success: function(data) {
                    table.draw();
                    $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                },
                error: function(data) {
                    $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.responseJSON.message+'</strong></div>');
                }
            });
        }
    });
});
</script>

@endsection