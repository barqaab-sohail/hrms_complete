
        
    <div class="card-body">
       
                    
        <h3 class="box-title">Project Invoice Chart</h3>
        
        <hr class="m-t-0 m-b-40">

        <div class="row">
            <div id="invoice" style="width: 100%; height: 500px;"></div>
        </div><!--/End Row-->

        <div class="table-responsive m-t-40"> 
      <table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
        <thead>
          <tr>
            <th>Invoice No</th>
            <th>Invoice Date</th>
            <th>Invoice Amount</th>
            <th>Sales Tax</th>
            <th>Total Invoice</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2003</td>
            <td>January 07, 2021</td>
            <td>1,000,000</td>
            <td>160,000</td>
            <td>1,160,000</td>
            <td>Received</td>          
          </tr>
          <tr>
            <td>2004</td>
            <td>February 07, 2021</td>
            <td>1,000,000</td>
            <td>160,000</td>
            <td>1,160,000</td>
            <td>Received</td>           
          </tr>
          <tr>
            <td>2005</td>
            <td>March 07, 2021</td>
            <td>1,000,000</td>
            <td>160,000</td>
            <td>1,160,000</td>
            <td>Received</td>            
          </tr>
          <tr>
            <td>2006</td>
            <td>April 07, 2021</td>
            <td>1,000,000</td>
            <td>160,000</td>
            <td>1,160,000</td>
            <td>Pending</td>         
          </tr>
          <tr>
            <td>2007</td>
            <td>May 07, 2021</td>
            <td>1,000,000</td>
            <td>160,000</td>
            <td>1,160,000</td>
            <td>Pending</td>         
          </tr>
           <tr>
            <td>2008</td>
            <td>June 07, 2021</td>
            <td>1,000,000</td>
            <td>160,000</td>
            <td>1,160,000</td>
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
      
	</div> <!-- end card body --> 

<script>

    
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

        chart.draw(view, options);
      }

$(document).ready(function() {


  
            $('#myTable').DataTable({
         
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
 
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                'PKR'+pageTotal 
            );
        }
            });
            
});

</script>

