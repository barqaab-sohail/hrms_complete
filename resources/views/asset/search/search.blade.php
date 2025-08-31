@extends('layouts.master.master')
@section('title', 'Search Asset')
@section('Heading')
<h3 class="text-themecolor">Search Asset</h3>
@stop
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Search Asset <button type="button" id="resetForm" class="btn btn-danger float-right">Reset Search</button></h4>
        <hr>
        <form id="assetSearch" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12 required">
                                <label class="control-label text-right">Name of Office</label><br>
                                <select name="office_id" id="office_id" class="form-control searchSelect">
                                    <option value=""></option>
                                    @foreach($offices as $office)
                                    <option value="{{$office->id}}" {{(old("office_id")==$office->id? "selected" : "")}}>{{$office->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12 required">
                                <label class="control-label text-right">Class Name</label><br>
                                <select name="class_id" id="class_id" class="form-control searchSelect">
                                    <option value=""></option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{(old("class_id")==$class->id? "selected" : "")}}>{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Sub Classes<span class="text_requried">*</span></label>
                                <select name="as_sub_class_id" id="as_sub_class_id" class="form-control selectTwo" data-placeholder="First Select Class">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12 required">
                                <label class="control-label text-right">Name Employee</label><br>
                                <select name="hr_employee_id" id="hr_employee_id" class="form-control searchSelect">
                                    <option value=""></option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->employee_no}}-{{$employee->full_name}} - {{$employee->employeeCurrentDesignation->name??''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group row">
                            <div class="col-md-12 required">
                                <label class="control-label text-right">Asset Owner</label><br>
                                <select name="client_id" id="client_id" class="form-control searchSelect">
                                    <option value=""></option>
                                    @foreach($owners as $owner)
                                    <option value="{{$owner->id}}" {{(old("client_id")==$owner->id? "selected" : "")}}>{{$owner->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive m-t-40">
                    <table id="assetDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0" style="color:black;">
                        <thead>
                            <tr>
                                <th>Asset Id</th>
                                <th>Description</th>
                                <th>Ownership</th>
                                <th>Purchase Condition</th>
                                <th>Purchase Date</th>
                                <th>Purchase Cost</th>
                                <th>Allocation/Location</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="progressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="progressModalLabel">Generating PDF</h5>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Generating PDF...</span>
                </div>
                <p class="mt-3">Please wait while we prepare your PDF export.</p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize select2
        $('select').select2();
        
        // Add this reset function
        $('#resetForm').click(function() {
            // Reset all select2 dropdowns
            $('.searchSelect').val('').trigger('change');
            $('#as_sub_class_id').val('').trigger('change');
            
            // Clear any error messages
            $('#json_message').html('');
            
            // Remove any error highlighting
            $('.form-group').removeClass('has-error');
            
            // Reload datatable with empty criteria
            assetTable.ajax.reload();
        });
        
        // Mutual exclusion between office and employee selection
        $('#office_id').change(function() {
            if ($(this).val()) {
                $('#hr_employee_id').val('').trigger('change');
            }
            assetTable.ajax.reload();
        });
        
        $('#hr_employee_id').change(function() {
            if ($(this).val()) {
                $('#office_id').val('').trigger('change');
            }
            assetTable.ajax.reload();
        });
        
        //Add Sub_Class
        $('#class_id').change(function() {
            var cid = $(this).val();
            if (cid) {
                $.ajax({
                    type: "get",
                    url: "{{url('hrms/asset/sub_classes')}}" + "/" + cid,
                    success: function(res) {
                        if (res) {
                            $("#as_sub_class_id").empty();
                            $("#as_sub_class_id").append('<option value="">Select Sub Classes</option>');
                            $.each(res, function(key, value) {
                                $("#as_sub_class_id").append('<option value="' + key + '">' + value + '</option>');
                            });
                            $('#as_sub_class_id').select2('destroy');
                            $('#as_sub_class_id').select2({
                                width: "100%",
                                theme: "classic"
                            });
                        }
                    }
                });
            } else {
                $("#as_sub_class_id").empty();
            }
            assetTable.ajax.reload();
        });
        
        // Other dropdown changes
        $('#as_sub_class_id, #client_id').change(function() {
            assetTable.ajax.reload();
        });
        
        // Initialize DataTable with smaller page length for faster loading
        var assetTable = $('#assetDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('asset.result') }}",
                data: function(d) {
                    d.office_id = $('#office_id').val();
                    d.class_id = $('#class_id').val();
                    d.as_sub_class_id = $('#as_sub_class_id').val();
                    d.hr_employee_id = $('#hr_employee_id').val();
                    d.client_id = $('#client_id').val();
                }
            },
            columns: [
                { data: 'asset_code', name: 'asset_code' },
                { 
                    data: 'description', 
                    name: 'description',
                    render: function(data, type, row) {
                        return '<a href="/asset/' + row.id + '/edit" style="color:black;">' + data + '</a>';
                    }
                },
                { data: 'ownership', name: 'ownership' },
                { data: 'purchase_condition', name: 'purchase_condition' },
                { data: 'purchase_date', name: 'purchase_date' },
                { data: 'purchase_cost', name: 'purchase_cost' },
                { data: 'allocation_location', name: 'allocation_location' },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            pageLength: 10, // Default to smaller page size for faster loading
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // Exclude image column for copy
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // Exclude image column for Excel
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    className: 'btn-primary',
                    action: function(e, dt, node, config) {
                        $('#progressModal').modal('show');
                        
                        setTimeout(function() {
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                            $('#progressModal').modal('hide');
                        }, 2000);
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7] // Include image column for PDF
                    },
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        doc.pageOrientation = 'landscape';
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.pageMargins = [20, 30, 20, 30];
                        
                        // Process images for PDF
                        var images = document.querySelectorAll('.export-image');
                        
                        // Loop through table rows and replace image HTML with actual images
                        for (var i = 1; i < doc.content[1].table.body.length; i++) {
                            if (images[i-1] && images[i-1].getAttribute('data-base64')) {
                                doc.content[1].table.body[i][7] = {
                                    image: images[i-1].getAttribute('data-base64'),
                                    width: 30,
                                    height: 30,
                                    alignment: 'center'
                                };
                            } else {
                                doc.content[1].table.body[i][7] = {text: 'No Image', style: 'imagePlaceholder'};
                            }
                        }
                        
                        // Add style definition for image placeholders
                        doc.styles.imagePlaceholder = {
                            fontSize: 7,
                            alignment: 'center',
                            color: '#999999'
                        };
                        
                        return doc;
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // Exclude image column for CSV
                    }
                }
            ],
            scrollY: "400px",
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 2
            },
            initComplete: function() {
                // Initialize lazy loading after table is loaded
                initLazyLoading();
            },
            drawCallback: function() {
                // Reinitialize lazy loading after each table redraw
                initLazyLoading();
            }
        });
        
        // Lazy loading implementation
        function initLazyLoading() {
            const lazyImages = document.querySelectorAll('.lazy-load');
            
            if ('IntersectionObserver' in window) {
                // Modern browsers with Intersection Observer support
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy-load');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                lazyImages.forEach(img => {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback for older browsers
                lazyImages.forEach(img => {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-load');
                });
            }
        }
        
        // Function to view from list table
        $(document).on('click', '.ViewIMG', function() {
            $(this).EZView();
        });
    });
</script>

<style>
    .spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }
    
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
    
    .modal-header {
        border-bottom: none;
    }
    
    .modal-content {
        border-radius: 0.7rem;
    }
    
    /* Smooth transition for lazy loaded images */
    .lazy-load {
        transition: opacity 0.3s;
        opacity: 0.7;
    }
    
    .lazy-load.loaded {
        opacity: 1;
    }
</style>

@stop