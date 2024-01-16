
        
    <div class="card-body">
       
                    
        <h3 class="box-title">Project Progress Chart</h3>
        
        <hr class="m-t-0 m-b-40">

        <div class="row">
            <div id="s-curve" style="width: 100%; height: 500px;"></div>
        </div><!--/End Row-->
     
	</div> <!-- end card body --> 

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>

    
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Year', 'Target', 'Achieved'],
          ['12-2019',  0.72,     0.7],
          ['01-2020',  1.96,     1.7],
          ['02-2020',  4.88,     2.88],
          ['03-2020',  10.48,    8.0],
          ['04-2020',  21.14,    18.14],
          ['05-2020',  33.69,    24.50],
          ['06-2020',  48.42,    40.56],
          ['07-2020',  62.98,    50.21],
          ['08-2020',  76.40,    65.12],
          ['09-2020',  86.88,    76.19],
          ['10-2020',  94.21,    86.12],
          ['11-2020',  97.21,    90.11],
          ['12-2020',  98.83,    92.01],
          ['01-2021',  99.60,    93.10],
          ['02-2021',  100,      94.10]
        ]);

        

        var options = {
          title: 'Project Progress',
          curveType: 'function',
          pointSize: 5,
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('s-curve'));

        chart.draw(data, options);

      }

</script>

