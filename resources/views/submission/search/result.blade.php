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
                        <th>Project Name</th>
                        <th>Client Name</th>
                        <th>Division</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $submission)
                        
                        <tr>
                            <td>{{$submission->project_name??''}}</td>
                            <td>{{$submission->client->name??''}}</td>
                            <td>{{$submission->subDivision->name??''}}</td>
                            <td>{{$submission->subStatus->name??''}}</td>
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
         //function view from list table
        $(function(){

             $('.ViewIMG').EZView();
        });

        $('#myDataTable').DataTable({
                    stateSave: false,        
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [ 0, 1]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 0, 1,]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [ 0, 1,]
                            }
                        }, {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [ 0, 1]
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