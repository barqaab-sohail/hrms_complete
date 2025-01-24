@extends('layouts.master.master')
@section('title', 'Projects List')
@section('Heading')
<h3 class="text-themecolor">List of Photocopies</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        

        <h4 class="card-title">List of Photocopies</h4>
        <livewire:photocopy.Index/>

        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Project No</th>
                        <th>Project Name</th>
                        <th>Cost</th>
                        <th>Division</th>
                        <th>Client Name</th>
                        <th>Commencement Date</th>
                        <th>Completion Date</th>
                        <th>Status</th>
                        <th>JV/Independent</th>
                        <th class="text-center" style="width:5%">Edit</th>
                        @role('Super Admin')
                        <th class="text-center" style="width:5%">Delete</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {



        $('#myTable').DataTable({
            stateSave: false,

            dom: 'Blfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5,6,7,8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5,6,7,8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5,6,7,8]
                    }
                }, {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4,5,6,7,8]
                    }
                },
            ],
            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 2
            }
        });

    });
</script>

@stop