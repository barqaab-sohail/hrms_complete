
<div class="table-responsive m-t-40">	
	<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
		<thead>
		<tr>
			<th>Full Name</th>
			<th>Detail</th>

		</tr>
		</thead>
		<tbody>
		
			<tr>
				<td></td>
				<td></td>
			</tr>
		
		
		</tbody>
	</table>
</div>

@push('scripts')
<script>
$(document).ready(function() {


	
            $('#myTable').DataTable({
                stateSave: false,
        
                dom: 'Blrtip',
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
@endpush