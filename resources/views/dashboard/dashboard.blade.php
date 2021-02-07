@extends('layouts.master.master')
@section('title', 'BARQAAB HR')


@section('Heading')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
 


	<h3 class="text-themecolor">Dashboard</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
		
		
	</ol>

@stop
@section('content')

	<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>
			<h4 class="card-title">Salaries</h4>
			-->
			
			<h2 >{{ucwords (Auth::User()->hrEmployee->first_name??'')}} {{ucwords(Auth::User()->hrEmployee->last_name??'')}} Welcome to HRMS</h2>
			
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>
			<h4 class="card-title">Salaries</h4>
			-->
			
		</div>
	</div>
	
@can('Super Admin')
	<!--TASK -->
		<div class="card">
			<div class="card-body">
			
	        
		          	<!-- Button trigger modal -->

		          	<button type="button" class="btn btn-info float-right"  data-toggle="modal" data-target="#taskModal"> 
		            Add New Task
		          	</button>
		          
		          	<!-- Modeal Include-->
		          	@include('self.task.modal')
		          	@include('self.task.editModal')
	  				

	  					<div id="append_data" class="table-responsive m-t-40 table-container">
	  				
            	         
            			
           				 </div>
       			

	      		</div>
	
			</div>	
	
		</div>
	<!--End TASK -->
@endcan



@stop

@push('scripts')
<script>
$(document).ready(function () {
  
	//var url = "{{route('task.index')}}";
	//refreshTable("{{route('task.index')}}");
	//load_data();
});
	

	 function load_data(){
    var loadUrl = "{{route('task.index')}}";
          $("#append_data").load(loadUrl, function (){
            $('#myTable').DataTable({
              stateSave: false,
              "order": [[ 2, "asc" ]],
              "destroy": true,
              "columnDefs": [
              { "width": "30%", "targets": 0, },
              {"targets": "_all", "className": "dt-center"}
              ],
                   dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 0, 1, 2]
                            }
                        },
                    ]
                 
            });
        });
  }

</script>
@endpush

@section('footer')

@stop

