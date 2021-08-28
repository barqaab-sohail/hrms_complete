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
					<th class="text-center"style="width:5%">Detail</th>
				</tr>
				</thead>
				
				<tbody>
					<tr>
						<td>Employee CNIC Expired or Near to Expire Next 10 Days</td>
						<td>{{$totalCnicExpire}}</td> 
						<td class="text-center">
							<button type="button" name="edit" id="cnicExpiryDetail" href="{{route('hrAlert.cnicExpiryDetail')}}" class="edit btn btn-success btn-sm">Detail</button>
						</td>													
					</tr>
					<tr>
						<td>Employee Appointment Contract Expired or Near to Expire Next 10 Days</td>
						<td>{{$appointmentExpiryTotal}}</td> 
						<td class="text-center">
							<button type="button" name="edit" id="appointmentExpiryDetail" href="{{route('hrAlert.appointmentExpiry')}}" class="edit btn btn-success btn-sm">Detail</button>
						</td>													
					</tr>
                    <tr>
                        <td>Driver Licence Expired or Near to Expire Next 10 Days</td>
                        <td>{{$drivingLicenceExpiryTotal}}</td> 
                        <td class="text-center">
                            <button type="button" name="edit" id="drivingLicenceExpiryTotal" href="{{route('hrAlert.licenceExpiry')}}" class="edit btn btn-success btn-sm">Detail</button>
                        </td>                                                   
                    </tr>
                     <tr>
                        <td>PEC Card Expired or Near to Expire Next 10 Days</td>
                        <td>{{$pecCardExpiryTotal}}</td> 
                        <td class="text-center">
                            <button type="button" name="edit" id="pecCardExpiry" href="{{route('hrAlert.pecCardExpiry')}}" class="edit btn btn-success btn-sm">Detail</button>
                        </td>                                                   
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