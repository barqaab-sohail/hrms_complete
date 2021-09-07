
        
    <div class="card-body">
       
                    
        <h3 class="box-title">Project Invoice Chart</h3>
        
        <hr class="m-t-0 m-b-40">

        <div class="row">
            <div id="pendingInvoice" style="width: 100%; height: 500px;"></div>
            <div id="invoice" style="width: 100%; height: 500px;"></div>
        </div><!--/End Row-->

        <div class="table-responsive m-t-40">
      <table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
        <thead>
          <tr>
            <th>Invoice Month</th>
            <th>Total Invoice</th>
            <th>Man Month Cost</th>
            <th>Direct Cost</th>
            <th>Sales Tax</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Dec19, Jan20, Feb20</td>
            <td>6,765,652</td>
            <td>2,881,571</td>
            <td>3,105,732</td>
            <td>778,349</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Mar20, Apr20</td>
            <td>3,976,607</td>
            <td>2,274,316</td>
            <td>1,244,805</td>
            <td>457,486</td>
            
            <td>Received</td>         
          </tr>
           <tr>
            <td>May-20</td>
            <td>2,788,754</td>
            <td>1,137,158</td>
            <td>1,330,766</td>
            <td>320,830</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Jun-20</td>
            <td>2,818,734</td>
            <td>1,039,042</td>
            <td>1,455,413</td>
            <td>324,279</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Jul20, Aug20</td>
            <td>4,723,246</td>
            <td>2,177,261</td>
            <td>2,002,603</td>
            <td>543,382</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Sep-20</td>
            <td>3,272,703</td>
            <td>1,670,513</td>
            <td>1,225,684</td>
            <td>376,506</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Oct-20</td>
            <td>3,187,641</td>
            <td>1,644,612</td>
            <td>1,176,309</td>
            <td>366,720</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Nov-20</td>
            <td>3,299,229</td>
            <td>1,766,824</td>
            <td>1,152,848</td>
            <td>379,557</td>
           
            <td>Received</td>         
          </tr>
          <tr>
            <td>Dec-20</td>
            <td>3,657,687</td>
            <td>1,958,737</td>
            <td>1,278,154</td>
            <td>420,796</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Jan-21</td>
            <td>3,819,626</td>
            <td>2,030,133</td>
            <td>1,350,067</td>
            <td>439,426</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>Feb-21</td>
            <td>3,787,118</td>
            <td>2,107,998</td>
            <td>1,243,434</td>
            <td>435,686</td>
           
            <td>Received</td>         
          </tr>
          <tr>
            <td>Mar-21</td>
            <td>4,494,139</td>
            <td>2,515,127</td>
            <td>1,461,987</td>
            <td>517,025</td>
           
            <td>Received</td>         
          </tr>
          <tr>
            <td>Apr-21</td>
            <td>5,109,262</td>
            <td>3,158,945</td>
            <td>1,362,526</td>
            <td>587,791</td>
            
            <td>Received</td>         
          </tr>
          <tr>
            <td>May-21</td>
            <td>5,006,917</td>
            <td>3,200,356</td>
            <td>1,230,544</td>
            <td>576,017</td>
           
            <td>Pending</td>         
          </tr>
          <tr>
            <td>Jun-21</td>
            <td>4,788,247</td>
            <td>2,628,363</td>
            <td>1,609,024</td>
            <td>550,860</td>
           
            <td>Pending</td>         
          </tr>
          <tr>
            <td>Jul-21</td>
            <td>5,344,065</td>
            <td>3,147,956 </td>
            <td>1,581,305</td>
            <td>614,804</td>
           
            <td>Pending</td>         
          </tr>
         
          
        
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align:right">Total:</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
      </table>
    </div>
      
	</div><!-- end card body -->
<script>

    
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      


      //Pending Invoice
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      
      function drawChart() {
        

        var total = "15,139,229";

        var data1 = google.visualization.arrayToDataTable([
          ['Invoice Month', 'Pending Amount'],
          ['May-21',     5006917],
          ['June-21',     4788247],
          ['July-21',     5344065]
          
        ]);

        colorsHex = ['#1e8449','#007fff','#17c9ff','#e3890b','#f22e07'];  
        var options1 = {
          title: 'Pending Invoice Detail'+ ' - (Total Pending Invoice = '+ total+')',
          colors: colorsHex,
          is3D: true,
          pieSliceText: 'value',
          legend:{position:'left'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('pendingInvoice'));

        chart.draw(data1, options1);
      }

      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
        ["Element", "Millon", { role: "style" } ],
        ["Invoice Pending",  15139229, "Red",],
        ["Invoice Received",   51393635, "yellow"],
        ["Invoice Raised",   66839627, "green"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Invoice Status in Rupees upto July 2021",
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
        var chart = new google.visualization.BarChart(document.getElementById('invoice'));


        chart.draw(view, options);
      }

$(document).ready(function() {


  
            $('#myTable').DataTable({
              "pageLength": 30,
              "order": [[ 5, "asc" ]],
             "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
          
            // Update footer
            $( api.column( 4 ).footer() ).html(
                'PKR'+total 
            );
        }
            });
            
});
</script>

