<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Document</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Description</label>
                            <input type="hidden" name="sub_document_id" id="sub_document_id">

                            <input type="text" id="description" name="description" value="{{ old('description') }}" class="form-control exempted" data-validation="required" placeholder="Enter Document Detail" >
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
                        
                        <input type="file"  name="document" id="view" class="form-control" hidden>
                                                                        

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
                       
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" id="saveBtn" style="font-size:18px"></i>Save</button>
                                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
<br>
<table class="table table-bordered data-table" width=100%>
    <thead>
      <tr>
          <th style="width:35%">Description</th>
          <th style="width:15%">View</th>
          <th style="width:5%">Edit</th>
          <th style="width:5%">Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>

   
</div>
        
<script type="text/javascript">
$(document).ready(function(){

       // formFunctions();

       $('#formDocument').hide();    

        $( "#pdf" ).hide();
            // Prepare the preview for profile picture
        $("#view").change(function(){
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;
                
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
                    $('embed').remove();
                    
                }else{
                    alert('Only PDF, JPG and PNG Files Allowed');
                $(this).val('');
                }
            }
            
        });

});//end document ready

      //start function
$(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        retrieve: true,
        ajax: "{{ route('submissionDocument.create') }}",
        columns: [
            {data: "description", name: 'description'},
            {data: "document", name: 'document'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#formDocument').toggle();
            $('#formDocument').trigger("reset");
            $('#sub_document_id').val('');
            $("#formDocument").attr("method", "POST");
            $( "#pdf" ).hide();
              document.getElementById("wizardPicturePreview").src="{{asset('Massets/images/document.png')}}"; 
              document.getElementById("h6").innerHTML = "Click On Image to Add Document";
    });

    $('body').unbind().on('click', '.editDocument', function () {
      var sub_document_id = $(this).data('id');
       $("#formDocument").attr("method", "PUT");
      $.get("{{ url('hrms/submissionDocument') }}" +'/' + sub_document_id +'/edit', function (data) {
          $('#formDocument').show(); 
          var file_extension = data.extension;
          var file_path = '/'+ data.path + data.file_name; 
          var file_name = `{{asset('storage/')}}${file_path}`;
          $('#formHeading').html("Edit Document");
          $('#description').val(data.description);
          $('#sub_document_id').val(data.id);
          if(file_extension == 'pdf'){
             $( "#pdf" ).show();
             $('embed').attr('src', file_name);
          }else{
             $('#wizardPicturePreview').attr("src", file_name);
          }

      
      })
   });
    
      $("#formDocument").submit(function(e) {

        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);
        
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          data: formData,
          url: "{{ route('submissionDocument.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
     
              $('#formDocument').trigger("reset");
              $( "#pdf" ).hide();
              document.getElementById("wizardPicturePreview").src="{{asset('Massets/images/document.png')}}"; 
              document.getElementById("h6").innerHTML = "Click On Image to Add Document";
              $('#formDocument').toggle();
              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');  

              table.draw();
        
          },
          error: function (data) {
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteDocument', function () {
     
        var sub_document_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('submissionDocument.store') }}"+'/'+sub_document_id,
            success: function (data) {
                table.draw();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                
            }
          });
        }
    });
     
  });// end function



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


      $(document).on('click','.viewPdf, .viewImg', function(){  
        $('.viewPdf, .viewImg').EZView();
    });
            
            
</script>