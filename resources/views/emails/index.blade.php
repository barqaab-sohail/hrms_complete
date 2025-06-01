@extends('layouts.master.master')
@section('title', 'Email List')
@section('Heading')
<h3 class="text-themecolor">List of Emails</h3>
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
				<form id="emailForm" name="emailForm" action="{{route('emails.store')}}" class="form-horizontal">
					<input type="hidden" name="email_id" id="email_id">
					<div class="row">
						
						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label text-right">Email</label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" 
                    				name="email" id="email" value="{{ old('email') }}" required>
                            </div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label text-right">Space Allocation</label>
								<input type="number" class="form-control @error('total_space') is-invalid @enderror" 
									name="total_space" id="total_space"{{ old('total_space') }}>	
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">Space Unit</label>
								<select class="form-control @error('space_unit') is-invalid @enderror" 
									name="space_unit" id="space_unit" required>
									<option value="">Select Unit</option>
									<option value="MB" {{ old('space_unit') == 'MB' ? 'selected' : '' }}>MB</option>
									<option value="GB" {{ old('space_unit') == 'GB' ? 'selected' : '' }}>GB</option>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label text-right">Is Active</label>
								<div class="form-check mt-2">
									<input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
										name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
									<label class="form-check-label" for="is_active">Active</label>
									
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						
						<!-- Description Field -->
						
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label text-right">Description</label>
								<input class="form-control @error('description') is-invalid @enderror" 
									name="description" id="description"{{ old('description') }}>	
							</div>
						</div>
					</div>
					<div class="row">
						<!-- Emailable Type Selection -->
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label text-right">Email For</label>
								<select id="emailable_type" class="form-control @error('emailable_type') is-invalid @enderror" 
                                    name="emailable_type" required>
                                    <option value="">Select Type</option>
                                    <option value="employee" {{ old('emailable_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="project" {{ old('emailable_type') == 'project' ? 'selected' : '' }}>Project</option>
									<option value="department" {{ old('emailable_type') == 'department' ? 'selected' : '' }}>Department</option>
                                </select>
                            </div>
						</div>
						 <!-- Emailable ID Selection (dynamic based on type) -->
						<div class="col-md-9">
							<div class="form-group">
								<label class="control-label text-right">Select</label>
								<select id="emailable_id" class="form-control select2 @error('emailable_id') is-invalid @enderror" 
                                    name="emailable_id" required disabled>
                                    <option value="">First select type above</option>
                                </select>
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
		<button type="button" class="btn btn-success float-right" id="createEmail" data-toggle="modal">Add Email</button>
		<h4 class="card-title" style="color:black">List of Emails</h4>
		<div class="table-responsive m-t-40">
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Email</th>
						<th>Total Space</th>
                        <th>Status</th>
                        <th>Email Used By</th>
                        <th>Email Model</th>
						<th>Description</th>
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
@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const typeSelect = document.getElementById('emailable_type');
		const idSelect = document.getElementById('emailable_id');
		
		typeSelect.addEventListener('change', function() {
			// Clear and disable the ID select until we load options
			idSelect.innerHTML = '<option value="">Loading...</option>';
			idSelect.disabled = true;
			
			if (!this.value) {
				idSelect.innerHTML = '<option value="">First select type above</option>';
				return;
			}
			
			const baseUrl = "{{ url('/emails/type') }}";
			// Fetch the appropriate items based on type
			fetch(`${baseUrl}/${this.value}`)
			.then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
	.then(data => {
        idSelect.innerHTML = '<option value="">Select ' + this.value + '</option>';
        
        // Handle both array and object responses
        if (Array.isArray(data)) {
            // If array format (unlikely based on your backend)
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.text || item.name;
                idSelect.appendChild(option);
            });
        } else if (typeof data === 'object' && data !== null) {
            // Handle object format (what your backend actually returns)
            Object.entries(data).forEach(([id, text]) => {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = text;
                idSelect.appendChild(option);
            });
        }
        
        idSelect.disabled = false;
    })
				.catch(error => {
					idSelect.innerHTML = '<option value="">Error loading options</option>';
					console.error('Error:', error);
				});
		});
	});
	</script>
@endpush
<script>
	$(document).ready(function() {
	
	// Consolidated fetch function
function fetchEmailableOptions(type) {
    const idSelect = document.getElementById('emailable_id');
    idSelect.innerHTML = '<option value="">Loading...</option>';
    idSelect.disabled = true;

    if (!type) {
        idSelect.innerHTML = '<option value="">First select type above</option>';
        return Promise.resolve();
    }

    const baseUrl = "{{ url('/emails/type') }}";
    return fetch(`${baseUrl}/${type}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            idSelect.innerHTML = '<option value="">Select ' + type + '</option>';
            
            if (Array.isArray(data)) {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.text || item.name;
                    idSelect.appendChild(option);
                });
            } else if (typeof data === 'object' && data !== null) {
                Object.entries(data).forEach(([id, text]) => {
                    const option = document.createElement('option');
                    option.value = id;
                    option.textContent = text;
                    idSelect.appendChild(option);
                });
            }
            
            idSelect.disabled = false;
            return data; // Return data for chaining if needed
        })
        .catch(error => {
            idSelect.innerHTML = '<option value="">Error loading options</option>';
            console.error('Error:', error);
            throw error; // Re-throw for error handling
        });
}

// Set up change event handler
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('emailable_type');
    
    typeSelect.addEventListener('change', function() {
        fetchEmailableOptions(this.value);
    });
});



		$('.select2').select2({
        width: "100%",
        theme: "classic",

			errorPlacement: function (error, element) {
				if (element.parent(".input-group").length) {
					error.insertAfter(element.parent()); // radio/checkbox?
				} else if (element.hasClass("select2")) {
					error.insertAfter(element.next("span")); // select2
				} else {
					error.insertAfter(element); // default
				}
			},
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
				responsive: true,
				"lengthMenu": [
					[10, 25, 50, -1],
					[10, 25, 50, "All"]
				],
				"pageLength": 10,
				dom: 'Blfrtip',
				buttons: [{
						extend: 'copyHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4,5]
						}
					},
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4,5]
						}
					},
					{
						extend: 'pdfHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4,5]
						}
					}, {
						extend: 'csvHtml5',
						exportOptions: {
							columns: [0, 1, 2, 3, 4,5]
						}
					},
				],

				"aaSorting": [],
				ajax: {
					url: "{{ route('emails.create') }}",
				},
				columns: [{
						data: 'email',
						name: 'email'
					},
					{
						data: 'total_space',
						name: 'total_space',
					},
                   
                    {
						data: 'is_active',
						name: 'is_active',
					},
                    {
						data: 'emailable_id',
						name: 'emailable_id'
					},
                    {
						data: 'emailable_type',
						name: 'emailable_type'
					},
					{
						data: 'description',
						name: 'description',
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

			$('#createEmail').click(function(e) {
				$('#json_message_modal').html('');
				$('#email_id').val("");
				$("#emailable_id").val('').trigger('change');
				$('#modelHeading').html("Create New Email");
				$('#emailForm').trigger("reset");
	
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
					data: $('#emailForm').serialize(),
					url: "{{ route('emails.store') }}",
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
            
			// Edit email handler
$('body').on('click', '.editEmail', function() {
    var email_id = $(this).data('id');
    
    $('#json_message_modal').html('');
    $.get("{{ url('emails') }}" +'/' + email_id +'/edit', function(data) {
        $('#modelHeading').html("Edit Email");
        $('#saveBtn').val("edit-email");
        $('#ajaxModel').modal('show');
        
        // Fill basic fields
        $('#email_id').val(data.id);
        $('#email').val(data.email); 
        $('#description').val(data.description);
        $('#is_active').prop('checked', data.is_active);
		$('#total_space').val(data.total_space);
		$('#space_unit').val(data.space_unit).trigger('change');
        
        // Handle emailable_type and emailable_id
        var emailableType = data.emailable_type;
        var emailableId = data.emailable_id;
        
        if (emailableType) {
            // Use the shared fetch function
            fetchEmailableOptions(emailableType).then(function() {
                $('#emailable_type').val(emailableType);
                $('#emailable_id').val(emailableId).trigger('change');
                $('#emailable_id').prop('disabled', false);
            });
        } else {
            $('#emailable_type').val('');
            $('#emailable_id').html('<option value="">First select type above</option>');
            $('#emailable_id').prop('disabled', true);
        }
    }).fail(function(xhr, status, error) {
        console.error("Error loading email data:", error);
    });
});

			$('body').on('click', '.deleteEmail', function() {
				var email_id = $(this).data("id");
				var con = confirm("Are You sure want to delete !");
				if (con) {
					$.ajax({
						type: "DELETE",
						url: "{{ route('emails.store') }}" + '/' + email_id,
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
@stop