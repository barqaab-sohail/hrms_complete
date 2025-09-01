@extends('layouts.master.master')
@section('title', 'Experience Letter')
@section('Heading')
<h3 class="text-themecolor">Experience Letter</h3>
@stop
@section('content')
<style>
    /* Add this in your head section or in a style tag */
    .max-width-column {
        width: 60% !important; /* Adjust this percentage as needed */
        min-width: 300px; /* Set a minimum width if desired */
    }
    .min-width-column {
        width: 1% !important;
        white-space: nowrap;
    }
</style>
<div class="card">
    <div class="card-body">
        <h2>Generate Experience Letter</h2>
        
        <form id="experienceLetterForm" method="POST" action="{{ route('experience-letters.preview') }}">
            @csrf
            
            <div class="form-group">
                <label for="employee_id">Employee</label> <br>
                <select class="form-control select2" id="employee_id" name="employee_id" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                            data-status="{{ $employee->hr_status_id }}"
                            data-project="{{ $employee->employeeCurrentProject->name ?? '' }}"
                            >
                            {{ $employee->employee_no }} - {{ $employee->full_name }} - {{ $employee->employeeCurrentDesignation?->name }}
                            ({{ $employee->hr_status_id }})
                        </option>
                    @endforeach
                </select>
                <div id="employee_id_error" class="invalid-feedback"></div>
            </div>
            
            <div class="form-group">
                <label for="project">Project</label>
                <input type="text" class="form-control" id="project" name="project" 
                       placeholder="Enter project name or leave empty to use employee's default">
                <small class="form-text text-muted">
                    Leave blank to use employee's current project
                </small>
                <div id="project_error" class="invalid-feedback"></div>
            </div>
            <div class="row">
                <div class="form-group col-4">
                    <label for="project">Letter Date</label>
                    <input type="text" class="form-control date-picker" id="letter_date" name="letter_date" 
                        placeholder="Enter letter date or leave empty current date">
                    <small class="form-text text-muted">
                        Leave blank to use letter current date
                    </small>
                    <div id="project_error" class="invalid-feedback"></div>
                </div>
                <div class="form-group col-4">
                    <label for="project">Joining Date</label>
                    <input type="text" class="form-control date-picker" id="joining_date" name="joining_date" 
                        placeholder="Enter joining date or leave empty to use employee's actual joining date">
                    <small class="form-text text-muted">
                        Leave blank to use employee's actual joining date
                    </small>
                    <div id="project_error" class="invalid-feedback"></div>
                </div>
                <div class="form-group col-4">
                    <label for="project">Leaving Date</label>
                    <input type="text" class="form-control date-picker" id="leaving_date" name="leaving_date" 
                        placeholder="Enter leaving date or leave empty to use employee's actual leaving date">
                    <small class="form-text text-muted">
                        Leave blank to use employee's actual leaving date
                    </small>
                    <div id="project_error" class="invalid-feedback"></div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Preview Letter</button>
        </form>
        
        <!-- Error Alert -->
        <div id="errorAlert" class="alert alert-danger mt-3" style="display: none;"></div>
        
        <!-- Modal Container -->
        <div id="modalContainer"></div>
        
        <!-- Table Container (Hidden Initially) -->
        <div id="tableContainer" class="mt-4" style="display: none;">
            <hr>
            <h2>List of BARQAAB Experience Letters already Issued</h2>
            <table class="table table-bordered data-table" width="100%">
                <thead>
                    <tr>
                        <th>Document Name</th>
                        <th>Date</th>
                        <th>View</th>
                        <th>Copy Link</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    var experienceLettersTable;
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select an option',
        width: '100%',
        allowClear: true
    });

    // Initialize date picker with custom format
    $('.date-picker').datepicker({
        format: 'MM d, yyyy',
        autoclose: true,
        todayHighlight: true
    });

    // Make this function available globally so the modal can call it
    window.updateExperienceLettersTable = function() {
        var employeeId = $('#employee_id').val();
        if ($.fn.DataTable.isDataTable('.data-table')) {
            $('.data-table').DataTable().ajax.reload(null, false); // false means don't reset paging
        } else {
            // Re-initialize the table if it doesn't exist
            initializeDataTable(employeeId);
        }
    };
    
    // Extract table initialization into a separate function
    function initializeDataTable(employeeId) {
        experienceLettersTable = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('experience-letters.list') }}",
                type: "GET",
                data: function(d) {
                    d.employee_id = $('#employee_id').val();
                }
            },
            columns: [
                { data: "description", name: 'description', className: "max-width-column"  },
                { data: "document_date", name: 'document_date', className: "min-width-column" },
                { data: "document", name: 'document', className: "min-width-column" },
                { data: "copy_link", name: 'copy_link', className: "min-width-column" },
                { data: 'Delete', name: 'Delete', orderable: false, searchable: false, className: "min-width-column" },
            ],
            drawCallback: function (settings) {
                if (this.api().rows().data().length > 0) {
                    $("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
                }

                $('.copyLink').click(function () {
                    var text = $(this).attr('link').replace(" ", "%20");
                    navigator.clipboard.writeText(text);
                    alert('Link Copied');
                });

                $('.copyLink').hover(function () {
                    $(this).css('cursor', 'pointer').attr('title', 'Click for Copy Link');
                }, function () {
                    $(this).css('cursor', 'auto');
                });

                // Check time for delete buttons
                $('.deleteDocument').each(function() {
                    var createdAt = new Date($(this).data('created'));
                    var oneHourAgo = new Date();
                    oneHourAgo.setHours(oneHourAgo.getHours() - 1);
                    
                    if (createdAt < oneHourAgo) {
                        $(this).prop('disabled', true);
                        $(this).removeClass('deleteDocument');
                        $(this).html('Delete');
                        $(this).css('cursor', 'not-allowed');
                        $(this).attr('title', 'Cannot delete after 1 hour');
                    }
                });
            },
            order: [[1, "desc"]]
        });
    }
    
    // Update project field when employee changes
    $('#employee_id').on('change', function () {
        $('#project').val('').trigger('change'); // Clear project field
        $('#letter_date').val(''); // Clear letter date field
        $('#joining_date').val(''); // Clear joining date field
        $('#leaving_date').val(''); // Clear leaving date field
        var selectedOption = $(this).find('option:selected');
        var employeeId = selectedOption.val();
        console.log("Selected Employee ID:", employeeId);

        $('#project').val(selectedOption.data('project'));

        if (employeeId) {
            // Show the table container when an employee is selected
            $('#tableContainer').show();
            
            // Destroy existing DataTable if already initialized
            if ($.fn.DataTable.isDataTable('.data-table')) {
                $('.data-table').DataTable().destroy();
            }

            // Re-initialize DataTable with new employeeId
            initializeDataTable(employeeId);
        } else {
            // Hide the table container when no employee is selected
            $('#tableContainer').hide();
        }

        // Handle delete action
        $('body').off('click', '.deleteDocument').on('click', '.deleteDocument', function () {
            var document_id = $(this).data("id");
            var createdAt = new Date($(this).data('created'));
            var oneHourAgo = new Date();
            oneHourAgo.setHours(oneHourAgo.getHours() - 1);
            
            if (createdAt < oneHourAgo) {
                alert('Cannot delete after 1 hour');
                return false;
            }

            var con = confirm("Are you sure you want to delete?");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('documentation.store') }}/" + document_id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        experienceLettersTable.ajax.reload(null, false); 
                        clearMessage();
                        if ($('#formDocument:visible').length != 0) {
                            $('#formDocument').toggle();
                        }

                        if (data.status == "Not OK") {
                            $('#json_message').html('<div class="alert alert-danger"><strong>' + data.message + '</strong></div>');
                        } else {
                            $('#json_message').html('<div class="alert alert-success"><strong>' + data.message + '</strong></div>');
                        }

                        if (data.error) {
                            $('#json_message').html('<div class="alert alert-danger"><strong>' + data.error + '</strong></div>');
                        }
                    },
                    error: function (data) {
                        console.log("Delete error", data);
                    }
                });
            }
        });
    });

    $('#experienceLetterForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#errorAlert').hide();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#modalContainer').html(response);
                $('#previewModal').modal('show');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + '_error').text(value[0]);
                    });
                } else {
                    // Other errors
                    var errorMessage = xhr.responseJSON.message || 'An error occurred. Please try again.';
                    $('#errorAlert').text(errorMessage).show();
                }
            }
        });
    });
    
    $(document).on('hidden.bs.modal', '#previewModal', function() {
        $('#modalContainer').empty();
    });
});
</script>
@stop