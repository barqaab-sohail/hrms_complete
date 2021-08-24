@if($result->count()!=0)

<hr>         
        <div class="card">
        <div class="card-body">
            <!--<div class="float-right">
                <input id="month" class="form-control" value="" type="month">
            </div>-->
            
            <div class="table-responsive m-t-40">
            
            <table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Employee Id</th>
                    <th>Employee Name</th>
                    <th>Designation/Position</th>
                    <th>Date of Birth</th>
                    <th>Status</th>
                    <th>CNIC</th>
                    <th>Date of Joining</th>
                    <th>Mobile</th>
                    
                    <th class="text-center"style="width:5%">Edit</th> 
                    <th class="text-center"style="width:5%">Delete</th>
                
                </tr>
                </thead>
                <tbody>
                    @foreach($result as $employee)
                        <tr>
                            <td>{{$employee->employee_no??''}}</td>
                            <td>{{$employee->first_name}} {{$employee->last_name}}</td>
                            <td>{{$employee->employeeDesignation->last()->name??''}}</td>
                            <td>{{$employee->formatted_date_of_birth}}</td>
                            <td>{{$employee->hr_status_id}}</td>
                            <td>{{$employee->cnic}}</td>
                            <td>{{isset($employee->employeeAppointment->joining_date)?date('M d, Y', strtotime($employee->employeeAppointment->joining_date)):''}}</td>
                            <td>{{$employee->hrContactMobile->mobile??''}}</td>
                            
                            
                            <td class="text-center">
                                <a class="btn btn-info btn-sm" href="{{route('employee.edit',$employee->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
                            </td>
                            <td class="text-center">
                                 @role('Super Admin')
                                 <form  id="formDeleteContact{{$employee->id}}" action="{{route('employee.destroy',$employee->id)}}" method="POST">
                                 @method('DELETE')
                                 @csrf
                                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
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
    <hr>  

<script>
$(document).ready(function() {

    $('#myDataTable').DataTable({
                stateSave: false,        
                dom: 'Blfrtip',
                columnDefs: [ { type: 'date', 'targets': [5] } ],
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7]
                        }
                    },
                ],
               
                scrollY:        "400px",
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 1,
                    rightColumns:2
                }
    });

});
</script>
@endif