@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Employee Monthly Charging</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
		
		
	</ol>
@stop
@section('content')
	<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
			<h4 class="card-title">Employee Monthly Charging</h4>
			
			<div class="table-responsive m-t-40">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr>
						<th style="width:20%">Employee Name</th>
						<th style="width:20%">Father Name</th>
						<th style="width:15%">CNIC</th>
						<th style="width:35%">Project</th>
						<th style="width:5%">MM</th>
						<th style="width:5%">MM</th>
						
						

						
						
					</tr>
					</thead>
					<tbody>
						@foreach($employees as $employee)
							<tr>
								<td>{{$employee->first_name}} {{$employee->last_name}}</td>
								<td>{{$employee->father_name}}</td>
								<td>{{$employee->cnic}}</td>
								<td> </td>
								<td><input type="text" id="marks_obtain" name="marks_obtain" value="{{ old('marks_obtain') }}"   class="form-control " ></td>
								<td></td>
							
																														
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
                scrollY:        "300px",
      			scrollX:        true,
        		scrollCollapse: true,
        		paging:         false,
                dom: 'Blfrti',
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
                ]
            });
        });
 
	</script>

@stop