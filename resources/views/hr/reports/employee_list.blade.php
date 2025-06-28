@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
<h3 class="text-themecolor"></h3>
@section('content')
@include('hr.reports.partials.search_form')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">List of Employees</h4>

        <div class="table-responsive m-t-40">
            <table id="employees-table" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        <th>Current Salary</th>
                        <th>Father Name</th>
                        <th>Date of Birth</th>
                        <th>CNIC</th>
                        <th>Date of Joining</th>
                        <th>Division</th>
                        <th>Education</th>
                        <th>PEC No</th>
                        <th>Expiry Date</th>
                        <th>Employee No</th>
                        <th>Mobile</th>
                        <th>LandLine Number</th>
                        <th>Email</th>
                        <th>Emergency Number</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Blood Group</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#employees-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('hr.reports.employee_list') }}",
                data: function(d) {
                    // Add your search form data to the request
                    d.employee_name = $('input[name=employee_name]').val();
                    d.designation = $('select[name=designation]').val();
                    d.department = $('select[name=department]').val();
                    d.education = $('select[name=education]').val();
                    d.employee_no = $('input[name=employee_no]').val();
                    d.status = $('select[name=status]').val();
                }
            },
            columns: [{
                    data: 'sr_no',
                    name: 'sr_no'
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
                    data: 'current_salary',
                    name: 'current_salary'
                },
                {
                    data: 'father_name',
                    name: 'father_name'
                },
                {
                    data: 'date_of_birth',
                    name: 'date_of_birth'
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'joining_date',
                    name: 'joining_date',

                },
                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'education',
                    name: 'education'
                },
                {
                    data: 'pec_no',
                    name: 'pec_no'
                },
                {
                    data: 'expiry_date',
                    name: 'expiry_date'
                },
                {
                    data: 'employee_no',
                    name: 'employee_no'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'landline',
                    name: 'landline'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'emergency_number',
                    name: 'emergency_number'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'blood_group',
                    name: 'blood_group'
                },

            ],
            scrollX: true,
            dom: 'Blfrtip',
            buttons: [
                'copy'
            ],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ]
        });

        // Handle search form submission
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Reset filters
        $('#reset-filters').on('click', function() {
            $('#search-form')[0].reset();
            table.ajax.reload();
        });
    });
</script>
@stop