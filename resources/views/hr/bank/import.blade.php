@extends('layouts.master.master')
@section('title', 'Projects List')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card">
    <div class="card-body">
        @can('Super Admin')
        <div class="container" id='hideDiv'>
            <h3 align="center">Import Excel File</h3>

            <form method="POST" enctype="multipart/form-data" action="{{route('importBankDetail.import')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <table class="table">
                        <tr>
                            <td width="40%" align="right"><label>Select File for Upload</label></td>
                            <td width="30">
                                <input type="file" name="excel_file" />
                            </td>
                            <td width="30%" align="left">
                                <input type="submit" name="upload" class="btn btn-success" value="Upload">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" align="right"></td>
                            <td width="30"><span class="text-muted">.xls, .xslx Files Only</span></td>
                            <td width="30%" align="left"></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>


        <hr>
        @endcan


    </div>
</div>


<script>
    $(document).ready(function() {





    });
</script>

@stop