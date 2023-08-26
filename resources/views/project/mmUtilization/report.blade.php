@extends('layouts.master.master')
@section('title', 'Man Month Utilization')
@section('Heading')
<h3 class="text-themecolor">Man Month Utilization</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <table id="myTable" class="table table-bordered table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Position #</th>
                    <th>Position Name</th>
                    <th>Total Man Month</th>
                    <th>Total MM Utilized</th>
                    <th>Total Cost Utilized</th>
                    <th>Employee Name</th>
                    <th>Total MM As Per Employee</th>

                    <th>Billing Rate</th>
                    <th>MM As Per Billing Rate</th>
                    <th>Amount As Per Billing Rate</th>
                    @foreach($months as $month)
                    <th>{{$month}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($positionArray as $key=>$positions)


                @foreach ($positions as $positinoKey=>$position)

                <tr>
                    @if($positinoKey==0)
                    <td>{{$key+1}}</td>
                    <td>{{$position['position']}}</td>
                    <td>{{$position['total_man_month']}}</td>
                    <td>{{$position['total_mm_utilized']}}</td>
                    <td>{{number_format($position['total'],2)}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                    @if($positinoKey>0 && $positions[$positinoKey-1]['employee_name'] == $positions[$positinoKey]['employee_name'])
                    <td></td>
                    <td></td>

                    @else
                    <td>{{$position['employee_name']}}</td>
                    <td>{{$position['employee_total_mm']}}</td>

                    @endif
                    <td>{{number_format($position['billing_rate'])}}</td>
                    <td>{{$position['total_mm_utilized_billing']}}</td>
                    <td>{{number_format($position['employee_total'],2)}}</td>
                    @foreach($months as $month)
                    <td>{{$position[$month]}}</td>
                    @endforeach
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Blfrtip',
            "ordering": false,
            // rowGroup: {
            //     dataSrc: [1]
            // },
            buttons: [{
                    extend: 'copyHtml5',
                },
                {
                    extend: 'excelHtml5',
                },
                {
                    extend: 'pdfHtml5',
                }, {
                    extend: 'csvHtml5',
                },

            ],
            order: [],

            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                left: 3,
            },
            "drawCallback": function(settings) {
                var api = this.api();

                // Output the data for the visible rows to the browser's console
                console.log(api.rows({
                    page: 'current'
                }).data());
            }
        });




    });
</script>
@stop