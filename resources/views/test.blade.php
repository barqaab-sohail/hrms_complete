@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
   {{$dataTable->table()}}

@push('scripts')
	<script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {{$dataTable->scripts()}}
@endpush

@stop