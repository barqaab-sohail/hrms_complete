@if($result->count()!=0)
    <hr>         
            <div class="card">
            <div class="card-body">
                <!--<div class="float-right">
                    <input id="month" class="form-control" value="" type="month">
                </div>-->
                
                <div class="table-responsive m-t-40">
                
                <table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                @foreach($inputProjects as $inputProject)
                    @foreach($result as $input)
                    

                    @if($inputProject->id == $input->input_project_id)
                    @if($loop->first)
                     <thead>
                        <tr>
                         <th id="par" colspan="5">{{$inputProject->prDetail->name??''}}</th>
                        </tr>
                        <tr>
                           
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Designation/Position</th>
                            <th>Input</th>
                            <th>Remarks</th>
                            
                        </tr>
                    </thead>
                    @endif
                    <tbody>
                            <tr>   
                            
                                <td>{{$input->hrEmployee->employee_no??''}}</td>
                                <td>
                                    {{$input->hrEmployee->first_name}} {{$input->hrEmployee->last_name}}
                                </td>
                                <td>{{$input->hrDesignation->name??''}}</td>
                                <td>{{$input->input??''}}</td>
                                <td>{{$input->remarks??''}}</td>
                           
                                                            
                            </tr>
                             
                    
                    </tbody>
                  
                         @endif  
                    @endforeach
                @endforeach
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