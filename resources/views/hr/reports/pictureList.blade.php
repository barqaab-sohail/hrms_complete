@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
<h3 class="text-themecolor">List of Employees</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">List of Employees Picture</h4>

        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Employee No</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        <th>Size of Picture</th>
                        <th class="text-center" style="width:5%">Edit</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr>
                        <td>{{$employee->employee_no??''}}</td>
                        <td>{{$employee->first_name}} {{$employee->last_name}}</td>
                        <td>{{$employee->employeeCurrentDesignation->name??''}}</td>
                        <td>{{round(($employee->picture->size??0 / 1000),2)}}</td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{route('employee.edit',$employee->id)}}" title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
                        </td>


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
            "ordering": false,

            dom: 'Blfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                }, {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
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
