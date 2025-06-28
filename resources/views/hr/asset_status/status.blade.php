@extends('layouts.master.master')
@section('title', 'Employee Search')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card">
	<div class="card-body">
		<h4 class="card-title">Search <button type="button" id="resetForm" class="btn btn-danger float-right">Reset Search</button></h4>
		<hr>
		<form id="employeeSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('employee.asset.result')}}" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="form-body">
				<div class="row">
					<div class="col-md-6">
						<!--/span 5-1 -->
						<div class="form-group row">
							<div class="col-md-12 required">
								<label class="control-label text-right">Name of Employee</label><br>
								<select name="employee" id="employee" class="form-control searchSelect">
									<option value=""></option>
									@foreach($employees as $employee)
									<option value="{{$employee->id}}" {{(old("employee")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employeeCurrentDesignation->name??''}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-actions">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Search</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>

		<hr>
		<div class="row">
			<div class="col-md-12 table-container">
				<!-- Results will be displayed here -->
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.fa-spinner').hide();
		$('select').select2();
		formFunctions();

		// Reset Form Button Functionality
		$('#resetForm').click(function() {
			$('.searchSelect').val('').trigger('change');
			$('#json_message').html('');
			$('.form-group').removeClass('has-error');
			$('div.table-container').html('');
			$('html,body').scrollTop(0);
		});

		//submit function
		$("#employeeSearch").submit(function(e) {
			e.preventDefault();
			$('.fa-spinner').show();
			var url = $(this).attr('action');

			$.ajaxPrefilter(function(options, originalOptions, xhr) {
				var token = $('meta[name="csrf-token"]').attr('content');
				if (token) {
					return xhr.setRequestHeader('X-CSRF-TOKEN', token);
				}
			});

			$.ajax({
				url: url,
				method: "GET",
				data: $('#employeeSearch').serialize(),
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
				//console.log(data);
					// Process the response and display in card format
					var html = '';
					
					// Physical Assets Section - only show if there are physical assets
					if (data[0] && data[0].length > 0) {
						html += '<div class="card mb-4">';
						html += '<div class="card-header bg-primary""><h4 style="color: white !important;">Physical Assets</h4></div>';
						html += '<div class="card-body">';
						html += '<div class="row">';
						
						$.each(data[0], function(index, asset) {
							html += '<div class="col-md-6 mb-3">';
							html += '<div class="card h-100">';
							html += '<div class="card-header bg-info">';
							html += '<h5 class="card-title" style="color: white !important;">' + asset.asset.description + '</h5>';
							html += '</div>';
							html += '<div class="card-body">';
							
							// Asset Image - assuming the image path is available in asset.asset.image_path
							if (asset.asset.picture) {
								html += '<div class="text-center mb-3">';
								html += '<img src="' + asset.asset.picture + '" class="img-fluid rounded" style="max-height: 200px;" alt="Asset Image">';
								html += '</div>';
							}
							
							html += '<div class="row">';
							html += '<div class="col-md-6">';
							html += '<p class="card-text"><strong>Asset Code:</strong> ' + asset.asset.asset_code + '</p>';
							html += '<p class="card-text"><strong>Issued Date:</strong> ' + asset.date + '</p>';
							html += '</div>';
							html += '<div class="col-md-6">';
							html += '<p class="card-text"><strong>Sub Class:</strong> ' + (asset.asset.as_sub_class ? asset.asset.as_sub_class.name : 'N/A') + '</p>';
							html += '<p class="card-text"><strong>Status:</strong> ' + (asset.asset.status ? asset.asset.status : 'Active') + '</p>';
							html += '</div>';
							html += '</div>'; // close inner row
							html += '</div>'; // close card-body
							html += '</div>'; // close card
							html += '</div>'; // close col-md-6
						});
						
						html += '</div>'; // close row
						html += '</div>'; // close card-body
						html += '</div>'; // close card
					}
					
					// Email Assets Section - only show if there are email assets
					if (data[1] && data[1].length > 0) {
						html += '<div class="card mb-4">';
						html += '<div class="card-header bg-primary"><h4 style="color: white !important;">Email Assets</h4></div>';
						html += '<div class="card-body">';
						html += '<div class="row">';
						
						$.each(data[1], function(index, email) {
							html += '<div class="col-md-6 mb-3">';
							html += '<div class="card h-100">';
							html += '<div class="card-header bg-success">';
							html += '<h5 class="card-title" style="color: white !important;">Email Account</h5>';
							html += '</div>';
							html += '<div class="card-body">';
							html += '<div class="row">';
							html += '<div class="col-md-6">';
							html += '<p class="card-text"><strong>Email:</strong> ' + email.email + '</p>';
							html += '<p class="card-text"><strong>Storage:</strong> ' + email.total_space + ' ' + email.space_unit + '</p>';
							html += '</div>';
							html += '<div class="col-md-6">';
							html += '<p class="card-text"><strong>Status:</strong> ' + (email.is_active ? 'Active' : 'Inactive') + '</p>';
							html += '<p class="card-text"><strong>Created:</strong> ' + new Date(email.created_at).toLocaleDateString() + '</p>';
							html += '</div>';
							html += '</div>'; // close inner row
							html += '</div>'; // close card-body
							html += '</div>'; // close card
							html += '</div>'; // close col-md-6
						});
						
						html += '</div>'; // close row
						html += '</div>'; // close card-body
						html += '</div>'; // close card
					}
					
					// If no assets found
					if ((!data[0] || data[0].length === 0) && (!data[1] || data[1].length === 0)) {
						html += '<div class="alert alert-info">No assets found for this employee.</div>';
					}
					
					$('div.table-container').html(html);
					$('.fa-spinner').hide();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if (jqXHR.status == 401) {
						location.href = "{{route ('login')}}"
					}
					var test = jqXHR.responseJSON;
					var errorMassage = '';
					$.each(test.errors, function(key, value) {
						errorMassage += value + '<br>';
					});
					$('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');
					$('html,body').scrollTop(0);
					$('.fa-spinner').hide();
				}
			});
		});
	});
</script>

@stop