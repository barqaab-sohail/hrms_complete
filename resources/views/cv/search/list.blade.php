@if($result->count()!=0)

<hr>         
        <div class="card">
        <div class="card-body">
            <!--<div class="float-right">
                <input id="month" class="form-control" value="" type="month">
            </div>-->
            
            <div class="table-responsive">
                
                <table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                    
                    <tr >
                        <th>Full Name</th>
                        <th>Stage</th>
                        <th>Experience</th>

                    </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($result as $cvDetail)
                            <tr>
                                <td>{{$cvDetail->full_name}}</td>
                                
                                <td>{{$cvDetail->cvEducation->implode('degree_name',' + ')}}</td>
                              
                                <td>{{$cvDetail->year}}</td>
                               
                            </tr>
                        @endforeach
                    
                     
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>  
@endif
<script>
$(document).ready(function() {


            $('#myTable').DataTable({
                stateSave: false,
        
                dom: 'Bfrti',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                ],
                scrollY:        "300px",
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