@can('pr edit record')
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-info float-right">Add Document</button>
</div>
@endcan

<div class="card-body" id="hideDiv">
    @include('project.document.form')
   
</div>
    <div class="row">
              <div class="col-md-12 table-container">
       
               
            </div>
    </div>
        
<script>
$(document).ready(function(){
        
        $('.employeeName').hide();
        $('#employee_file').click(function(){
              $('.employeeName').toggle();

        });



        $('#hideDiv').hide();
        $('#hideButton').click(function(){
              $('#hideDiv').toggle();

        });


        refreshTable("{{route('projectDocument.table')}}");
        
        $("#document_name").change(function (){
            var other = $('#document_name').val();
                if (other == 'Other'){
                    $('.hideDiv').show();
                    $('#forward_slash').attr('data-validation','required length');
                }else{
                    $('.hideDiv').hide();
                    $('#forward_slash').removeAttr('data-validation').val('');
                }
        });
        //submit function
        $("#formDocument").submit(function(e) { 
            console.log('submit');
            e.preventDefault();
            var url = "{{route('projectDocument.store')}}";
            $('.fa-spinner').show(); 
            submitForm(this, url);
            resetForm();
            $('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}").attr('width','150');
             $('#pdf').attr('src','');
            $('#h6').text('Click On Image to Add Document');
            //refreshTable("{{route('projectDocument.table')}}",500);
        });
        $( "#pdf" ).hide();
            // Prepare the preview for profile picture
        $("#view").change(function(){
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;
                //var fileType = fileName.split('.').pop();
                
            //Restrict File Size Less Than 2MB
            if (fileSize> 25600000){
                alert('File Size is bigger than 20MB');
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