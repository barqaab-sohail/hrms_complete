@extends('layouts.master.master')
@section('title', 'BARQAAB HR')


@section('Heading')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
 


  <h3 class="text-themecolor">Dashboard</h3>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
    
    
  </ol>
@stop
@section('content')
	<div class="card">
		<div class="card-body">
		            
                <br>
                <br>
        <div class="row">
        <h1>Total Employee: {{$employees}}</h1>
            <div class="col-md-6">
    		    <div id="piechart" style="width: 700px; height: 500px;"></div>
            </div>
             <div class="col-md-6">
            <div id="lineChart" style="width: 700px; height: 500px;"></div>
            </div>
        </div>
   
        			
		</div>
	</div>
@stop
@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var categoryA = {{$categoryA}};
        var categoryB = {{$categoryB}};
        var categoryC = {{$categoryC}};
        
        //Pie chart data
        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['Category A Employees',     categoryA],
          ['Category B Employees',      categoryB],
          ['Category C Employees',      categoryC]
          
        ]);

        //Bar chart data
        var data1 = google.visualization.arrayToDataTable([
          ['Description', '', { role: 'style' }, { role: 'annotation' }],
          ['Category A',     categoryA, '#e94922', categoryA],
          ['Category B',      categoryB, '#4f328a', categoryB],
          ['Category C',      categoryC, '#00a49f', categoryC],
          
        ]);

        colorsHex = ['#e94922','#4f328a','#00a49f'];
  
        var options = {
          title: 'Categorywise Chart',
          colors: colorsHex,
          pieHole: 0.4,
          pieSliceText: 'value',
        };

        var options1 = {
          title: 'Categorywise Chart',
          colors: colorsHex,
           isStacked: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        //bar chart object
        //var chart1 = new google.visualization.ColumnChart(document.getElementById('lineChart'));

        chart.draw(data, options);
        chart1.draw(data1, options1);
      }
    </script>

@endpush
