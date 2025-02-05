@extends('layouts.master.master')
@section('title', $photocopy->name)
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
				<form id="recordForm" name="recordForm" action="{{route('photocopy_record.store')}}" class="form-horizontal">
					<input type="hidden" name="record_id" id="record_id">
					<input type="hidden" name="photocopy_id" value="{{$photocopy->id}}">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label text-right">Date</label>
								<input type="text" id="date" name="date" value="{{ old('date') }}" class="form-control date_input" data-validation="required" readonly>

							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">Reading</label>
								<input type="number" name="reading" id="reading" value="{{ old('reading') }}" class="form-control">
							</div>
						</div>
						<div class="col-md-7">
							<div class="form-group">
								<label class="control-label text-right">Remarks</label>
								<input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control">
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

@include('layouts.vButton.photocopyButton')

<div class="row justify-content-end">
	<div class="col-lg-12 reducedCol">
		<div class="card">
			<div class="card-body">
				<button type="button" class="btn btn-success float-right" id="createRecordy" data-toggle="modal">Add Record</button>
				<h2 class="card-title" style="color:black">{{$photocopy->name}} Records</h2>
				<div class="table-responsive m-t-40">
					<table id="myTable" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th style="width:10%">Date</th>
								<th style="width:10%">Reading</th>
								<th style="width:60%">Remarks</th>
								<th style="width:10%">Total Copies</th>
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
	</div>
</div>

<script>
	$(document).ready(function() {

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
				dom: 'Blfrtip',
				buttons: [{
						extend: 'copyHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3]
						}
					},
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3]
						}
					},
					{
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3]
						}
					}, {
						extend: 'csvHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3]
						}
					},
				],
				ajax: {
					url: "{{ route('photocopy_record.show', $photocopy->id) }}",
				},
				columns: [{
						data: 'date',
						name: 'date'
					},
					{
						data: 'reading',
						name: 'reading'
					},

					{
						data: 'remarks',
						name: 'remarks'
					},
					{
						data: 'copies',
						name: 'copies'
					},

					{
						data: 'Edit',
						name: 'Edit',
						orderable: false,
						searchable: false
					},
					@role('Super Admin') {
						data: 'Delete',
						name: 'Delete',
						orderable: false,
						searchable: false
					}
					@endrole
				]
			});

			$('#createRecordy').click(function(e) {
				$('#json_message_modal').html('');
				$('#recordForm').trigger("reset");

				$('#ajaxModel').modal('show');

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
					data: $('#recordForm').serialize(),
					url: "{{ route('photocopy_record.store') }}",
					type: "POST",
					dataType: 'json',
					success: function(data) {
						$('#ajaxModel').modal('hide');
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
			$('body').unbind().on('click', '.editRecord', function() {
				var record_id = $(this).data('id');

				$('#json_message_modal').html('');
				$.get("{{ url('photocopy_record') }}" + '/' + record_id + '/edit', function(data) {
					console.log(data);
					$('#modelHeading').html("Edit Photocopy");
					$('#saveBtn').val("edit-Photocopy");
					$('#ajaxModel').modal('show');
					$('#record_id').val(data.id);
					$('#date').val(data.date);
					$('#reading').val(data.reading);
					$('#remarks').val(data.remarks);
				})
			});

			$('body').on('click', '.deleteRecord', function() {
				var record_id = $(this).data("id");
				var con = confirm("Are You sure want to delete !");
				if (con) {
					$.ajax({
						type: "DELETE",
						url: "{{ route('photocopy_record.store') }}" + '/' + record_id,
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