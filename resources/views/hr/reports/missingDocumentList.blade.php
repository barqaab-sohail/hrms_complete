@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Missing Document List</h4>

        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <th class="text-left bg-primary text-white">Employee ID</th>
                        <th class="text-left bg-primary text-white">Employee Name</th>
                        <th class="text-left bg-primary text-white">Designation</th>
                        <th class="text-left bg-primary text-white">Division</th>
                        <th class="text-left bg-primary text-white">Project</th>
                        <th class="text-left bg-primary text-white">CNIC Front</th>
                        <th class="text-left bg-primary text-white">Picture</th>
                        <th class="text-left bg-primary text-white">Signed Appointment</th>
                        <th class="text-left bg-primary text-white">Appointment Letter</th>
                        <th class="text-left bg-primary text-white">HR Form</th>
                        <th class="text-left bg-primary text-white">Joining Report</th>
                        <th class="text-left bg-primary text-white">Engineering Degree</th>
                        <th class="text-left bg-primary text-white">Educational Documents</th>
                        <th class="text-left bg-primary text-white">Mobile</th>

                        <th style="width:5%">Edit</th>
                        <th style="width:5%">Delete</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr>
                        <td class="text-left">{{$employee->employee_no}}</td>
                        <td class="text-left">{{$employee->first_name}} {{$employee->last_name}}</td>
                        <td class="text-left">{{$employee->designation}}</td>
                        <td class="text-left">{{$employee->employeeCurrentDepartment->name??''}}</td>
                        <td class="text-left">{{$employee->employeeProject->last()->name??''}}</td>
                        <td class="text-left">{{$employee->cnicFront->first()?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->picture?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->signedAppointmentLetter?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->appointmentLetter->first()?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->hrForm->first()?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->joiningReport->first()?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->engineeringDegree->first()?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->educationalDocuments->first()?'Avaiable':'Missing'}}</td>
                        <td class="text-left">{{$employee->hrContactMobile->mobile??''}}</td>


                        <td>
                            <a class="btn btn-success btn-sm" href="{{route('employee.edit',$employee->id)}}"
                                title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
                        </td>
                        <td>
                            @role('Super Admin')
                            <form id="formDeleteContact{{$employee->id}}"
                                action="{{route('employee.destroy',$employee->id)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you Sure to Delete')" href=data-toggle="tooltip"
                                    data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                            </form>
                            @endrole
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


        dom: 'Blfrtip',
        buttons: [{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }, {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
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