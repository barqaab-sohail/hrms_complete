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
                        <th>Id</th>
                        <th>Employee Name</th>
                        <th>HOD Name</th>
                        <th>Effective Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $emp)
                    <tr>
                        <td>{{$emp->id}}</td>
                        <td>{{$emp->first_name. ' '.$emp->last_name .' - '. $emp->employeeDesignation->last()->name??''}}</td>
                        <td>{{employeeFullName($emp->hr_manager_id)}}</td>
                        <td>{{$emp->effective_date}}</td>                   
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
                    dom: 'flrti',
                    scrollY:        "500px",
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