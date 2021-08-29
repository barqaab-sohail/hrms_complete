@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
        ["Element", "Millon", { role: "style" } ],
        ["Invoice Pending", 9.15, "Red"],
        ["Invoice Received", 10.15, "yellow"],
        ["Invoice Raised", 19.30, "green"],
        ["Total Cost", 50, "Blue"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Invoice Status in Rupees Millon",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
        var chart = new google.visualization.BarChart(document.getElementById('invoice'));

        chart.draw(data, options);
      }
    </script>

@endpush
