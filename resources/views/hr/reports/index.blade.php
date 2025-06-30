@extends('layouts.master.master')
@section('title', 'List of HR Reports')
@section('Heading')
<h3 class="text-themecolor">List of HR Reports</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">List of HR Reports</h4>
        
        @can('manage_hr_reports')
        <div class="mb-3">
            <a href="{{ route('hr.reports.create') }}" class="btn btn-primary">Add New Report</a>
        </div>
        @endcan
    
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Name of Report</th>
                        <th class="text-center" style="width:5%">Status</th>
                        @can('manage_hr_reports')
                        <th class="text-center" style="width:10%">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route($report->route) }}" style="color:black">
                                {{ $report->name }}
                            </a>
                            @if($report->description)
                            <br><small class="text-muted">{{ $report->description }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($report->is_active)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        @can('manage_hr_reports')
                        <td class="text-center">
                            <a href="{{ route('hr.reports.edit', $report->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('hr.reports.destroy', $report->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {



        $('#myTable').DataTable({
            stateSave: false,

            dom: 'Blfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1]
                    }
                }, {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
            ],
            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 2
            }
        });

    });
</script>

@stop