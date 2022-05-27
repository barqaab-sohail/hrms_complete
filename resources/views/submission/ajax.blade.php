
<div class="addAjax">
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('submission.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Submission</button>
    </div>
    <div class="row">
        <div class="col-lg-2">
        @include('layouts.vButton.subButton')
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-lg-12 reducedCol">
            <div class="card-body">
                <form id="submissionForm" name="submissionForm" action="{{route('submission.store')}}"class="form-horizontal">
                    <input type="hidden" name="submission_id" id="submission_id">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Submission Type<span class="text_requried">*</span></label><br>
                                  <select  name="sub_type_id"  id="sub_type_id" class="form-control selectTwo" data-validation="required" readonly>
                                    @foreach($subTypes as $subType)
                                    <option value="{{$subType->id}}" {{(old("sub_type_id", $data->sub_type_id??'')==$subType->name? "selected" : "")}}>{{$subType->name}}</option>
                                    @endforeach   
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Division<span class="text_requried">*</span></label><br>
                                <select  name="sub_division_id"  id="sub_division_id" class="form-control selectTwo" data-validation="required" readonly>
                                @foreach($divisions as $division)
                                <option value="{{$division->code}}" {{(old("sub_division_id", $data->sub_division_id??'')==$division->id? "selected" : "")}}>{{$division->name}}</option>
                                @endforeach   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Submission No</label>
                                <input type="text"  name="submission_no" value="{{ old('submission_no', $data->submission_no??'') }}"  class="form-control" data-validation="required" readonly>    
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="hideDiv">
                                <label class="control-label text-right">EOI Reference</label>
                                <select  name="eoi_reference_id"  id="eoi_reference_id" class="form-control selectTwo" data-validation="required" readonly>
                                </select>   
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Client Name<span class="text_requried">*</span></label><br>
                          <select  name="client_id"  id="client_id" class="form-control selectTwo" data-validation="required">
                            @foreach($clients as $client)
                            <option value="{{$client->id}}" {{(old("sub_division_id", $data->client_id??'')==$client->id? "selected" : "")}}>{{$client->name}}</option>
                            @endforeach   
                          </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Project Name</label>
                        <input type="text"  name="project_name" value="{{ old('project_name', $data->project_name??'') }}"  class="form-control" data-validation="length"  data-validation-length="250">   
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Comments</label>
                        <input type="text" name="comments" id="comments" value="{{ old('comments') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Please enter Comments">    
                    </div>
                    
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save
                     </button>
                    </div>
                </form>
        	</div> <!-- end card body -->
        </div>   
    </div>
</div>
   
 

		  



