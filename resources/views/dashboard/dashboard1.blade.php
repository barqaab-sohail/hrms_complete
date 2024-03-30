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
			
			<h2 >Welcome to HRMS</h2>
			@can('View Record')
				<h1>@php
				echo 'Dashboard-1';
				@endphp
				</h1>
			@endcan

			
		</div>
	</div>
	

	<!--TASK -->
		<div class="card">
			<div class="card-body">
			
	        
		          	<!-- Button trigger modal -->

		          	<button type="button" class="btn btn-success float-right"  data-toggle="modal" data-target="#taskModal"> 
		            Add New Task
		          	</button>
		          
		          	<!-- Modeal Include-->
		          
	  			
	  				<div id="append_data" class="table-responsive m-t-40">
	      		</div>
				
				

	
			</div>	
	
		</div>
	<!--End TASK -->

	


@stop
  

@section('footer')

@stop