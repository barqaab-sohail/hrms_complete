@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Asset Types</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					<th>Asset Name</th>
					<th>Quantity</th>				
				</tr>
				</thead>
				<tbody>
			
					@foreach($types as $type)
						<tr>
							<td>{{$type['name']}}</td>
							<td>{{$type['count']}}</td>					
						</tr>
					@endforeach
				
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
$(document).ready(function() {
	
            $('#myTable').DataTable({
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
                            columns: [ 0, 1]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
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
        		},
        		
            });

	
});


</script>

@stop