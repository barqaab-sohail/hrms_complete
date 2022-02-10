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
                            <th>Employee No</th>
                            <th>Employee Name</th>
                            <th>Designation/Position</th>
                            <th>Input</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @foreach($inputProjects as $first=>$project)
                    
                    @foreach($result as $k=>$input)
                     
                        @if($project->pr_detail_id == $input->prdetail->id)
                        <tr>   
                            <td>{{$input->hrEmployee->employee_no??''}}
                            </td>
                            <td>
                                {{$input->hrEmployee->first_name}} {{$input->hrEmployee->last_name}}
                                
                                Project-{{$project->pr_detail_id}}
                               
                            </td>
                            <td>{{$input->prDetail->name??''}}</td>
                            <td>{{$input->input??''}}</td>
                            <td></td>
                        </tr>      
                        @endif
                    @endforeach
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
                                columns: [ 0, 1, 2,3,4]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2,3,4]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2,3,4]
                            }
                        }, {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2,3,4]
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