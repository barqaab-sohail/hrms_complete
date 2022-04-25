@can('pr edit document')
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
    </div>
@endcan

<div class="card-body" id="hideDiv">
    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title">Document</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Folder Name<span class="text_requried">*</span></label>
                        
                            <select  name="pr_folder_name_id" id="document_name"  data-validation="required"  class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($prFolderNames as $prFolderName)
                                <option value="{{$prFolderName->id}}" {{(old("pr_folder_name_id")==$prFolderName->id? "selected" : "")}}>{{$prFolderName->name}}</option>
                                @endforeach
                            </select>         
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Reference No</label>
                            <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}" class="form-control exempted" data-validation="length"  data-validation-length="max190" placeholder="Enter Document Reference" >
                            <div id="check_reference"></div>
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
            </div>
            <!--/row-->
            
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group row">
                        <div class="col-md-12">
                           
                            <label class="control-label text-right">Document Description</label>
                            <input type="text" name="description"  value="{{ old('description') }}" class="form-control" data-validation="required length"  data-validation-length="max190" placeholder="Enter Document Detail" >
                               
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
         
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                       
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>
                        <hr>
                        <br>           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
   
</div>

        
         <div class="row">
                @foreach($prFolderNames as $prFolderName)
                <div class="col-md-3 ">
                    <div class="form-group row">
                        <div class="col-md-12">
                          <a id="documentList{{$prFolderName->id}}" href="{{route('projectDocument.show',$prFolderName->id)}}" data-toggle="tooltip" data-original-title="Edit">
                            <i class="fa fa-folder fa-3x"  style="color:#cfca3e;" aria-hidden="true"></i>
                            <p style="color:black;">{{$prFolderName->name}}</p>
                           </a>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>



    <div class="row" >
              <div class="col-md-12 table-container">
       
               
            </div>
    </div>
        
<script>
$(document).ready(function(){
        
        $('.employeeName').hide();
        $('#employee_file').click(function(){
         $('.employeeName').toggle();
         $("#hr_employee_id").val('').select2('val', 'All');

        });

       


        $('#hideDiv').hide();
        $('#hideButton').click(function(){
              $('#hideDiv').toggle();

        });



        $('a[id^=documentList]').click(function (e){
            $('#hideDiv').hide();
            e.preventDefault();
            $('a[id^=documentList]').not(this).find('i').attr('class','fa fa-folder fa-3x')

            var url = $(this).attr('href');
            var $el = $(this).find( "i" ).toggleClass('fa-folder-open');
            if ($el.hasClass('fa-folder-open')) {
                
                refreshTable(url);
            }else{
                $('#myDataDiv').remove();
            }
       
        });


        refreshTable("{{route('projectDocument.table')}}");
        
      
        //submit function
        $("#formDocument").submit(function(e) { 
            e.preventDefault();
            var url = "{{route('projectDocument.store')}}";
            $('.fa-spinner').show(); 
            submitForm(this, url);
            resetForm();
            $('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}").attr('width','150');
             $('#pdf').attr('src','');
            $('#h6').text('Click On Image to Add Document');

            var folderUrl = $('.fa-folder-open').closest('a').attr('href');
            if (typeof folderUrl  !== "undefined"){
                //refreshTable(folderUrl,1000);
            }
            
        });
        $( "#pdf" ).hide();
            // Prepare the preview for profile picture
        $("#view").change(function(){
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;
                //var fileType = fileName.split('.').pop();
                
            //Restrict File Size Less Than 30MB
            if (fileSize> 38400000){
                alert('File Size is bigger than 30MB');
                $(this).val('');
            }else{
                //Restrict File Type
                if ((fileType =='application/msword') || (fileType=='application/vnd.openxmlformats-officedocument.wordprocessingml.document')){
                    $( "#pdf" ).hide();
                    document.getElementById("h6").innerHTML = "MS Word Document is Attached";
                }else if(fileType=='application/pdf')
                {
                readURL(this);// for Default Image
                document.getElementById("h6").innerHTML = "PDF Document is Attached";
                document.getElementById("pdf").src="{{asset('Massets/images/document.png')}}";  
                $( "#pdf" ).show();
                }else{
                    alert('Only PDF, JPG and PNG Files Allowed');
                $(this).val('');
                }
            }
            
        });

        $('#reference_no').keyup(function(){ 
            var query = $(this).val();
            if(query != '')
            {
             var _token = $('input[name="_token"]').val();
                $.ajax({
                  url:"{{ route('projectDocument.reference') }}",
                  method:"GET",
                  data:{query:query, _token:_token},
                  success:function(data){
                   $('#check_reference').fadeIn();  
                            $('#check_reference').html(data);
                  }
                });
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
                 $('#wizardPicturePreview').attr('width','150');
            }           
        }
            
        $("#wizardPicturePreview" ).click (function() {
           $("input[id='view']").click();
        });

});//end document ready

        
      
            
            
</script>