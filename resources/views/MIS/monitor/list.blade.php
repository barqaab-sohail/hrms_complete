@extends('layouts.master.master')
@section('title', 'MIS Monitor')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')

<!-- End Modal -->
<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="color:black">Invoices Status</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:75%">Project Name</th>
                        <th style="width:15%">Last Invoice Created</th>
                        <th style="width:10%">Invoice Month</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                "aaSorting": [],
                ajax: {
                    url: "{{ route('MISMonitor.index') }}",
                },
                columns: [{
                        data: 'project_name',
                        name: 'project_name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'invoice_month',
                        name: 'invoice_month'
                    },
                ]
            });

        }); // end function

    }); //End document ready function
</script>

</div>

@stop