@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of Projects</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		
		<h4 class="card-title">Projects Invoice Detaill</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
					<tr>
						<th>Project No</th>
						<th>Project Name</th>
						<th>Client Name</th>
						<th>Total Cost</th>
						<th>Invoice Raised</th>
						<th>Payment Received</th>
						<th>Payment Pending</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2003</td>
						<td>220kv Shikarpur TL Prject</td>
						<td>NTDC</td>
						<td>143,000,000</td>
						<td>80,000,000</td>
						<td>50,000,000</td>
						<td>30,000,000</td>						
					</tr>
					<tr>
						<td>2004</td>
						<td>500kV Jamshoror TLl Prject</td>
						<td>NTDC</td>
						<td>89,000,000</td>
						<td>70,000,000</td>
						<td>40,000,000</td>
						<td>30,000,000</td>						
					</tr>
					<tr>
						<td>2005</td>
						<td>220kv Jhampir GS Prject</td>
						<td>NTDC</td>
						<td>34,000,000</td>
						<td>20,000,000</td>
						<td>15,000,000</td>
						<td>5,000,000</td>						
					</tr>
					<tr>
						<td>2006</td>
						<td>500KE KKI GS Prject</td>
						<td>KE</td>
						<td>176,000,000</td>
						<td>34,000,000</td>
						<td>14,000,000</td>
						<td>10,000,000</td>						
					</tr>
				
				</tbody>
				<tfoot>
            <tr>
                <th colspan="6" style="text-align:right">Total:</th>
                <th></th>
            </tr>
        </tfoot>
			</table>
		</div>
	</div>
</div>


<script>
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
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6 , { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 6 ).footer() ).html(
                '$'+pageTotal +' ( $'+ total +' total)'
            );
        }
            });
            
        });
</script>

@stop