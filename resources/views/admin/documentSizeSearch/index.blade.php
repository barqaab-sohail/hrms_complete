@extends('layouts.master.master')
@section('title', 'Search File Size')
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="color:black">Document Size Analyzer</h4>
        <p class="text-muted">Find the largest files stored in your document tables</p>
        
        <div class="row">
            {{-- Statistics Panel --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-database"></i> Table Statistics</h5>
                    </div>
                    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                        @foreach($tableStats as $tableName => $stats)
                        <div class="mb-3 p-3 border rounded bg-light">
                            <h6 class="font-weight-bold">{{ $stats['human_name'] }}</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Files</small>
                                    <div class="font-weight-bold">{{ number_format($stats['file_count']) }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Size</small>
                                    <div class="font-weight-bold">{{ $stats['total_size'] }}</div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Largest</small>
                                    <div>{{ $stats['max_size'] }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Average</small>
                                    <div>{{ $stats['avg_size'] }}</div>
                                </div>
                            </div>
                            @if(isset($stats['numeric_count']) && $stats['file_count'] > 0)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">Numeric Files</small>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $stats['file_count'] > 0 ? ($stats['numeric_count'] / $stats['file_count']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <small class="ml-2">{{ $stats['numeric_count'] }}/{{ $stats['file_count'] }}</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(isset($stats['warning']))
                            <div class="mt-2">
                                <span class="badge badge-warning">Note</span>
                                <small class="text-warning">{{ $stats['warning'] }}</small>
                            </div>
                            @endif
                            @if(isset($stats['error']))
                            <div class="mt-2">
                                <span class="badge badge-danger">Error</span>
                                <small class="text-danger">{{ $stats['error'] }}</small>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            {{-- Search Panel --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-search"></i> Search Large Files</h5>
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="table_name" class="font-weight-bold">Select Table</label>
                                        <select class="form-control" id="table_name" name="table_name" required>
                                            <option value="">-- Choose Table --</option>
                                            @foreach($tableStats as $tableName => $stats)
                                            <option value="{{ $tableName }}">
                                                {{ $stats['human_name'] }} 
                                                @if($stats['file_count'] > 0)
                                                ({{ $stats['file_count'] }} files, {{ $stats['total_size'] }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="limit" class="font-weight-bold">Number of Files to Show</label>
                                        <select class="form-control" id="limit" name="limit" required>
                                            <option value="10">10 Largest Files</option>
                                            <option value="20">20 Largest Files</option>
                                            <option value="30">30 Largest Files</option>
                                            <option value="50">50 Largest Files</option>
                                            <option value="100">100 Largest Files</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                Only files with numeric size values will be included in the search. 
                                String values are filtered out for accurate sorting.
                            </div>
                            
                            <div class="form-group text-center mt-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search mr-2"></i> Search Large Files
                                </button>
                            </div>
                        </form>
                        
                        <hr>
                        
                        {{-- Bulk Download Controls --}}
                        <div id="bulkDownloadControls" class="d-none mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-download mr-2"></i> Bulk Download</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span id="selectedCount">0</span> files selected
                                            <span class="ml-3 text-muted" id="selectedSize">0 B</span>
                                        </div>
                                        <div>
                                            <button id="selectAllBtn" class="btn btn-sm btn-outline-primary mr-2">
                                                <i class="fas fa-check-square mr-1"></i> Select All
                                            </button>
                                            <button id="deselectAllBtn" class="btn btn-sm btn-outline-secondary mr-2">
                                                <i class="fas fa-square mr-1"></i> Deselect All
                                            </button>
                                            <button id="bulkDownloadBtn" class="btn btn-success btn-sm" disabled>
                                                <i class="fas fa-file-archive mr-1"></i> Download Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Results Section --}}
                        <div id="loadingIndicator" class="text-center d-none">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-3">Searching for large files...</p>
                        </div>
                        
                        <div id="resultsContainer">
                            {{-- Results will be displayed here --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentFiles = [];
    let selectedFiles = new Set();
    let totalSelectedSize = 0;
    
    // Handle form submission
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const tableName = $('#table_name').val();
        
        if (!tableName) {
            alert('Please select a table first.');
            return;
        }
        
        // Reset selection
        selectedFiles.clear();
        totalSelectedSize = 0;
        updateBulkDownloadControls();
        
        // Show loading indicator
        $('#loadingIndicator').removeClass('d-none');
        $('#resultsContainer').html('');
        $('#bulkDownloadControls').addClass('d-none');
        
        // Make AJAX request
        $.ajax({
            url: '{{ route("document-search.search") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#loadingIndicator').addClass('d-none');
                
                if (response.success) {
                    currentFiles = response.data.files;
                    displayResults(response.data);
                } else {
                    $('#resultsContainer').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            ${response.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                $('#loadingIndicator').addClass('d-none');
                
                let errorMessage = 'An error occurred while searching.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                $('#resultsContainer').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        ${errorMessage}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
            }
        });
    });
    
    // Display results
    function displayResults(data) {
        const { files, summary } = data;
        
        let html = '';
        
        // Summary section
        html += `
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Search Summary</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-primary">${summary.total_files_found}</h3>
                            <small class="text-muted">Files Found</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-info">${summary.total_size_found}</h3>
                            <small class="text-muted">Total Size</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-warning">${summary.largest_file}</h3>
                            <small class="text-muted">Largest File</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-success">${summary.table_statistics.total_files_in_table}</h3>
                            <small class="text-muted">Total in Table</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td><strong>Numeric Files in Table:</strong></td>
                                <td class="text-right">${summary.table_statistics.numeric_files_in_table || summary.table_statistics.total_files_in_table}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Table Size:</strong></td>
                                <td class="text-right">${summary.table_statistics.total_size_in_table}</td>
                            </tr>
                            <tr>
                                <td><strong>Average File Size:</strong></td>
                                <td class="text-right">${summary.table_statistics.average_file_size}</td>
                            </tr>
                            <tr>
                                <td><strong>Maximum File Size:</strong></td>
                                <td class="text-right">${summary.table_statistics.maximum_file_size}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                ${summary.table_statistics.total_files_in_table > (summary.table_statistics.numeric_files_in_table || summary.table_statistics.total_files_in_table) ? 
                `<div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Note: ${summary.table_statistics.total_files_in_table - (summary.table_statistics.numeric_files_in_table || 0)} files have non-numeric sizes and were excluded from this search.
                </div>` : ''}
            </div>
        </div>`;
        
        // Files table
        if (files.length > 0) {
            html += `
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Top ${files.length} Largest Files</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                    </th>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>File Name</th>
                                    <th>Document ID</th>
                                    <th class="text-right">Size</th>
                                    <th class="text-center" width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>`;
            
            files.forEach((file, index) => {
                const sizeClass = getSizeClass(file.size_in_bytes);
                
                html += `
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input file-checkbox" 
                            data-id="${file.id}" 
                            data-size="${file.size_in_bytes}"
                            data-index="${index}">
                    </td>
                    <td class="font-weight-bold">${index + 1}</td>
                    <td>
                        <div class="text-truncate" style="max-width: 150px;" title="${file.description || 'No description'}">
                            ${file.description || '<span class="text-muted">N/A</span>'}
                        </div>
                    </td>
                    <td>${file.document_date}</td>
                    <td>
                        <div class="text-truncate" style="max-width: 150px;" title="${file.file_name}">
                            <i class="fas fa-file mr-1 text-muted"></i>
                            ${file.file_name}
                        </div>
                    </td>
                    <td>${file.document_identification_id || '<span class="text-muted">N/A</span>'}</td>
                    <td class="text-right">
                        <span class="badge badge-pill ${sizeClass}" title="${file.size_in_bytes.toLocaleString()} bytes">
                            ${file.size}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="${file.download_url}" 
                            class="btn btn-sm btn-outline-primary download-btn" 
                            title="Download this file"
                            data-id="${file.id}">
                                <i class="fas fa-download mr-1"></i>
                            </a>
                            
                            ${file.edit_url ? `
                            <a href="${file.edit_url}" 
                            class="btn btn-sm btn-outline-warning edit-btn" 
                            title="Edit Document"
                            target="_blank"
                            data-id="${file.id}">
                                <i class="fas fa-edit mr-1"></i>
                            </a>` : ''}
                        </div>
                    </td>
                </tr>`;
            });
            
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>`;
            
            // Show bulk download controls
            $('#bulkDownloadControls').removeClass('d-none');
        } else {
            html += `
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                No files with numeric sizes found in the selected table.
            </div>`;
        }
        
        $('#resultsContainer').html(html);
        
        // Initialize event handlers for checkboxes
        initializeCheckboxHandlers();
    }
    
    // Initialize checkbox event handlers
    function initializeCheckboxHandlers() {
        // Select all checkbox
        $('#selectAllCheckbox').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.file-checkbox').prop('checked', isChecked).trigger('change');
        });
        
        // Individual file checkboxes
        $('.file-checkbox').on('change', function() {
            const fileId = $(this).data('id');
            const fileSize = parseInt($(this).data('size')) || 0;
            const isChecked = $(this).prop('checked');
            
            if (isChecked) {
                selectedFiles.add(fileId);
                totalSelectedSize += fileSize;
            } else {
                selectedFiles.delete(fileId);
                totalSelectedSize -= fileSize;
            }
            
            updateBulkDownloadControls();
            
            // Update select all checkbox state
            const totalCheckboxes = $('.file-checkbox').length;
            const checkedCount = $('.file-checkbox:checked').length;
            $('#selectAllCheckbox').prop('checked', totalCheckboxes === checkedCount);
        });
        
        // Select all button
        $('#selectAllBtn').on('click', function() {
            $('.file-checkbox').prop('checked', true).trigger('change');
        });
        
        // Deselect all button
        $('#deselectAllBtn').on('click', function() {
            $('.file-checkbox').prop('checked', false).trigger('change');
        });
        
        // Bulk download button
        $('#bulkDownloadBtn').on('click', function() {
            if (selectedFiles.size === 0) return;
            
            const tableName = $('#table_name').val();
            const selectedIds = Array.from(selectedFiles);
            
            // Show loading
            $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...').prop('disabled', true);
            
            // Create form for POST request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("document-search.bulk-download") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = $('meta[name="csrf-token"]').attr('content');
            form.appendChild(csrfToken);
            
            // Add table name
            const tableInput = document.createElement('input');
            tableInput.type = 'hidden';
            tableInput.name = 'table';
            tableInput.value = tableName;
            form.appendChild(tableInput);
            
            // Add file IDs
            selectedIds.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });
            
            document.body.appendChild(form);
            form.submit();
            
            // Reset button after a delay
            setTimeout(() => {
                $('#bulkDownloadBtn').html('<i class="fas fa-file-archive mr-1"></i> Download Selected').prop('disabled', false);
            }, 3000);
        });
        
        // Single file download buttons
        $('.download-btn').on('click', function(e) {
            // Let the link work normally for single downloads
            // We'll just track it
            const fileId = $(this).data('id');
            console.log('Downloading file ID:', fileId);
        });
    }
    
    // Update bulk download controls
    function updateBulkDownloadControls() {
        const count = selectedFiles.size;
        const formattedSize = formatBytes(totalSelectedSize);
        
        $('#selectedCount').text(count);
        $('#selectedSize').text(formattedSize);
        
        if (count > 0) {
            $('#bulkDownloadBtn').prop('disabled', false);
            $('#bulkDownloadBtn').html(`<i class="fas fa-file-archive mr-1"></i> Download ${count} Files (${formattedSize})`);
        } else {
            $('#bulkDownloadBtn').prop('disabled', true);
            $('#bulkDownloadBtn').html('<i class="fas fa-file-archive mr-1"></i> Download Selected');
        }
    }
    
    // Determine size badge class
    function getSizeClass(sizeInBytes) {
        if (sizeInBytes > 104857600) { // > 100MB
            return 'badge-danger';
        } else if (sizeInBytes > 52428800) { // > 50MB
            return 'badge-warning';
        } else if (sizeInBytes > 10485760) { // > 10MB
            return 'badge-info';
        } else if (sizeInBytes > 1048576) { // > 1MB
            return 'badge-primary';
        } else {
            return 'badge-secondary';
        }
    }
    
    // Format bytes to human readable format
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
});
</script>

<style>
.card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 8px 8px 0 0 !important;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.badge-pill {
    padding: 0.4em 0.8em;
    font-size: 0.85em;
    cursor: default;
}

.badge-danger { background-color: #dc3545; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-info { background-color: #17a2b8; }
.badge-primary { background-color: #007bff; }
.badge-secondary { background-color: #6c757d; }

.progress {
    border-radius: 4px;
}

.progress-bar {
    border-radius: 4px;
}

.form-check-input {
    cursor: pointer;
}

.download-btn {
    min-width: 100px;
}
/* Add to the existing style section */
.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.btn-group .btn-sm {
    font-size: 0.875rem;
}

.edit-btn:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-outline-warning {
    border-color: #ffc107;
    color: #ffc107;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    color: #212529;
}
</style>
@endpush