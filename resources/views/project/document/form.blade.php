<form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title">Document</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Name<span class="text_requried">*</span></label>
                        
                            <select  name="pr_document_name_id" id="document_name"  data-validation="required"  class="form-control selectTwo">
                                <option value=""></option>
                                <option value="Other">Other</option>
                                @foreach($documentNames as $documentName)
                                <option value="{{$documentName->id}}" {{(old("pr_document_name_id")==$documentName->id? "selected" : "")}}>{{$documentName->name}}</option>
                                @endforeach
                            </select>         
                        </div>
                    </div>
                </div>
                 <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                            <input type="text" name="document_date"  value="{{ old('document_date') }}" class="form-control date_input" data-validation="required"  readonly placeholder="Enter Document Detail">
                            <br>
                            <i class="fas fa-trash-alt text_requried"></i>  
                        </div>
                    </div>
                </div>
                
                <!--/span-->
                <div class="col-md-6 hideDiv">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Description</label>
                            <input type="text" id="forward_slash" name="description"  value="{{ old('description') }}" class="form-control" data-validation=""  data-validation-length="max190" placeholder="Enter Document Detail" >
                        </div>
                    </div>
                </div>
            </div>
            <!--/row-->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="employee_file">
                          <label class="form-check-label" for="employee_file">
                            Also Save in Employee File
                          </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 employeeName">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Employee Name</label>
                        
                            <select  name="hr_employee_id[]" id="hr_employee_id"  multiple="multiple" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}}</option>
                                @endforeach
                            </select>         
                        </div>
                    </div>
                </div>
                
            </div>
                            
            <!--/row-->
             <div class="row">
                <div class="col-md-8 pdfView">
                    <embed id="pdf" src=""  type="application/pdf" height="300" width="100%" />
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <center >
                        <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                        </input>
                        <input type="file"  name="document" id="view" data-validation="required" class="" hidden>
                                                                        
                        <h6 id="h6" class="card-title m-t-10">Click On Image to Add Document<span class="text_requried">*</span></h6>
                
                        </center>
                       
                    </div>

                </div>
                                                        
            </div>
            
                                               
        </div>
         <hr>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                       
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>
                                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>