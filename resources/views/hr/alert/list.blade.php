@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of HR Alerts</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">List of HR Alerts</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
				<thead>
				<tr>
					<th>Detail of Alerts</th>
					<th class="text-center"style="width:5%">Total</th> 
				</tr>
				</thead>
				
				<tbody>
					<tr>
						<td><a id="cnicExpiryDetail" href="{{route('hrAlert.cnicExpiryDetail')}}" style="color:grey" class="activeRow">CNIC Expired or Near to Expire Next 10 Days</a></td>
						<td>{{$totalCnicExpire}}</td>
					</tr>
					<tr>
						<td><a id="appointmentExpiryDetail" href="{{route('hrAlert.appointmentExpiry')}}" style="color:grey" class="activeRow">Appointment Contract Expired or Near to Expire Next 10 Days</a></td>
						<td>{{$appointmentExpiryTotal}}</td>
					</tr>
                    <tr>
                        <td><a id="drivingLicenceExpiryTotal" href="{{route('hrAlert.licenceExpiry')}}" style="color:grey" class="activeRow">Driver Licence Expired or Near to Expire Next 10 Days</a></td>
                        <td>{{$drivingLicenceExpiryTotal}}</td>
                    </tr>
                     <tr>
                        <td><a id="pecCardExpiry" href="{{route('hrAlert.pecCardExpiry')}}" style="color:grey" class="activeRow">PEC Card Expired or Near to Expire Next 10 Days</a></td>
                        <td>{{$pecCardExpiryTotal}}</td>               
                    </tr>
				
				</tbody>
			</table>
		</div>
		 @include('hr.alert.alertDetailModal')
	</div>
</div>


<script>
$(document).ready(function() {


            $('#myTable').DataTable({
                stateSave: false,
        
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1]
                        }
                    },
                ],
                scrollY:        "300px",
      			scrollX:        true,
        		scrollCollapse: true,
        		paging:         false,
        		fixedColumns:   {
            		leftColumns: 1,
            		rightColumns:2
        		}
            });
            
        });
</script>

@stop