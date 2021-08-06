@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {
        var aboveSixty = {{$countAboveSixty}};
        var belowSixty = {{$countBelowSixty}};
        var total ={{$countAboveSixty}} + {{$countBelowSixty}};

        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['Above Sixty',     aboveSixty],
          ['Below sixty',      belowSixty]
          
        ]);

        colorsHex = [
        
    '#1e8449',
    '#007fff'
  ];  
        var options = {
          title: 'Agewise Chart'+ ' - (Total Employee = '+ total+')',
          colors: colorsHex,
          is3D: true,
          pieSliceText: 'value'
        };

        var chart = new google.visualization.PieChart(document.getElementById('ageChart'));

        chart.draw(data, options);
      }
    </script>

@endpush
