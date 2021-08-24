@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of HR Reports</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">List of HR Reports</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					
					<th>Name of Report</th>
					<th class="text-center"style="width:5%">Show</th> 
				</tr>
				</thead>
				
				<tbody>
					
					<tr>
						<td><a href="{{route('hrReports.cnicExpiryList')}}"style="color:black">CNIC Expiry List</a></td>															
					</tr>
					<tr>
						<td><a href="{{route('hrReports.missingDocumentList')}}" style="color:black">Missing Document List</a></td>
																				
					</tr>

					<tr>
						<td><a href="{{route('hrReports.searchEmployee')}}" style="color:black">Search Employee</a></td>												
					</tr>
					<tr>
						<td><a href="{{route('hrReports.report_1')}}" style="color:black">Report_1 (Father Name, CNIC, Degree, Degree Year, DOJ, PEC, Employee No., Contact No.)</a></td>
					</tr>
				
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
        		}
            });
            
        });
</script>

@stop