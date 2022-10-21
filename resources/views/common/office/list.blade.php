@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<!-- Model -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modelHeading"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
			</div>
			<div class="modal-body">
				<div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
				<form id="officeForm" name="officeForm" action="{{route('office.store')}}" class="form-horizontal">
					<input type="hidden" name="office_id" id="office_id">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label text-right">Office Name<span class="text_requried">*</span></label>
								<input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">Establish Date</label><br>
								<input type="text" id="submission_date" name="submission_date" value="{{ old('submission_date') }}" class="form-control date_input" readonly>
								<br>
								<i class="fas fa-trash-alt text_requried"></i>
							</div>
						</div>

						<div class="col-md-7">
							<div class="form-group">
								<label class="control-label text-right">Address</label>
								<input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control" placeholder="Please enter Office Address">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">Country<span class="text_requried">*</span></label>
								<select name="country_id" id="country_id" class="form-control selectTwo" data-validation="required">
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">State<span class="text_requried">*</span></label><br>
								<select name="state_id" id="state_id" class="form-control selectTwo" data-validation="required">
								</select>

							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">City<span class="text_requried">*</span></label>
								<select name="city_id" id="city_id" class="form-control selectTwo" data-validation="required">
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">Status<span class="text_requried">*</span></label>
								<select name="is_active" id="is_active" class="form-control selectTwo" data-validation="required">
									<option></option>
									<option value="1">Working</option>
									<option value="0">Closed</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label text-right">Contract Person<span class="text_requried">*</span></label>
								<select name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo" data-validation="required">

								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2 phone" id='phone_1'>
							<input type="hidden" name="office_phone_id[]">
							<div class="form-group">
								<label class="control-label text-right">Phone Number</label>
								<input type="text" name="phone_no[]" value="{{ old('phone_no') }}" class="form-control prc_1">
								<button type="button" name="add" id="add" class="btn btn-success add">+</button>
							</div>
						</div>
					</div>

					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		formFunctions();
	</script>
</div>
<!--End Modal  -->
<style>
	.modal-dialog {
		max-width: 80%;
		display: flex;
	}

	.ui-timepicker-container {
		z-index: 1151 !important;
	}
</style>
<!-- End Modal -->
<div class="card">
	<div class="card-body">
		<button type="button" class="btn btn-success float-right" id="createOffice" data-toggle="modal">Add Office</button>
		<h4 class="card-title" style="color:black">List of Submissions</h4>
		<div class="table-responsive m-t-40">
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="width:15%">Office Name</th>
						<th style="width:35%">Address</th>
						<th style="width:15%">Contact Person</th>
						<th style="width:20%">Phone Number</th>
						<th style="width:5%">Status</th>
						<th style="width:5%">Edit</th>
						@role('Super Admin')
						<th style="width:5%">Delete</th>
						@endrole
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		//Dynamic add Phone

		// Add new element
		$("#add").unbind().click(function() {

			// Finding total number of elements added
			var total_element = $(".phone").length;

			// last <div> with element class id
			var lastid = $(".phone:last").attr("id");
			var split_id = lastid.split("_");
			var nextindex = Number(split_id[1]) + 1;
			var max = 5;
			// Check total number elements
			if (total_element < max) {
				//Clone Phone div and copy 
				var clone = $("#phone_1").clone();
				clone.prop('id', 'phone_' + nextindex).find('input:text').val('');
				clone.find("#add").html('X').prop("class", "btn btn-danger remove remove_phone");
				clone.insertAfter("div.phone:last");
				clone.find('input[name="office_phone_id[]"]').val('');
			}

		});
		// Remove element
		$(document).on("click", '.remove_phone', function() {
			$(this).closest(".phone").remove();
		});

		$(document).on("keyup", '.prc_1', function() {
			// skip for arrow keys
			if (event.which >= 37 && event.which <= 40) return;
			// format number
			$(this).val(function(index, value) {
				return value.replace(/\D/g, "");
			});
		});

		$(function() {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			var table = $('#myTable').DataTable({
				processing: true,
				serverSide: true,
				"aaSorting": [],
				ajax: {
					url: "{{ route('office.index') }}",
				},
				columns: [{
						data: 'name',
						name: 'name'
					},
					{
						data: 'address',
						name: 'address'
					},
					{
						data: 'establish_date',
						name: 'establish_date'
					},
					{
						data: 'phone_no',
						name: 'phone_no'
					},
					{
						data: 'is_active',
						name: 'is_active'
					},
					{
						data: 'edit',
						name: 'edit',
						orderable: false,
						searchable: false
					},
					@role('Super Admin') {
						data: 'delete',
						name: 'delete',
						orderable: false,
						searchable: false
					}
					@endrole
				]
			});

			$('#createOffice').click(function(e) {
				$('input[name="phone_no[]"]').eq(0).val('');
				$('input[name="phone_no[]"]').eq(1).val('');
				$('input[name="phone_no[]"]').eq(2).val('');
				$('input[name="phone_no[]"]').eq(3).val('');
				$('input[name="phone_no[]"]').eq(4).val('');
				$("[id^=phone_2]").remove();
				$("[id^=phone_3]").remove();
				$("[id^=phone_4]").remove();
				$("[id^=phone_5]").remove();
				$("#state_id").empty();
				$("#city_id").empty();
				$('#json_message_modal').html('');
				$.get("{{ url('hrms/misc/office/create') }}", function(data) {

					$("#country_id").empty();
					$("#country_id").append('<option value="">Select Country</option>');
					$.each(data.countries, function(key, value) {
						$("#country_id").append('<option value="' + value.id + '">' + value.name + '</option>');
					});
					$('#office_id').val("");
					$('#officeForm').trigger("reset");
					$('#ajaxModel').modal('show');
				});
			});
			$('body').unbind().on('click', '.editOffice', function() {
				var office_id = $(this).data('id');
				$(".remove_phone").trigger('click');
				$('input[name="phone_no[]"]').eq(0).val('');
				$("#state_id").empty();
				$("#city_id").empty();
				$('#json_message_modal').html('');
				$.get("{{ url('hrms/misc/office') }}" + '/' + office_id + '/edit', function(data) {

					$.get("{{ url('hrms/misc/office/create') }}", function(data) {
						$("#country_id").empty();
						$("#country_id").append('<option value="">Select Country</option>');
						$.each(data.countries, function(key, value) {
							$("#country_id").append('<option value="' + value.id + '">' + value.name + '</option>');
						});
					}).done(function() {
						$('#country_id').val(data.country_id);
						$('#country_id').trigger('change');
					});

					$('#modelHeading').html("Edit Office");
					$('#saveBtn').val("edit-Office");
					$('#office_id').val(data.id);
					$('#name').val(data.name);
					$('#establish_date').val(data.establish_date);
					$('#address').val(data.address);
					$('#is_active').val(data.is_active).change();
					if (data.office_phones.length > 0) {
						$.each(data.office_phones, function(index, value) {
							if (index != 0) {
								$("#add").trigger('click');
							}
							$('input[name="office_phone_id[]"]').eq(index).val(value.id);
							$('input[name="phone_no[]"]').eq(index).val(value.phone_no);
						});
					}
					setTimeout(function() {
						$('#state_id').val(data.state_id);
						$('#state_id').trigger('change');
					}, 1000);
					setTimeout(function() {
						$('#city_id').val(data.city_id);
						$('#city_id').trigger('change');
					}, 2000);

				}).done(function() {

					$('#ajaxModel').modal('show');
				})
			});

			//get states through ajax
			$('#country_id').change(function() {
				var cid = $(this).val();
				if (cid) {
					$.ajax({
						type: "get",
						url: "{{url('country/states')}}" + "/" + cid,
						success: function(res) {
							if (res) {
								$("#state_id").empty();
								$("#city_id").empty();
								$("#state_id").append('<option value="">Select State</option>');
								$.each(res, function(key, value) {
									$("#state_id").append('<option value="' + key + '">' + value + '</option>');

								});
								$('#state_id').select2('destroy');
								$('#state_id').select2();
							}
						}
					}); //end ajax
				}
			});
			//get cities through ajax
			$('#state_id').change(function() {
				var sid = $(this).val();
				if (sid) {
					$.ajax({
						type: "get",
						url: "{{url('country/cities')}}" + "/" + sid,
						success: function(res) {
							if (res) {
								$("#city_id").empty();
								$("#city_id").append('<option value="">Select City</option>');
								$.each(res, function(key, value) {
									$("#city_id").append('<option value="' + key + '">' + value + '</option>');
								});
								$('#city_id').select2('destroy');
								$('#city_id').select2();
							}
						}
					});
				}
			});

			$('#saveBtn').unbind().click(function(e) {
				$(this).attr('disabled', 'ture');
				//submit enalbe after 3 second
				setTimeout(function() {
					$('.btn-prevent-multiple-submits').removeAttr('disabled');
				}, 3000);

				e.preventDefault();
				$(this).html('Save');

				$.ajax({
					data: $('#officeForm').serialize(),
					url: "{{ route('office.store') }}",
					type: "POST",
					dataType: 'json',
					success: function(data) {
						$('#ajaxModel').modal('hide');
						$('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.message + '</strong></div>');
						table.draw();
					},
					error: function(data) {
						var errorMassage = '';
						$.each(data.responseJSON.errors, function(key, value) {
							errorMassage += value + '<br>';
						});
						$('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

						$('#saveBtn').html('Save Changes');
					}
				});
			});

			$('body').on('click', '.deleteOffice', function() {
				var office_id = $(this).data("id");
				var con = confirm("Are You sure want to delete !");
				if (con) {
					$.ajax({
						type: "DELETE",
						url: "{{ route('office.store') }}" + '/' + office_id,
						success: function(data) {
							table.draw();
							if (data.error) {
								$('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
							}

						},
						error: function(data) {

						}
					});
				}
			});
		}); // end function

	}); //End document ready function
</script>

</div>

@stop