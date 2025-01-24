@extends('layouts.master.master')
@section('title', 'Active Employee List')
@section('Heading')
<h3 class="text-themecolor">List of Employees</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">MM Utilizations</h4>

        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Positions</th>
                        <th>Total Man Months</th>
                        <th>Name</th>
                        @foreach($months as $month)
                        <th>{{$month->month_year}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                    $serial = 1;
                    @endphp

                    @foreach ($prPositions as $position)
                    <tr>
                        <td>{{$serial++}}</td>
                        <td>{{$position->hrDesignation->name}}</td>
                        <td>{{$position->total_mm}}</td>


                        <td> @foreach(mmUtilization(14, $position->id) as $employeeid)
                            {{employeeName($employeeid)}} <br>
                            @endforeach
                        </td>


                        @foreach($months as $month)
                        @foreach ($prMmUtilizations as $prMmUtilization)
                        @if($month->month_year == $prMmUtilization->month_year && $prMmUtilization->pr_position_id == $position->id)
                        <td>{{$prMmUtilization->man_month}}</td>
                        @endif
                        @endforeach
                        @endforeach



                    </tr>
                    @endforeach

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
            columnDefs: [{
                type: 'date',
                'targets': [5]
            }],
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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