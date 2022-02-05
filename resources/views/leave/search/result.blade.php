@if($result->count()!=0 && !$leaveBalance)
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
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Designation/Position</th>
                        <th>Leave From</th>
                        <th>Leave To</th>
                        <th>Leave Type</th>
                        <th>Total Days</th>
                        <th>Leave Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $leave)
                            <tr>
                                <td>{{$leave->hrEmployee->employee_no??''}}</td>
                                <td>
                                    {{$leave->hrEmployee->first_name}} {{$leave->hrEmployee->last_name}}
                                </td>
                                <td>{{$leave->employeeDesignation->last()->name??''}}</td>
                                <td>{{date('M d, Y', strtotime($leave->from))}}</td>
                               <td>{{date('M d, Y', strtotime($leave->to))}}</td>
                                <td>{{$leave->leType->name}}</td>
                                <td>{{$leave->days}}</td>
                                <td>{{leaveStatusType($leave->leSanctioned->le_status_type_id??'')}}</td>
                                                            
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
                    order: [[ 3, 'asc' ]],
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
@elseif($leaveBalance)
<div class="card">
    <div class="card-body"> 
        <h4 class="card-title" style="color:black">Leave Balance</h4>

        <div class="table-responsive m-t-40">   
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Casual Leave</th>
                    <th>Earned Leave</th>
                    <th>Accumulative E/L</th>
                </tr>
                </thead>
                 <tbody>
                    <tr>
                        <td>{{$result->employee_no??''}}</td>
                        <td>{{$result->first_name}} {{$result->last_name}}</td>
                        <td>{{casualLeave($result->id)}}</td>
                        <td>{{annualLeave($result->id)}}</td>
                        <td>{{$result->leAccumulative->accumulative_total??'N/A'}}</td>                              
                    </tr>          
                </tbody>
            </table>
        </div>
        
    </div>
</div>




@endif