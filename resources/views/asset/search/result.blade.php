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
                        <th>Asset Id</th>
                        <th>Description</th>
                        <th>Image</th>
                       
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $asset)
                        
                        <tr>
                            <td>{{$asset->asset_code??''}}</td>
                            <td>{{$asset->description}}</td>
                            <td>
                            <img src="{{asset('storage/'.$asset->asPicture->path.$asset->asPicture->file_name)}}" onerror="this.src ='{{asset('Massets/images/default.png')}}';" alt="user" class="profile-pic ViewIMG" width="20%"/></td>
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
                                columns: [ 0, 1,2]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2]
                            }
                        }, {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2]
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