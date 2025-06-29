@extends('layouts.master.master')
@section('title', 'List of Employees')
@section('Heading')
<h3 class="text-themecolor">List of Employees</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="color:black">List of Employees</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Designation/Position</th>
                        <th>Project/Office</th>
                        <th>Date of Birth</th>
                        <th>Status</th>
                        <th>Last W/D</th>
                        <th>CNIC</th>
                        <th>Date of Joining</th>
                        <th>Age</th>
                        <th>Salary</th>
                        <th>Effective Salary</th>
                        <th class="text-center" style="width:5%">Expiry</th>
                        <th>Mobile</th>
                        @role('Super Admin')
                        <th class="text-center" style="width:5%">Delete</th>
                        @endrole
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>



<script>
    $(document).ready(function() {
        // $('#myTable').DataTable().ajax.reload();
      
    $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            dom: 'Blfrtip',
            lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
            buttons: [
                'copy', 'excel', 'pdf',{
                text: 'Refresh',
                    action: function ( e, dt, node, config ) {
                        //dt.ajax.reload();
                    dt.ajax.url("{{ route('employees.refresh') }}").load();
                    }
                }
            ],
            "aaSorting": [],
         
            ajax: {
                url: "{{ route('employee.loadData') }}",
            },
            columns: [{
                    data: 'employee_no',
                    name: 'employee_no'
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'project',
                    name: 'project'
                },
                {
                    data: 'date_of_birth',
                    name: 'date_of_birth'
                },
                {
                    data: 'hr_status_id',
                    name: 'status',
                    orderable: true
                },
                {
                    data: 'last_working_date',
                    name: 'last_working_date',
                    orderable: true
                },
                {
                    data: 'cnic',
                    name: 'cnic'
                },
                {
                    data: 'joining_date',
                    name: 'joining_date'
                },
                {
                    data: 'age',
                    name: 'age'
                },
                {
                    data: 'salary',
                    name: 'salary'
                },
                {
                    data: 'effective_date',
                    name: 'effective_date'
                },
                {
                    data: 'expiry_date',
                    name: 'expiry_date'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                @role('Super Admin') 
                {
                    data: 'delete',
                    name: 'delete',
                    orderable: false,
                    searchable: false
                }
                @endrole
            ]
        });



    });
</script>

@stop