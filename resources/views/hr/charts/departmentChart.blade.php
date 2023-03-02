
@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var finance = {{$finance}};
        var power = {{$power}};
        var water = {{$water}};
        
        //Pie chart data
        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['Finance Department',     finance],
          ['Power Department',      power],
          ['Water Department',      water]
          
        ]);


        colorsHex = ['#7f5539','#dc2f02','#4895ef'];
  
        var options = {
          title: 'Department Wise Chart',
          colors: colorsHex,
          pieHole: 0.4,
          pieSliceText: 'value',
          legend:{position:'top'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('departmentChart'));
    
        chart.draw(data, options);
      }
    </script>

@endpush
