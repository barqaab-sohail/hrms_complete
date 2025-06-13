@extends('layouts.master.master')
@section('title', 'Missing Documents')
@section('Missing Documents')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Complete Missing Document List</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="missing-documents-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Contact Number</th>
                                    <th>Division</th>
                                    <th>Project/Office</th>
                                    <th>Missing Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
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
        $('#missing-documents-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('hrReports.newmissingdocuments') }}",
                type: "GET"
            },
            columns: [{
                    data: 'employee_no',
                    name: 'employee_no'
                },
                {
                    data: 'employee_name',
                    name: 'employee_name'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'contact_number',
                    name: 'contact_number'
                },
                {
                    data: 'division',
                    name: 'division'
                },
                {
                    data: 'project',
                    name: 'project'
                },
                {
                    data: 'missing_documents',
                    name: 'missing_documents',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            // Handle both array and string cases
                            if (Array.isArray(data)) {
                                return data.join(', ');
                            }
                            return data || 'N/A';
                        }
                        return data;
                    }
                }
            ],
            responsive: true,
            dom: 'frtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            pageLength: 25,
            order: [
                [1, 'asc']
            ]
        });
    });
</script>
@endpush