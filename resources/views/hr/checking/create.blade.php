@extends('layouts.master.master')
@section('title', 'HR Report Checking')
@section('Heading')
<h3 class="text-themecolor">HR Monthly Report Checking</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-12 table-container">


            </div>
        </div>
        <form action="{{ route('process.files') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="excel_files">Upload Excel Files:</label>
                <input type="file" name="excel_files[]" multiple accept=".xls,.xlsx">
            </div>
            <button type="submit">Process Files</button>
        </form>
    </div>
</div>
@stop