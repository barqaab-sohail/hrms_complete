<div class="row">
	<div class="col-md-2">
	<a type="submit" role="button" id="addPhotocopyRecord"  {{Request::is('*photocopy_record/*')?'style=background-color:#737373;color:white':'style=color:white'}} href="{{route('photocopy_record.show',$photocopy->id)}}" class="btn btn-success  dropdown-item">Records</a>
	</div>
	<div class="col-md-2">
	<a type="submit" role="button" id="addDocuments" {{Request::is('*photocopy_documents/*')?'style=background-color:#737373;color:white':'style=color:white'}} style="color:white" href="{{route('photocopy_documents.show',$photocopy->id)}}" class="btn btn-success  dropdown-item ">Documents</a>
	</div>
</div>