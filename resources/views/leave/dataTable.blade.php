@extends('layouts.master.master')
@section('title', 'Leave Balance')
@section('Heading')
<h3 class="text-themecolor">Leave Balance</h3>
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

