@if($hrMonthlyReportProjects->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
		
			<h2 class="card-title">Projects</h2>
			
			<div class="table-responsive">
				
				<table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
					
					<tr >
						<th>Month and Year</th>
						<th>Name of Project</th>
						
					
						
					</tr>
					</thead>
					<tbody>
						@foreach($hrMonthlyReportProjects as $hrMonthlyReportProject)
							<tr>
								<td>{{$hrMonthlyReportProject->hrMonthlyReport->month}} - {{$hrMonthlyReportProject->hrMonthlyReport->year}}</td>
								<td>{{$hrMonthlyReportProject->prDetail->name}}</td>
							</tr>
						@endforeach
					
					 
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<hr>  
@endif
<script>
$(document).ready(function() {
	



});
</script>
