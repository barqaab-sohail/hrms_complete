<div style="margin-top:10px; margin-right: 10px;">
    <a type="button" style="color:white;" id ="hideButton"  class="btn btn-success float-right">Add Document</a>
</div>

<div class="card-body" id="hideDiv">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('submissionDocument.update',$data->id)}}" id="formEditDocument" enctype="multipart/form-data">
    @method('PATCH')
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title">Edit Document</h3>
            <hr class="m-t-0 m-b-40">
             <!--/row-->
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group row">
                        <div class="col-md-12">
                           
                            <label class="control-label text-right">Document Description</label>
                            <input type="text" id="description" name="description" value="{{ old('description', $data->description??'') }}" class="form-control" data-validation="required" placeholder="Enter Document Detail" >
                               
                        </div>
                    </div>
                </div>
                
            </div>
    

             <div class="row">
                <div class="col-md-8 pdfView">
                    @if($data->extension == 'pdf')
                    <iframe src="{{asset('storage/'.$data->path.$data->file_name)}}" height="300" width="100%"/>
                    @endif
                </div>
				<div class="col-md-1">
				</div>
                <div class="col-md-3">
                	 
                    <div class="form-group row">
                        <center >
                        @if(($data->extension == "jpg")||($data->extension == "jpeg")||($data->extension == "png"))
                		<img src="{{asset(isset($data->file_name)? 'storage/'.$data->path.$data->file_name: 'Massets/images/document.png') }}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                        @else
                        <img  src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                        @endif
            			
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
   
</div>
    
<div class="row">
        <div class="col-md-12 table-container">

        </div>
</div>
        
<script>
    $(document).ready(function(){
        
        refreshTable("{{route('submissionDocument.table')}}");



        $('#hideButton').click(function(e){
            e.preventDefault();
            url="{{route('submissionDocument.create')}}";
            getAjaxData(url);

            setTimeout(function(){
                $('#hideDiv').show();
            },1000);


        });


        //submit function
    	$("#formEditDocument").submit(function(e) {	
			e.preventDefault();
			var url = $(this).attr('action');
            $('.fa-spinner').show(); 
			submitForm(this, url);
           
            $('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}").attr('width','150');
            $('#h6').text('Click On Image to Add Document');

            var folderUrl = $('.fa-folder-open').closest('a').attr('href');
            if (typeof folderUrl  !== "undefined"){
                refreshTable(folderUrl,1000);
            }

		});



        $("#pdf").hide();
			// Prepare the preview for profile picture
	    $("#view").change(function(){
	        	var fileName = this.files[0].name;
	        	var fileType = this.files[0].type;
	        	var fileSize = this.files[0].size;
	        	//var fileType = fileName.split('.').pop();
	        	

	        //Restrict File Size Less Than 2MB
	        if (fileSize> 10240000){
	        	alert('File Size is bigger than 10MB');
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
	        		        	
	        if (fileType !='application/pdf'){
        	//Read URL if image
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
	                    reader.onload = function (e) {
	                        $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow').attr('width','100%');
                             $('iframe').remove();
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