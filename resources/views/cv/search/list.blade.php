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
                        <th>Education</th>
                         <th>Detail</th>
                        <th>Experience</th>
                        <th>Total Experience</th>

                    </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($result as $cvDetail)
                            <tr>
                                <td>{{$cvDetail->full_name}}  </td>

                                <td>{{$cvDetail->cvEducation->implode('degree_name',' + ')}}</td>
                                <td> <button type="button" name="edit" id="{{$cvDetail->id}}" class="edit btn btn-primary btn-sm">Detail</button>
                           


                                <!-- @foreach($cvDetail->cvExperience as $spec)
                                        {{cvSpecilizationName($spec->cv_specialization_id)}} {{$spec->year}}

                                    @endforeach -->

                                </td> 
                                <td><!-- {{$cvDetail->cvExperience->implode('year',' + ')}} --></td>
                                <td>{{$cvDetail->cvExperience->sum('year')}}</td>
                               
                            </tr>
                        @endforeach
                    
                     
                    
                    </tbody>
                </table>
            </div>
            @include('cv.search.detailModal')
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