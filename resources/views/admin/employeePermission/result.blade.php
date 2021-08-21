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
                    <th> Delete </th> 
                
                </tr>
                </thead>
                <tbody>
                    @foreach($result as $permission)
                        <tr>
                            <td>{{$permission->name}}</td>
                            <td class="text-center">
                                 @can('hr edit record')
                                 <form  id="deletePermission{{$permission->id}}{{$userId}}" action="{{route('employeePermission.destroy',['id'=>$permission->id, 'userId'=>$userId])}}" method="POST">
                                 @method('DELETE')
                                 @csrf
                                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                                 </form>
                                 @endcan
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

    $("form[id^=deletePermission]").submit(function(e) { 
    e.preventDefault();
    var url = $(this).attr('action');
    $('.fa-spinner').show(); 
    submitForm(this, url);
    
    });

});
</script>
@endif