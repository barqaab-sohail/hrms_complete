@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {
        var countBelowForty = {{$countBelowForty}};
        var countBelowFifty = {{$countBelowFifty}};
        var countBelowSixty = {{$countBelowSixty}};
        var countBelowSeventy = {{$countBelowSeventy}};
        var countAboveSeventy = {{$countAboveSeventy}};


        var total ={{$countBelowForty}} + {{$countBelowFifty}} + {{$countBelowSixty}} + {{$countBelowSeventy}} + {{$countAboveSeventy}};

        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['Below 40 Years',     countBelowForty],
          ['Between 40 to 50 Years',     countBelowFifty],
          ['Between 50 to 60 Years',     countBelowSixty],
          ['Between 60 to 70 Years',     countBelowSeventy],
          ['Above 70 Years',      countAboveSeventy]
          
        ]);

        colorsHex = ['#1e8449','#007fff','#17c9ff','#e3890b','#f22e07'];  
        var options = {
          title: 'Age Wise Chart'+ ' - (Total Employee = '+ total+')',
          colors: colorsHex,
          is3D: true,
          pieSliceText: 'value',
          legend:{position:'left'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('ageChart'));

        chart.draw(data, options);
      }
    </script>

@endpush
