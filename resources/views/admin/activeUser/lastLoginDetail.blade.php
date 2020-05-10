@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Last Login Detail</h3>
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
			<h4 class="card-title">Last Login Detail</h4>
			
			<div class="table-responsive m-t-40">
				
				<table id="myTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr>
						<th>Employee Name</th>
						<th>Email</th>
						<th>CNIC</th>
						<th>Father Name</th>
						<th>Last Login Time</th>
						<th>Last Login IP</th>
						
					</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
							<tr>
								<td>{{$user->hrEmployee->first_name}} {{$user->hrEmployee->last_name}}</td>
								<td>{{$user->hrEmployee->email}}</td>
								<td>{{$user->hrEmployee->cnic}}</td>
								<td>{{$user->hrEmployee->father_name}}</td>
								<td>{{$user->last_login_at}}</td>
								<td>{{$user->last_login_ip}}</td>
																						
							</tr>
						@endforeach
					
					 
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
@push('scripts')	
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
	@endpush
@stop