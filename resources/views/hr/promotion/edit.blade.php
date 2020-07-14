
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "formPromotion" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('promotion.update',$data->id)}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Edit Promotion</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Designation<span class="text_requried">*</span></label>
                                 <select  id="hr_designation_id"   name="hr_designation_id" data-validation="required" class="form-control selectTwo">
                                    <option value=""></option>
                                    @foreach($designations as $designation)
                                    <option value="{{$designation->id}}" {{(old("hr_designation_id",$data->hr_designation_id??'')==$designation->id? "selected" : "")}}>{{$designation->name}}</option>
                                    @endforeach 
                                </select>
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>
                                
                                <input type="text" name="effective_date" value="{{ old('effective_date',$data->effective_date??'') }}" class="form-control date_input" data-validation="required" readonly >

                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Salary<span class="text_requried">*</span></label>

                                <select  id="hr_salary_id"   name="hr_salary_id" data-validation="required" class="form-control selectTwo">
                                    <option value=""></option>
                                    @foreach($salaries as $salary)
                                    <option value="{{$salary->id}}" {{(old("hr_salary_id",$data->hr_salary_id??'')==$salary->id? "selected" : "")}}>{{$salary->total_salary}}</option>
                                    @endforeach
                              
                                </select>

                                @can('hr edit record')
                                <button type="button" class="btn btn-sm btn-info"  data-toggle="modal" data-target="#salModal"><i class="fas fa-plus"></i>
                                </button>
                                @endcan 
                                                           
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Grade</label>
                                <select  name="grade"  class="form-control selectTwo">
                                    <option value=""></option>
                                    @for ($i = 1; $i < 15; $i++)
                                    <option value="{{$i}}" {{(old("grade",$data->grade??'')==$i? "selected" : "")}}>{{ $i }}</option>

                                    @endfor
                                </select>
                                
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">HOD<span class="text_requried">*</span></label>
                                 <select  id="hr_manager_id"   name="hr_manager_id" data-validation="required" class="form-control selectTwo" >
                                    <option value=""></option>
                                    @foreach($managers as $manager)
                                    <option value="{{$manager->id}}" {{(old("hr_manager_id",$data->hr_manager_id??'')==$manager->id? "selected" : "")}}>{{$manager->first_name}} {{$manager->last_name}}</option>
                                    @endforeach  
                                </select>
                                 
                                
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Department<span class="text_requried">*</span></label>
                                 <select  id="hr_department_id"   name="hr_department_id" data-validation="required" class="form-control selectTwo" >
                                    <option value=""></option>
                                    @foreach($departments as $department)
                                    <option value="{{$department->id}}" {{(old("hr_department_id",$data->hr_department_id??'')==$department->id? "selected" : "")}}>{{$department->name}}</option>
                                    @endforeach  
                                </select>
                                    
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Category<span class="text_requried">*</span></label>
                                <select  name="category"  class="form-control selectTwo" data-validation="required" >
                                    <option value=""></option>
                                    <option value="A" {{(old("category",$data->category??'')=='A'? "selected" : "")}}>Category-A</option>
                                    <option value="B" {{(old("category",$data->category??'')=='B'? "selected" : "")}}>Category-B</option>
                                    <option value="C" {{(old("category",$data->category??'')=='C'? "selected" : "")}}>Category-C</option>
                                </select>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Remarks<span class="text_requried">*</span></label>
                                <input type="text"  name="remarks" id="forward_slash" value="{{ old('remarks',$data->remarks??'') }}"  class="form-control" data-validation="required length" data-validation-length="max100" placeholder="Enter Remarks if any">
                                
                               
                            </div>
                        </div>
                    </div>     
                     <!--/span-->
                    <div class="col-md-2">
                        <!--/empty-->
                    </div>                  
                    <div class="col-md-2">
                        <div class="form-group row">
                            <center >
                            <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="50" >
                            
                            </input>
                            <input type="file"  name="document" id="view"  class="" hidden>
                                                                            

                            <h6 id="h6" class="card-title m-t-10">Click On Image to Add Pdf Document<span class="text_requried">*</span></h6>
                    
                            </center>
                        </div>
                    </div>                  
                </div><!--/End Row-->
                 <!--/row-->
                <div class="row">
                    <div class="col-md-8 pdfView">
                        <embed id="pdf" src=""  type="application/pdf" height="200" width="100%" />
                    </div>
                </div>

               
            </div> <!--/End Form Boday-->

            <hr>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit Promotion</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @include('hr.appointment.salModal')
	</div> <!-- end card body --> 
    <div class="row">
        <div class="col-md-12 table-container">
           
            
        </div>
    </div>      
  

<script>
$(document).ready(function(){
     refreshTable("{{route('promotion.table')}}");
    //submit function

       $("#formPromotion").submit(function(e) { 
            e.preventDefault();
            var url = $(this).attr('action');
            console.log('OK');
           
            $('.fa-spinner').show(); 
            submitForm(this, url);
            refreshTable("{{route('promotion.table')}}",1000);
        });
       

        $( "#pdf" ).hide();

         $("#view").change(function(){
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;
                //var fileType = fileName.split('.').pop();
                
            //Restrict File Size Less Than 2MB
            if (fileSize> 2048000){
                alert('File Size is bigger than 2MB');
                $(this).val('');
            }else{
                //Restrict File Type
                if(fileType=='application/pdf')
                {
                readURL(this);// for Default Image
                
                document.getElementById("pdf").src="{{asset('Massets/images/document.png')}}";  
                $( "#pdf" ).show();
                }else{
                    alert('Only PDF File is Allowed');
                $(this).val('');
                }
            }
            
        });

        function readURL(input) {
            var fileName = input.files[0].name;
            var fileType = input.files[0].type;
            //var fileType = fileName.split('.').pop();
                                
            if (fileType !='application/pdf'){
            //Read URL if image
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                        reader.onload = function (e) {
                            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow').attr('width','100%');
                        }
                        reader.readAsDataURL(input.files[0]);
                }
                    
            }else{
               
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                        reader.onload = function (e) {
                            $('embed').attr('src', e.target.result.concat('#toolbar=0&navpanes=0&scrollbar=0'));
                        }
                        reader.readAsDataURL(input.files[0]);
                }   
                document.getElementById("wizardPicturePreview").src="{{asset('Massets/images/document.png')}}"; 
                document.getElementById("h6").innerHTML = "PDF File is Attached";
                 $('#wizardPicturePreview').attr('width','50');
            }           
        }
            
        $("#wizardPicturePreview" ).click (function() {
           $("input[id='view']").click();
        });


});

</script>



