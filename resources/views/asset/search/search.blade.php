@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
<h3 class="text-themecolor">Search Asset</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">
		<h4 class="card-title">Search Asset</h4>
		<hr>
		<form id="assetSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('asset.result')}}" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="form-body">
				<div class="row">
					<div class="col-md-3">
						<!--/span 5-1 -->
						<div class="form-group row">
							<div class="col-md-12 required">
								<label class="control-label text-right">Name of Office</label><br>
								<select name="office_id" id="office_id" class="form-control searchSelect">
									<option value=""></option>
									@foreach($offices as $office)
									<option value="{{$office->id}}" {{(old("office_id")==$office->id? "selected" : "")}}>{{$office->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<!--/span 5-1 -->
						<div class="form-group row">
							<div class="col-md-12 required">
								<label class="control-label text-right">Class Name</label><br>
								<select name="class_id" id="class_id" class="form-control searchSelect">
									<option value=""></option>
									@foreach($classes as $class)
									<option value="{{$class->id}}" {{(old("class_id")==$class->id? "selected" : "")}}>{{$class->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group row">
							<div class="col-md-12">
								<label class="control-label text-right">Sub Classes<span class="text_requried">*</span></label>
								<select name="as_sub_class_id" id="as_sub_class_id" class="form-control selectTwo" data-placeholder="First Select Class">
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<!--/span 5-1 -->
						<div class="form-group row">
							<div class="col-md-12 required">
								<label class="control-label text-right">Name Employee</label><br>
								<select name="hr_employee_id" id="hr_employee_id" class="form-control searchSelect">
									<option value=""></option>
									@foreach($employees as $employee)
									<option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->full_name}} - {{$employee->designation??''}}</option>
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


			</div>
		</div>

	</div>
</div>


<script>
	$(document).ready(function() {
		$('.fa-spinner').hide();
		$('select').select2();
		formFunctions();
		
		//Add Sub_Class
		$('#class_id').change(function() {
			var cid = $(this).val();
			if (cid) {
				$.ajax({
					type: "get",
					url: "{{url('hrms/asset/sub_classes')}}" + "/" + cid,

					success: function(res) {

						if (res) {
							$("#as_sub_class_id").empty();
							$("#as_sub_class_id").append('<option value="">Select Sub Classes</option>');
							$.each(res, function(key, value) {
								$("#as_sub_class_id").append('<option value="'+key+'">'+value+'</option>');

							});
							$('#as_sub_class_id').select2('destroy');
							$('#as_sub_class_id').select2({
								width: "100%",
								theme: "classic"
							});

						}
					}

				}); //end ajax
			} else {
				$("#as_sub_class_id").empty();
			}
		});

		//submit function
		$("#assetSearch").submit(function(e) {

			e.preventDefault();
			$('.fa-spinner').show();
			var url = $(this).attr('action');

			//refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
			$.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
				var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

				if (token) {
					return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
				}
			});

			// ajax request
			$.ajax({
				url: url,
				method: "GET",
				data: $('#assetSearch').serialize(),
				//dataType:'JSON',
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {

					$('div.table-container').html(data);
					$('.fa-spinner').hide();

				},
				error: function(jqXHR, textStatus, errorThrown) {
					if (jqXHR.status == 401) {
						location.href = "{{route ('login')}}"
					}
					var test = jqXHR.responseJSON // this object have two more objects one is errors and other is message.

					var errorMassage = '';

					//now saperate only errors object values from test object and store in variable errorMassage;
					$.each(test.errors, function(key, value) {
						errorMassage += value + '<br>';
					});
					$('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');
					$('html,body').scrollTop(0);
					$('.fa-spinner').hide();

				} //end error
			}); //end ajax

		});


	});
</script>

@stop