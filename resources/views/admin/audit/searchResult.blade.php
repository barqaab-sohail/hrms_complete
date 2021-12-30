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
                        <th>User Name</th>
                        <th>Event</th>
                        <th>Auditable Type</th>
                        <th>Auditable Id</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $audit)
                    <tr>
                        <td>{{userFullName($audit->user_id)}}</td>
                        <td>{{$audit->event}}</td>
                        <td>{{$audit->auditable_type}}</td>
                        <td>{{$audit->auditable_id}}</td>
                        <td>{{$audit->old_values}}</td>
                        <td>{{$audit->new_values}}</td>
                        <td>{{$audit->created_at}}</td>
                        
 
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
                    "order": [[ 5, "desc" ]],
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