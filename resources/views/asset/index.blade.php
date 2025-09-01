@extends('layouts.master.master')
@section('title', 'Assets List')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card">
	<div class="card-body">
		@if(isset($subClassId) && $subClassId)
			<h4 class="card-title" style="color:black">List of Assets - Filtered by Subclass</h4>
			<button onclick="window.history.back()" class="btn btn-secondary">
				<i class="fas fa-arrow-left"></i> Back
			</button>
		@else
			<h4 class="card-title" style="color:black">List of All Assets</h4>
		@endif

		<div class="table-responsive m-t-40">
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Id</th>
						<th>Asset Code</th>
						<th>Description</th>
						<th>Ownership</th>
						<th>Location/Allocation</th>
						<th>Image</th>
						<th class="text-center" style="width:5%">Edit</th>
						@can('asset delete record')
						<th class="text-center" style="width:5%">Delete</th>
						@endcan
					</tr>
				</thead>
			</table>
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

			// Determine the URL based on whether we're filtering by subclass
			var url = "{{ route('asset.loadData') }}";
			@if(isset($subClassId) && $subClassId)
				url = "{{ route('asset.loadSubclassData', $subClassId) }}";
			@endif

			var table = $('#myTable').DataTable({
				processing: true,
				serverSide: true,
				lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
				dom: 'Blfrtip',
				buttons: [{
						extend: 'copyHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						}
					},
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						}
					},
					{
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						}
					}, {
						extend: 'csvHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						}
					},
				],
				"aaSorting": [],
				ajax: {
					url: url,
				},
				columns: [{
						data: 'id',
						name: 'id'
					},
					{
						data: 'asset_code',
						name: 'asset_code'
					},
					{
						data: 'description',
						name: 'description'
					},
					{
						data: 'ownership',
						name: 'ownership'
					},
					{
						data: 'location',
						name: 'location'
					},
					{
						data: 'image',
						name: 'image'
					},
					{
						data: 'edit',
						name: 'edit',
						orderable: false,
						searchable: false
					},
					@can('asset delete record') {
						data: 'delete',
						name: 'delete',
						orderable: false,
						searchable: false
					}
					@endcan
				],
				"drawCallback": function(settings) {
					$("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
				},
				'columnDefs': [{
					"targets": 4,
					"className": "text-center",
				}, ],
			});

			$('body').on('click', '.deleteAsset', function() {
				var asset_id = $(this).data("id");
				var con = confirm("Are You sure want to delete !");
				if (con) {
					$.ajax({
						type: "DELETE",
						url: "{{ route('asset.store') }}" + '/' + asset_id,
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
	});
</script>

@stop