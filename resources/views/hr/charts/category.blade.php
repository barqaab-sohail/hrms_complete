	<div class="card">
		<div class="card-body">
		            
                <br>
                <br>
        <div class="row">
            <div class="col-md-6">
    		    <div id="piechart" style="width: 700px; height: 500px;"></div>
            </div>
             <div class="col-md-6">
            <div id="lineChart" style="width: 700px; height: 500px;"></div>
            </div>
        </div>
   
        			
		</div>
	</div>

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var categoryA = {{$employees->categoryA()}};
        var categoryB = {{$employees->categoryB()}};
        var categoryC = {{$employees->categoryC()}};
        

        var data = google.visualization.arrayToDataTable([
          ['Description', 'Quantity'],
          ['Category A Employees',     categoryA],
          ['Category B Employees',      categoryB],
          ['Category C Employees',      categoryC]
          
        ]);

        var data1 = google.visualization.arrayToDataTable([
          ['Description', '', { role: 'style' }, { role: 'annotation' }],
          ['Category A',     categoryA, '#e94922', categoryA],
          ['Category B',      categoryB, '#4f328a', categoryB],
          ['Category C',      categoryC, '#00a49f', categoryC]
          
        ]);

        colorsHex = ['#e94922','#4f328a','#00a49f'];
  
        var options = {
          title: 'Categorywise Chart',
          colors: colorsHex,
          is3D: true,
          pieSliceText: 'value'
        };

        var options1 = {
          title: 'Categorywise Chart',
          colors: colorsHex,
           legend: { position: 'top', maxLines: 3 },
           isStacked: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        var chart1 = new google.visualization.ColumnChart(document.getElementById('lineChart'));

        chart.draw(data, options);
        chart1.draw(data1, options1);
      }
    </script>

@endpush
