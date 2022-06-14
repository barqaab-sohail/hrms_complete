
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('submission.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Submission</button>
    </div>

         
    <div class="card-body">
        <form id= "formSubmission" method="post" action="{{route('submission.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Submission Information</h3>
               
                <hr class="m-t-0 m-b-40">

                     <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Submission Type<span class="text_requried">*</span></label><br>
                                  <select  name="sub_type_id"  id="sub_type_id" class="form-control selectTwo" data-validation="required" readonly>
                                    @foreach($subTypes as $subType)
                                    <option value="{{$subType->id}}" {{(old("sub_type_id", $data->sub_type_id??'')==$subType->id? "selected" : "")}}>{{$subType->name}}</option>
                                    @endforeach   
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Division<span class="text_requried">*</span></label><br>
                                <select  name="sub_division_id"  id="sub_division_id" class="form-control selectTwo" data-validation="required" readonly>
                                @foreach($divisions as $division)
                                <option value="{{$division->code}}" {{(old("sub_division_id", $data->sub_division_id??'')==$division->id? "selected" : "")}}>{{$division->name}}</option>
                                @endforeach   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Submission No</label>
                                <input type="text"  name="submission_no"  id="submission_no"  value="{{ old('submission_no', $data->submission_no??'') }}"  class="form-control" data-validation="required" readonly>    
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label text-right">Client Name<span class="text_requried">*</span></label><br>
                                <select  name="client_id"  id="client_id" class="form-control selectTwo" data-validation="required">
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}" {{(old("sub_division_id", $data->client_id??'')==$client->id? "selected" : "")}}>{{$client->name}}</option>
                                    @endforeach   
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group hide">
                        <label class="control-label text-right">EOI Reference</label>
                        <select  name="eoi_reference_id"  id="eoi_reference_id" class="form-control selectTwo">
                        <option></option>
                        @foreach($eoiReferences as $eoiReference)
                        <option value="{{$eoiReference->id}}" {{(old("eoi_reference_id", $data->subEoiReference->eoi_reference_id??'')==$eoiReference->id? "selected" : "")}}>{{$eoiReference->submission_no}}-{{$eoiReference->project_name}}</option>
                        @endforeach   
                        </select>   
                    </div>
                   
                    <div class="form-group">
                        <label class="control-label text-right">Project Name</label>
                        <input type="text"  name="project_name" value="{{ old('project_name', $data->project_name??'') }}"  class="form-control">   
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Current Status</label>
                                <select  name="sub_status_id"  id="sub_status_id" class="form-control selectTwo">
                                <option></option>
                                @foreach($subStatuses as $subStatus)
                                <option value="{{$subStatus->id}}" {{(old("sub_status_id", $data->subStatusType->id??'')==$subStatus->id? "selected" : "")}}>{{$subStatus->name}}</option>
                                @endforeach   
                                </select>  
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Financial Cost Type</label>
                                <select  name="sub_financial_type_id"  id="sub_financial_type_id" class="form-control selectTwo">
                                <option></option>
                                @foreach($subFinancialTypes as $subFinancialType)
                                <option value="{{$subFinancialType->id}}" {{(old("sub_financial_type_id", $data->subFinancialType->id??'')==$subFinancialType->id? "selected" : "")}}>{{$subFinancialType->name}}</option>
                                @endforeach   
                                </select>  
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">CV Format</label>
                                <select  name="sub_cv_format_id"  id="sub_cv_format_id" class="form-control selectTwo">
                                <option></option>
                                @foreach($subCvFormats as $subCvFormat)
                                <option value="{{$subCvFormat->id}}" {{(old("sub_cv_format_id", $data->subCvFormat->id??'')==$subCvFormat->id? "selected" : "")}}>{{$subCvFormat->name}}</option>
                                @endforeach   
                                </select>  
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Evaluation Type</label>
                                <select  name="sub_evaluation_type_id"  id="sub_evaluation_type_id" class="form-control selectTwo">
                                <option></option>
                                @foreach($subEvaluationTypes as $subEvaluationType)
                                <option value="{{$subEvaluationType->id}}" {{(old("sub_evaluation_type_id", $data->subDescription->sub_evaluation_type_id??'')==$subEvaluationType->id? "selected" : "")}}>{{$subEvaluationType->name}}</option>
                                @endforeach   
                                </select>  
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                       
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Technical Opening Date</label>
                                <input type="text" id="technical_opening_date" name="technical_opening_date" value="{{ old('technical_opening_date', $data->subDescription->technical_opening_date??'') }}" class="form-control date_input" readonly>     
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                               
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Financial Opening Date</label>
                                <input type="text" id="financial_opening_date" name="financial_opening_date" value="{{ old('financial_opening_date', $data->subDescription->financial_opening_date??'') }}" class="form-control date_input" readonly>     
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                               
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Total Marks</label>
                                <input type="text" id="total_marks" name="total_marks" value="{{ old('total_marks', $data->subDescription->total_marks??'') }}" class="form-control" >
                                  
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-right">Passing Marks</label>
                                <input type="text" id="passing_marks" name="passing_marks" value="{{ old('passing_marks', $data->subDescription->passing_marks??'') }}" class="form-control" >
                                  
                            </div>
                        </div>
                        <div class="col-md-2 QCB">
                            <div class="form-group">
                                <label class="control-label text-right">Technical Weightage</label>
                                <input type="text" id="technical_weightage" name="technical_weightage" value="{{ old('technical_weightage', $data->subDescription->technical_weightage??'') }}" class="form-control">
                                
                            </div>
                        </div>
                        <div class="col-md-2 QCB">
                            <div class="form-group">
                                <label class="control-label text-right">Financial Weightage</label>
                                <input type="text" id="financial_weightage" name="financial_weightage" value="{{ old('financial_weightage', $data->subDescription->financial_weightage??'') }}" class="form-control" >
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-right">Comments</label>
                                <input type="text" name="comments" id="comments" value="{{ old('comments', $data->comments??'') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Please enter Comments">    
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save
                     </button>
                    </div>
        </form>
	</div> <!-- end card body -->    


<script>
    if("{{$data->subDescription->sub_evaluation_type_id??''}}"!=1){
        $('.QCB').hide();
    }

    $("#sub_evaluation_type_id").change(function(){

        if($(this).val() == 1){
            $('.QCB').show();
        }else{
            $('#technical_weightage').val('');
            $('#financial_weightage').val('');
            $('.QCB').hide();
        }

    });


    $('#sub_type_id').change(function(){
        var subTypeId = $(this).val();
        if(subTypeId == 3){
            $('.hide').show();
        }else{
            $('.hide').hide();
        }
        

        $.get("{{ url('hrms/submission/submissionNo') }}"+"/"+subTypeId, function (data) {
                    $("#submission_no").val(data);
            
        });
    });
</script>	  



