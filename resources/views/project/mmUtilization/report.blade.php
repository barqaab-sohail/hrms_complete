@extends('layouts.master.master')
@section('title', 'Man Month Utilization')
@section('Heading')
<h3 class="text-themecolor">Man Month Utilization</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-striped data-table">
            <thead>
                <tr>
                    <th>Sr. #</th>
                    <th>Position Name</th>
                    <th>Total Man Month</th>
                    <th>Billing Rate</th>
                    <th>Employee Name</th>
                    @foreach($months as $month)
                    <th>{{$month->month_year}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {


        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('project.utilization') }}",

                columns: [{
                        data: "key",
                        name: 'key'
                    },

                    {
                        data: "position_name",
                        name: 'position_name'
                    },
                    {
                        data: 'total_man_month',
                        name: 'total_man_month'
                    },
                    {
                        data: 'billing_rate',
                        name: 'billing_rate'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },


                ],
                order: [
                    [2, "desc"]
                ]
            });

        });
    });
</script>
@stop