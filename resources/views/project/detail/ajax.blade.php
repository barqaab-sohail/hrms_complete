
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('project.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Projects</button>
    </div>

         
    <div class="card-body">
        <form id= "formProject" method="post" action="{{route('project.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Project Information</h3>
               
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Name of Project<span class="text_requried">*</span></label><br>

                               	<input type="text"  name="name" value="{{ old('name', $data->name??'') }}"  class="form-control exempted" data-validation="required length" data-validation-length="max510" placeholder="Enter Name of Project">
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
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Commencement Date<span class="text_requried">*</span></label>
                                
                                <input type="text"  name="commencement_date" value="{{ old('commencement_date', $data->commencement_date??'') }}" class="form-control date_input" data-validation="required" readonly>
								
								<br>
                                @can('project edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Contractual Completion Date<span class="text_requried">*</span></label>
                                
                                <input type="text"  name="contractual_completion_date" value="{{ old('contractual_completion_date', $data->contractual_completion_date??'') }}" class="form-control date_input" data-validation="required" readonly>
								
								<br>
                                @can('project edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               		<label class="control-label text-right">Actual Completion Date</label>
                                
                                <input type="text"  name="actual_completion_date" value="{{ old('actual_completion_date', $data->actual_completion_date??'') }}" class="form-control date_input" readonly>
								
								<br>
                                @can('project edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Project Status<span class="text_requried">*</span></label>
                                
                               	<select  name="pr_status_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($projectStatuses as $projectStatus)
									<option value="{{$projectStatus->id}}" {{(old("pr_status_id", $data->pr_status_id??'')==$projectStatus->id? "selected" : "")}}>{{$projectStatus->name}}</option>
                                    @endforeach
                                    
                                    
                                </select>
								
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">BARQAAB Role<span class="text_requried" data-validation="required">*</span></label>
                               
                               	<select  name="pr_role_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($projectRoles as $projectRole)
									<option value="{{$projectRole->id}}" {{(old("pr_role_id",$data->pr_role_id??'')==$projectRole->id? "selected" : "")}}>{{$projectRole->name}}</option>
                                    @endforeach
                                   
                                  
                                </select>
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Contract Type<span class="text_requried">*</span></label>
                               	<select  name="contract_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($contractTypes as $contractType)
									<option value="{{$contractType->id}}" {{(old("contract_type_id", $data->contract_type_id??'')==$contractType->id? "selected" : "")}}>{{$contractType->name}}</option>
                                    @endforeach
                                    
                                </select>
                                
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Project Division<span class="text_requried">*</span></label>
                                <select  name="pr_division_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($divisions as $division)
                                    <option value="{{$division->id}}" {{(old("pr_division_id", $data->pr_division_id??'')==$division->id? "selected" : "")}}>{{$division->name}}</option>
                                    @endforeach
                                    
                                </select>
                                
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Project No<span class="text_requried">*</span></label>
                               
                               	<input type="text"  name="project_no" data-validation=" required length" data-validation-length="max6" value="{{ old('project_no',$data->project_no??'') }}" class="form-control" >
								
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">share</label>
                               
                               	 <input type="text" name="share" value="{{ old('share',$data->share??'') }}" data-validation="length" data-validation-length="max190" class="form-control"  placeholder="BARQAAB Share" >
								
                            </div>
                        </div>
                    </div>
                     
                    
                </div><!--/End Row-->
            </div> <!--/End Form Boday-->

            <hr>
            @canany(['pr edit water', 'pr edit power'])
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit Project</button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcanany
        </form>
	</div> <!-- end card body -->    

		  



