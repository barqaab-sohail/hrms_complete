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
                    <td></td>
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
        var table = $('#myTable').DataTable({
            dom: 'Blfrtip',

            rowGroup: {
                dataSrc: [1]
            },
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


            // createdRow: function(row, data, dataIndex) {
            //     // if (data.member_name != 'xyz') {
            //     // console.log(dataIndex);

            //     // if (dataIndex == 0) {
            //     //     $('td:eq(' + dataIndex + ')', row).attr('rowspan', 3);
            //     // }
            //     // $('td:eq(1)', row).css('display', 'none');
            //     //  $('td:eq(2)', row).css('display', 'none');
            //     //$('td:eq(15)', row).css('display', 'none');
            //     //$('td:eq(16)', row).css('display', 'none');
            //     // }
            // },
            scrollY: "300px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                left: 1,
            },
        });


        // table.rows().every(function(index) {
        //     var element = {};
        //     var d = this.data();

        // });

        // //  console.log(table.row(0).data());

        // // Find indexes of rows which have `Yes` in the second column
        // var indexes = table.rows().eq(0).filter(function(rowIdx) {
        //     //console.log(table.cell(rowIdx + 1, 0).data());
        //     return table.cell(rowIdx, 0).data() == 1 ? true : false;
        // });
        // table.rows(indexes)
        //     .nodes()
        //     .to$()
        //     .addClass('highlight');
        // table.draw();

    });
</script>
@stop