
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
          title: 'Category Wise Chart',
          colors: colorsHex,
          pieHole: 0.4,
          pieSliceText: 'value',
          legend:{position:'left'}
        };

        var options1 = {
          title: 'Categorywise Chart',
          colors: colorsHex,
           isStacked: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('categoryChart'));
        //bar chart object
        //var chart1 = new google.visualization.ColumnChart(document.getElementById('lineChart'));

        chart.draw(data, options);
        //chart1.draw(data1, options1);
      }
    </script>

@endpush
