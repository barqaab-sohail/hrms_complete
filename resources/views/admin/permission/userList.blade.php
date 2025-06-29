@extends('layouts.master.master')
@section('title', 'User List')
@section('Heading')
<h3 class="text-themecolor">User List</h3>
@stop
@section('content')


<div class="row">
    <div class="col-lg-12">

        <div class="card card-outline-info">

            <div class="d-flex justify-content-end" style="margin-top:20px; margin-right:20px;">
                <button type="button" id="hideDeleteButton" class="btn btn-danger float-right" style="margin-right:100px;">Delete All Permissions</button>
                <button type="button" id="hideButton" class="btn btn-success float-right">Add Permission</button>
            </div>
            <div class="row">

                <div class="card-body" style="margin-left:20px; margin-right:20px;">
                    <form method="post" action="{{route('permission.add')}}" class="form-horizontal form-prevent-multiple-submits" id="formPermission" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-body">
                            <h3 class="box-title" id="formHeading">Add User Permission</h3>
                            <hr class="m-t-0 m-b-40">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row">

                                        <div class="col-md-12">
                                            <label class="control-label text-right">Employee Name<span class="text_requried">*</span></label>
                                            <select name="hr_employee_id" class="form-control selectTwo" data-validation="required">
                                                <option value=""></option>
                                                @foreach($employees as $employee)
                                                <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}}, {{$employee->employeeCurrentDesignation?->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Permission</label><br>
                                            <select id="permission_name" name="permission_name" class="form-control selectTwo" data-validation="required">
                                                <option value=""></option>
                                                @foreach($permissions as $permission)
                                                <option value="{{$permission->name}}" {{(old("permission_name")==$permission->name? "selected" : "")}}>{{$permission->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <!--/span-->

                            </div><!--/End Row-->
                        </div> <!--/End Form Boday-->

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Permission</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <hr>
                    <!-- Delete Permission Form -->
                    <form method="post" action="{{route('permission.userAllPermissionDelete')}}" class="form-horizontal form-prevent-multiple-submits" id="formDeleteAllPermission" enctype="multipart/form-data">
                        {{csrf_field()}}
                        @method('DELETE')
                        <div class="form-body">
                            <h3 class="box-title" id="formHeading">Delete All Permissions</h3>
                            <hr class="m-t-0 m-b-40">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row">

                                        <div class="col-md-12">
                                            <label class="control-label text-right">Employee Name<span class="text_requried">*</span></label>
                                            <select name="hr_employee_id" class="form-control selectTwo" data-validation="required">
                                                <option value=""></option>
                                                @foreach($employees as $employee)
                                                <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}}, {{$employee->employeeCurrentDesignation?->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div><!--/End Row-->
                        </div> <!--/End Form Boday-->

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-danger btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Delete All Permission</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <hr>
                    <h2 class="card-title">List of Users With Permissions</h2>

                    <div class="table-responsive m-t-40">

                        <table id="myTable1" class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="20%">User Name</th>
                                    <th width="10%">Designation</th>
                                    <th width="70%">Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($distintModelPermission as $modelHasPermission)
                                <tr>
                                    <td width="20%">{{$modelHasPermission->user?->hrEmployee->first_name}} {{$modelHasPermission->user?->hrEmployee->last_name}}</td>
                                    <td width="10%">{{$modelHasPermission->user?->hrEmployee->designation}}</td>
                                    <td width="70%">{{implode(", ",$modelHasPermission->user->getPermissionNames()->toArray())}}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h2 class="card-title">List of Users</h2>

                    <div class="table-responsive m-t-40">

                        <table id="myTable2" class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="30%">User Name</th>
                                    <th width="15%">Designation</th>
                                    <th width="50%">Permission Name</th>
                                    <th width="5%">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modelsHasPermissions as $modelHasPermission)
                                <tr>
                                    <td width="30%">{{$modelHasPermission->user?->hrEmployee->first_name}} {{$modelHasPermission->user?->hrEmployee->last_name}}</td>
                                    <td width="15%">{{$modelHasPermission->user?->hrEmployee->designation}}</td>
                                    <td width="50%">{{$modelHasPermission->permission->name}}</td>
                                    <td width="5%">
                                        <form action="{{route('userPermission.destroy',[$modelHasPermission->permission->name, $modelHasPermission->model_id])}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href=data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {


        formFunctions(false);
        $('#formPermission').hide();
        $('#hideButton').click(function() {
            $('#formPermission').trigger("reset");
            $('#formPermission').toggle();
        });


        $('#formDeleteAllPermission').hide();
        $('#hideDeleteButton').click(function() {
            $('#formDeleteAllPermission').trigger("reset");
            $('#formDeleteAllPermission').toggle();
        });







        $('#myTable1').DataTable({
            stateSave: false,
            "order": [
                [1, "asc"]
            ],
            "columnDefs": [{
                    "width": "70%",
                    "targets": 0,
                },
                {
                    "targets": [-1, -2],
                    "className": "dt-center"
                }

            ],

            dom: 'Blfrtip',
            buttons: [

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
            ]
        });

        $('#myTable2').DataTable({
            stateSave: false,
            "order": [
                [1, "asc"]
            ],
            "columnDefs": [{
                    "width": "70%",
                    "targets": 0,
                },
                {
                    "targets": [-1, -2],
                    "className": "dt-center"
                }

            ],

            dom: 'Blfrtip',
            buttons: [

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
            ]
        });
    });
</script>

@stop