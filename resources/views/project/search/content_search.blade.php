@extends('layouts.master.master')
@section('title', 'Search Project Documents with Content')
@section('Heading')
<h3 class="text-themecolor">Search Project Documents with Content</h3>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-md-6">
            <!-- Search Filters Panel -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Search Filters</h5>
                </div>
                <div class="card-body">
                    <form id="searchForm" action="{{ route('documents.index') }}" method="GET">
                        <!-- Basic Search -->
                        <div class="form-group mb-3">
                            <label for="searchTerm" class="form-label">Search Term</label>
                            <input type="text" class="form-control" id="searchTerm" name="q"
                                value="{{ request('q') }}" placeholder="Enter search term...">
                        </div>

                        <!-- Advanced Filters (initially hidden) -->
                        <div id="advancedFilters" style="display: none;">
                            <div class="form-group mb-3">
                                <label for="reference_no" class="form-label">Reference No.</label>
                                <input type="text" class="form-control" id="reference_no" name="reference_no"
                                    value="{{ request('reference_no') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    value="{{ request('description') }}">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date_from" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="date_to" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="form-group mb-6">
                                <label for="project_name" class="form-label">Project Name</label>
                                <select class="form-select select2" id="project_name" name="project_name">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" 
                                            {{ request('project_name') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="pr_folder_name_id" class="form-label">Folder</label>
                                <select class="form-select" id="pr_folder_name_id" name="pr_folder_name_id">
                                    <option value="">All Folders</option>
                                    @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}"
                                        {{ request('pr_folder_name_id') == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                            <button type="button" id="toggleAdvanced" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-cog me-1"></i> Advanced Filters
                            </button>
                            <a href="{{ route('documents.index') }}" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        

        <div class="col-md-12">
            <!-- Search Results -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Search Results</h5>
                    <div class="text-muted small">
                        Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} results
                    </div>
                </div>

                <div class="card-body">
                    @if($documents->isEmpty())
                    <div class="alert alert-info">No documents found matching your search criteria.</div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Reference No.</th>
                                    <th>Project</th>
                                    <th>Document</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Size</th>
                                    <th>Folder</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($documents as $document)
<tr>
    <td>{{ $document->reference_no }}</td>
    <td>{{ Str::limit($document->prDetail->name, 50) }}</td>
    <td>
    <a href="{{ $document->full_path }}" target="_blank" class="text-primary" title="{{ $document->file_name }}">
        <i class="fas fa-file-{{ $document->extension == 'pdf' ? 'pdf' : 'word' }}"></i>
    </a>
</td>
    </td>
    <td>{{ Str::limit($document->description, 50) }}</td>
    <td>{{ $document->document_date }}</td>
    <td>{{ $document->size }} MB</td>
    <td>
        <span class="badge bg-secondary text-white">
            @isset($document->prFolderName)
                {{ $document->prFolderName->name }}
            @else
                N/A
            @endisset
        </span>
    </td>
</tr>
@endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
    {{ $documents->withQueryString()->links('pagination::bootstrap-4') }}
</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Preview Modal -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentModalLabel">Document Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="border rounded p-3 bg-light" id="modalContent">
                    <!-- Content will be inserted here by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

$(document).ready(function() {
        $('.select2').select2({
        width: '100%',  // Make it full width of its container
        dropdownAutoWidth: true, // Auto-adjust dropdown width
        placeholder: "Select a project", // Optional placeholder
    });
    });
    document.addEventListener('DOMContentLoaded', function() {

        
    // Toggle advanced filters
    document.getElementById('toggleAdvanced').addEventListener('click', function() {
    const filters = document.getElementById('advancedFilters');
    filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
    this.innerHTML = filters.style.display === 'none' ? 
        '<i class="fas fa-cog me-1"></i> Advanced Filters' : 
        '<i class="fas fa-times me-1"></i> Hide Filters';
});

    // Handle content preview modal
    document.querySelectorAll('.view-content').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.getAttribute('data-content');
            document.getElementById('modalContent').innerHTML = content ?
                `<pre>${content}</pre>` :
                '<div class="text-muted">No content available</div>';
        });
    });

    // Highlight search terms in results
    @if(request('q'))
    const searchTerm = '{{ request('q') }}';
    const regex = new RegExp(searchTerm, 'gi');
    const elements = document.querySelectorAll('td');

    elements.forEach(el => {
        const html = el.innerHTML.replace(
            regex,
            match => `<span class="bg-warning">${match}</span>`
        );
        el.innerHTML = html;
    });
    @endif

    // Add this to your existing JavaScript
    @if(request()->has('project_name'))
    document.getElementById('project_name').value = '{{ request('project_name') }}';
    @endif
});
</script>
@endpush

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .bg-warning {
        padding: 0 2px;
        border-radius: 3px;
    }

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: inherit;
        margin: 0;
    }

     /* Pagination styling */
     .pagination {
        --bs-pagination-padding-x: 0.75rem;
        --bs-pagination-padding-y: 0.375rem;
        --bs-pagination-font-size: 0.875rem;
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0.875rem;
    }
</style>
@endpush