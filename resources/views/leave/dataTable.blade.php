@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->

@stop
@section('content')
<!-- Yajra Datatable script -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap4.min.js"></script>
<!-- Yajra Datatable script -->

<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">Leave Balance</h4>

		{!!$dataTable->table()!!}
	</div>
</div>



{!!$dataTable->scripts()!!}

@stop