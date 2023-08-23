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
                    <th>Sr. #</th>
                    <th>Position Name</th>
                    <th>Total Man Month</th>
                    <th>Employee Name</th>
                    <th>Total MM Utilized</th>
                    <th>Total Cost Utilized</th>
                    <th>Total Employee Man Month</th>
                    <th>Billing Rate</th>
                    <th>Employee Utilized Amount</th>
                    @foreach($months as $month)
                    <th>{{$month}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($positionArray as $key=>$positions)
                @foreach ($positions as $position)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$position['position']}}</td>
                    <td>{{$position['total_man_month']}}</td>
                    <td>{{$position['employee_name']}}</td>
                    <td>{{$position['total_mm_utilized']}}</td>
                    <td>{{number_format($position['total'],2)}}</td>
                    <td>{{$position['employee_total_mm']}}</td>
                    <td>{{number_format($position['billing_rate'])}}</td>
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
        var oTable = $('#myTable').DataTable({

            dom: 'Blfrtip',
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
            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                left: 1,
            },
        });



    });
</script>
@stop