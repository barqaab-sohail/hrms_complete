@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {
        var pecEngineer = {{$pecRegisteredEngineers}};
        var associatedEngineers = {{$associatedEngineers}};
        var OtherStaff ={{$allEmployees}} - {{$pecRegisteredEngineers}} - {{$associatedEngineers}};

        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['Engineers',     pecEngineer],
          ['Associate Engineers',     associatedEngineers],
          ['OtherStaff',      OtherStaff]
          
        ]);

        colorsHex = ['#e94922','#4f328a','#00a49f'];
        var options = {
          title: 'PEC Registered Engineer Chart',
          colors: colorsHex,
          is3D: true,
          pieSliceText: 'value',
          legend:{position:'top'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('engineerChart'));

        chart.draw(data, options);
      }
    </script>

@endpush
