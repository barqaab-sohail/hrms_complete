@if($documentIds->count()!=0)
<hr>
<div class="card">
	<div class="card-body">
		<h2 class="card-title">Employee Documentation Detail</h2>

		<div class="table-responsive m-t-40">

			<table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
				<thead>

					<tr>
						<th>Document Name</th>
						@can('hr document date')
						<th>Date</th>
						@endcan
						<th>View</th>
						<th>Copy Link</th>
						@can('hr edit documentation')
						<th class="text-center" style="width:5%">Edit</th>
						@endcan
						@can('hr delete documentation')
						<th class="text-center" style="width:5%">Delete</th>
						@endcan
					</tr>
				</thead>
				<tbody>
					@foreach($documentIds as $documentId)
					<tr>
						<td>{{$documentId->description}}</td>
						@can('hr document date')
						<td>{{isset($documentId->document_date)?date('M d, Y', strtotime($documentId->document_date)):''}}</td>
						@endcan
						@if($documentId->extension!='pdf')
						<td><img id="ViewIMG" src="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" href="{{asset(isset($documentId->file_name)?  'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30 /></td>
						@else
						<td><img id="ViewPDF" src="{{asset('Massets/images/document.png')}}" href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30 /></td>
						@endif
						<td class="copyLink" link="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: '') }}"><a><img src="{{asset('Massets/images/copyLink.png')}}" width=30 /></a></td>
						@can('hr edit documentation')
						<td class="text-center">
							@if(promotionDocument($documentId->id) && postingDocument($documentId->id))
							<a class="btn btn-success btn-sm" id="editDocument" @if (empty($documentId->hrDocumentationProject->id??'')) href="{{route('documentation.edit',$documentId->id)}}"@else href="" @endif data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white " disabled></i></a>
							@endif
						</td>
						@endcan

						@can('hr delete documentation')
						<td class="text-center">
							@if(promotionDocument($documentId->id) && postingDocument($documentId->id))
							<form id="deleteDocument{{$documentId->id}}" action="{{route('documentation.destroy',$documentId->id)}}" method="POST">
								@method('DELETE')
								@csrf
								<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href=data-toggle="tooltip" data-original-title="Delete" @if(!empty($documentId->hrDocumentationProject->id??'')) disabled @endif><i class="fas fa-trash-alt "></i></button>
							</form>
							@endif
						</td>
						@endcan

					</tr>
					@endforeach

				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var id = window.location.href.replace(/[^\d.]/g, '');
		//var table = '{{route("documentation.table",":id")}}'.replace(':id', id);
		$('.copyLink').click(function() {
			var text = $(this).attr('link').replace(" ", "%20");
			navigator.clipboard.writeText(text);
			alert('Link Copied');
		});

		$('.copyLink').hover(function() {
			$(this).css('cursor', 'pointer').attr('title', 'Click for Copy Link');
		}, function() {
			$(this).css('cursor', 'auto');
		});


		$('#myDataTable').DataTable({
			stateSave: false,
			dom: 'flrti',
			scrollY: "500px",
			scrollX: true,
			scrollCollapse: true,
			paging: false,
			bSort: false,
			fixedColumns: {
				leftColumns: 1,
				rightColumns: 2
			}
		});

		//function view from list table
		$(function() {

			$('#ViewPDF, #ViewIMG').EZView();
		});




		$("form").submit(function(e) {
			e.preventDefault();
		});

		$('a[id^=editDocument]').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			if (url != '') {
				getAjaxData(url);
			} else {
				alert('you cannot edit external refernece');
			}

		});


		$("form[id^=deleteDocument]").submit(function(e) {
			e.preventDefault();
			var url = $(this).attr('action');
			$('.fa-spinner').show();

			submitForm(this, url);
			resetForm();
			refreshTable(table);

		});
	});
</script>
@endif