@can('cv edit record')
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
</div>
@endcan


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
        @method('PATCH')
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title">Edit Document</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Description</label>
                        
                            <input type="text" id="document_name" name="document_name" value="{{ old('document_name',$data->document_name) }}" class="form-control" data-validation="required" placeholder="Enter Document Detail" >
                        </div>
                    </div>
                </div>
            </div>
                
            <!--/row-->
             <div class="row">
                <div class="col-md-8 pdfView">
                 @if($data->extension =='pdf')
                    <iframe src="{{asset('storage/'.$data->path.$data->file_name)}}" height="300" width="100%"/>
                @endif
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                     

                    
                    <div class="form-group row">
                        <center >
                        <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                        
                        </input>
                        <input type="file"  name="document" id="view" class="" hidden>
                                                                        

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
    
    <div class="col-md-12 table-container">
                      
    </div>
   
</div>
        
<script>
    $(document).ready(function(){
   
        $('#hideButton').click(function(){

           resetForm();
        });


        refreshTable("{{route('cvDocument.table')}}");
        $("form").submit(function (e) {
         e.preventDefault();
         });
       
        //submit function
        $("#formDocument").submit(function(e) { 
            e.preventDefault();
            var url = "{{route('cvDocument.update',$data->id)}}";
            $('.fa-spinner').show(); 
            submitForm(this, url);
            $('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}").attr('width','150');
             $('#pdf').attr('src','');
            $('#h6').text('Click On Image to Add Document');
            refreshTable("{{route('cvDocument.table')}}",900);
        });
        $( "#pdf" ).hide();
            // Prepare the preview for profile picture
        $("#view").change(function(){
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;
                //var fileType = fileName.split('.').pop();
               
            //Restrict File Size Less Than 2MB
            if (fileSize> 4096000){
                alert('File Size is bigger than 4MB');
                $(this).val('');
            }else{
                //Restrict File Type
                if ((fileType =='image/jpeg') || (fileType=='image/png')){
                    $( "#pdf" ).hide();
                    readURL(this);
                    document.getElementById("h6").innerHTML = "Image is Attached";
                }else if(fileType=='application/pdf')
                {
                readURL(this);// for Default Image
                
                document.getElementById("pdf").src="{{asset('Massets/images/document.png')}}";  
                $( "#pdf" ).show();
                }else if ((fileType=='application/vnd.openxmlformats-officedocument.wordprocessingml.document')||(fileType=='application/msword')){
                    readURL(this);
                    document.getElementById("h6").innerHTML = "Document File is Attached";
                    $('iframe').hide();
                    
                }else{
                    alert('Only PDF, JPG and PNG Files Allowed');
                $(this).val('');
                }
            }
            
        });
    });//end document ready
        function readURL(input) {
            var fileName = input.files[0].name;
            var fileType = input.files[0].type;
            //var fileType = fileName.split('.').pop();
                                
            if ((fileType =='image/jpeg')||(fileType =='image/png')){
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
                            $('iframe').attr('src', e.target.result.concat('#toolbar=0&navpanes=0&scrollbar=0'));
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
      
            
            
</script>