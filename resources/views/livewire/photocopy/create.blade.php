<div>

    <button type="button" class="btn btn-success float-right" wire:click="createNewRecord" data-toggle="modal">Create New Photocopy</button>
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modelHeading"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
			</div>
			<div class="modal-body">
				<div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
				<form id="phtocopyForm" name="phtocopyForm"  class="form-horizontal">
					<input type="hidden" name="photocopy_id" id="photocopy_id">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label text-right">Photocopy Detail<span class="text_requried">*</span></label>
								<input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
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
</div> <!--End Modal  -->

@script
<script>
   $wire.on('openModal', ()=>{
     $('#ajaxModel').modal('show');
   })
</script>
@endscript
</div>

