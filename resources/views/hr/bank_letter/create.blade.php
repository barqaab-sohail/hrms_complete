@extends('layouts.master.master')
@section('title', 'Bank Accounts Letter')
@section('Heading')
<h3 class="text-themecolor">Bank Accounts Letter</h3>
@stop
@section('content')

<div class="card">
    <div class="card-body">
        
            <h2>Generate Bank Opening Letter</h2>
            
            <form id="bankLetterForm" method="POST" action="{{ route('bank-letters.preview') }}">
                @csrf
                
                <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <br>
                    <select class="form-control select2" id="employee_id" name="employee_id" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->employee_no }} - {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="bank_id">Bank</label> <br>
                            <select class="form-control select2" id="bank_id" name="bank_id" required>
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="salary">Salary (PKR)</label>
                            <input type="number" class="form-control" id="salary" name="salary" 
                                   placeholder="Leave empty to use employee's default salary">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Preview Letter</button>
            </form>
            
            <!-- Modal Container -->
            <div id="modalContainer"></div>

            <div id="datatableContainer" class="mt-4" style="display: none;">
                <hr>
                <h2>Bank Letters</h2>
                <p>List of bank letters issued.</p>
            
                <table class="table table-bordered data-table" width=100%>
                    <thead>
                        <tr>
                            <th>Document Name</th>
                        
                            <th>Date</th>
                            <th>View</th>
                            <th>Copy Link</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
        
                    </tbody>
                </table>
            </div>
        
    </div>
</div>

        
<script>

var bankLettersTable;

$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select an option',
        width: '100%',
        allowClear: true
    });
    $('#bankLetterForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#modalContainer').html(response);
                $('#previewModal').modal('show');
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Handle modal close
    $(document).on('hidden.bs.modal', '#previewModal', function() {
        $('#modalContainer').empty();
    });

   
     // Make this function available globally so the modal can call it
     window.updateBankLettersTable = function() {
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
        bankLettersTable = $('.data-table').DataTable({
            processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('bank-letters.list') }}",
            type: "GET",
            data: function(d) {
                d.employee_id = $('#employee_id').val();
            }
        },
        columns: [
            { data: "description", name: 'description' },
            { data: "document_date", name: 'document_date' },
            { data: "document", name: 'document' },
            { data: "copy_link", name: 'copy_link' },
            { data: 'Delete', name: 'Delete', orderable: false, searchable: false },
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
            },
            order: [[1, "desc"]]
        });
    }
    
    // Update project field when employee changes
    $('#employee_id').on('change', function () {
        var employeeId = $(this).val();
        
        if (employeeId) {
            // Show the dataTableContainer when an employee is selected
            $('#datatableContainer').show();
            
            // Destroy existing DataTable if already initialized
            if ($.fn.DataTable.isDataTable('.data-table')) {
                $('.data-table').DataTable().destroy();
            }

            // Re-initialize DataTable with new employeeId
            initializeDataTable(employeeId);
        } else {
            // Hide the dataTableContainer when no employee is selected
            $('#datatableContainer').hide();
        }

        // Handle delete action
        $('body').off('click', '.deleteDocument').on('click', '.deleteDocument', function () {
            var document_id = $(this).data("id");
            var con = confirm("Are you sure you want to delete?");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('documentation.store') }}/" + document_id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        bankLettersTable.ajax.reload(null, false); 
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
});
</script>

        
@stop