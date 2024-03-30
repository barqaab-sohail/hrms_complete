@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->

@stop
@section('content')

<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">Leave Balance</h4>

		{!!$dataTable->table()!!}
	</div>
</div>


{!!$dataTable->scripts()!!}
@stop

