@if($data->count()!=0)

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
                        <th>Employee Name</th>
                        <th>Project Name</th>
                        <th>Progress Rights</th>
                        <th>Invoice Rights</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $right)
                    <tr>
                        <td>{{$right->id}}</td>
                        <td>{{$right->pr_detail_id}}</td>
                        <td>{{$right->progress}}</td>
                        <td>{{$right->invoice}}</td>
 
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