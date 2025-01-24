@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {
        var twentyYears = {{$educations['20Years']}};
        var eighteenYears = {{$educations['18Years']}};
        var sixteenYears = {{$educations['16Years']}};
        var forteenYears = {{$educations['14Years']}};
        var twelveYears = {{$educations['12Years']}};
        var tenYears = {{$educations['10Years']}};

        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['18 Years',     eighteenYears],
          ['16 Years',      sixteenYears],
          ['14 Years',      forteenYears],
          ['12 Years',      twelveYears],
          ['10 Years',      tenYears]
          
          
        ]);

        colorsHex = ['#FFEB00','#007fff','#17c9ff','#e3890b','#f22e07'];

        var options = {
          title: 'Educationwise Chart',
          colors: colorsHex,
          is3D: true,
          pieSliceText: 'value',
          legend:{position:'top'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('educationChart'));

        chart.draw(data, options);
      }
    </script>

@endpush
