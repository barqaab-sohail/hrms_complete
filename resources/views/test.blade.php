@extends('layouts.master.master')
<h3 class="text-themecolor"></h3>
@section('content')
   {{$dataTable->table()}}

@push('scripts')
	<script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {{$dataTable->scripts()}}
@endpush

@stop