@extends('layouts.master.master')
@section('title', 'Leave Balance')
<h3 class="text-themecolor"></h3>
@section('content')

<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">Leave Balance</h4>

		{!!$dataTable->table()!!}
	</div>
</div>


{!!$dataTable->scripts()!!}
@stop

