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
                    <th>Permission Name</th>
                
                </tr>
                </thead>
                <tbody>
                    @foreach($result as $permission)
                        <tr>
                            <td>{{$permission->name}}</td>
                                                        
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
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0]
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