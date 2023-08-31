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
                    <th>Serial #</th>
                    <th>Description</th>
                    <th>Total Budget</th>
                    <th>Total Utilization</th>
                    @foreach($months as $month)
                    <th>{{$month}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($directCostArray as $key=>$directCost)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$directCost['direct_cost_description']}}</td>
                    <td>{{number_format($directCost['total_budget'],0)}}</td>
                    <td>{{number_format($directCost['total_item_utilized'],0)}}</td>
                    @foreach($months as $month)
                    <td>{{number_format((float)$directCost[$month],0)}}</td>
                    @endforeach
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Blfrtip',
            "ordering": false,
            buttons: [
                'copy', 'excel', 'pdf'
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