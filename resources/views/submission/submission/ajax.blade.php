
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('submission.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Submission</button>
    </div>
         
    <div class="card-body">
        <form id= "formSubmission" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
         @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Submission Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="control-label text-right">Division<span class="text_requried">*</span></label>
                                    
                                    <select  name="sub_division_id" id="sub_division_id" class="form-control selectTwo" data-validation="required">
                                        <option value=""></option>
                                        @foreach($divisions as $division)
                                        <option value="{{$division->code}}" {{(old("sub_division_id", $data->sub_division_id??'')==$division->id? "selected" : "")}}>{{$division->name}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                            </div>
                        </div>
                         <!--/span-->
                        <div class="col-md-2" id="submission_type_div">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="control-label text-right">Submission Type<span class="text_requried">*</span></label>
                                    <select  name="sub_type_id" id="submission_type" class="form-control selectTwo" data-validation="required">
                                        <option value=""></option>
                                        @foreach($subTypes as $subType)
                                        <option value="{{$subType->id}}" {{(old("sub_type_id", $data->sub_type_id??'')==$subType->name? "selected" : "")}}>{{$subType->name}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                            </div>
                        </div>
                         <!--/span-->
                        <div class="col-md-2">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="control-label text-right">Submission No.<span class="text_requried">*</span></label>
                                    
                                    <input type="text"  name="submission_no" value="{{ old('submission_no', $data->submission_no??'') }}"  class="form-control exempted" data-validation="required" readonly>
                                    
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6"  id="eoi_reference_div">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="control-label text-right">EOI Reference</label>
                                    <select  name="eoi_reference"  id="eoi_reference_no" class="form-control selectTwo">
                                        <option value=""></option>
                                        @foreach($eoiReferences as $eoiReference)
                                        <option value="{{$eoiReference->id}}" {{(old("eoi_reference", $data->eoi_reference_no??'')==$eoiReference->id? "selected" : "")}}>{{$eoiReference->project_name}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                            </div>
                        </div>
              
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Name of Project<span class="text_requried">*</span></label><br>

                                <input type="text"  name="project_name" value="{{ old('project_name', $data->project_name??'') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Enter Name of Submission Project">
                            </div>
                        </div>
                    </div>
                                   
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Client Name<span class="text_requried">*</span></label>
                                    <select  name="client_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}" {{(old("client_id", $data->client_id??'')==$client->id? "selected" : "")}}>{{$client->name}}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">                                       
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Submission Date<span class="text_requried">*</span></label>
                                
                                <input type="text"  name="submission_date" value="{{ old('submission_date',$data->date->submission_date??'') }}" class="form-control date_input" data-validation="required" readonly>
                                
                                <br>
                                @can('submission edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Submission Time<span class="text_requried">*</span></label>
                                
                                <input type="text"  name="submission_time" id="submission_time" value="{{ old('submission_time',$data->date->submission_time??'') }}" class="form-control time_input" data-validation="required" readonly>
                                
                                <br>
                                @can('submission edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Submission Address<span class="text_requried">*</span></label>
                                
                                <input type="text"  name="address" value="{{ old('address', $data->address->address??'') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Enter Address where document submitted">
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Designation<span class="text_requried">*</span></label>
                                <input type="text"  name="designation" value="{{ old('designation',$data->address->designation??'') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Designation of Client Representative">

                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Phone No.</label>
                                <input type="text" name="landline" value="{{ old('landline',$data->contact->landline??'') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
                               
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Fax No.</label>
                                <input type="text" name="fax" value="{{ old('fax', $data->contact->fax??'') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Mobile No.</label>
                
                               <input type="text" name="mobile" id="mobile" pattern="[0-9.-]{12}" title= "11 digit without dash" value="{{ old('mobile', $data->contact->mobile??'') }}" data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0345-0000000">                                             
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Email</label>
                
                                <input type="email" name="email" value="{{ old('emal', $data->contact->email??'') }}" data-validation="length"  data-validation-length="max50" class="form-control" placeholder="Enter Email Address">
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
            </div> <!--/End Form Boday-->

            <hr>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit Submission</button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
	</div> <!-- end card body -->   


    <script>
        if($('#submission_type').val() == '3'){ // or this.value == 'volvo'
            $("#eoi_reference_div").show();
        }else{
            $("#eoi_reference_div").hide();
        }

        $('#submission_type').change(function(){
            if($('#submission_type').val() == '3'){ // or this.value == 'volvo'
                $("#eoi_reference_div").show();
            }else{
                $('#eoi_reference_no').val('').select2('val', 'All');
                $("#eoi_reference_div").hide();
            }
        });

    </script>
 

		  



