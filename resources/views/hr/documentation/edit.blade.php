@can('hr edit record')
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
</div>
@endcan


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('documentation.update',$data->id)}}" id="formEditDocument" enctype="multipart/form-data">
    @method('PATCH')
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title">Edit Document</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">Document Name<span class="text_requried">*</span></label>
                        
                            <select  name="hr_document_name_id" id="document_name"    class="form-control selectTwo">
                            <option value=""></option>
                            <option value="Other" selected>Other</option>
                                @foreach($documentNames as $documentName)
								<option value="{{$documentName->id}}" {{(old("hr_document_name_id",$data->hrDocumentName->first()->id??'')==$documentName->id? "selected" : "")}}>{{$documentName->name}}</option>
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
                        
                            <input type="text" name="document_date"  id="document_date" value="{{ old('document_date',$data->document_date) }}" class="form-control date_input" readonly data-validation="required" placeholder="Enter Document Detail">
                            <br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            
                        </div>
                    </div>
                </div>
                
                <!--/span-->
                @if($documentNameExist == null)
                <div class="col-md-6" id="documentNameExist">
                    <div class="form-group row">
                        <div class="col-md-12">
                        	<label class="control-label text-right">Document Description</label>
                        
                            <input type="text" id="description" name="description" value="{{old('description',$data->description??'')}}" class="form-control exempted" data-validation="required" placeholder="Enter Document Detail" >
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-6 hideDiv">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Description</label>
                        
                            <input type="text" id="description" name="description" value="{{ old('description', $data->description??'') }}" class="form-control" data-validation="required" placeholder="Enter Document Detail" >
                        </div>
                    </div>
                </div>
                @endif
            </div>

  
            <!--/row-->
             <div class="row">
                <div class="col-md-8 pdfView">
                    @if($data->extension == 'pdf')
                    <iframe src="{{asset('storage/'.$data->path.$data->file_name)}}" height="300" width="100%" id="iframe"/>
                    @endif
                </div>
				<div class="col-md-1">
				</div>
                <div class="col-md-3">
                	 

                	@can('hr edit record')
                    <div class="form-group row">
                        <center >
                        @if($data->extension != 'pdf')
                		<img src="{{asset(isset($data->file_name)? 'storage/'.$data->path.$data->file_name: 'Massets/images/document.png') }}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                        @else
                        <img  src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                        @endif
            			
                		</input>
                		<input type="file"  name="document" id="view" class="" hidden>
                				                                		

                        <h6 id="h6" class="card-title m-t-10">Click On Image to Add Document<span class="text_requried">*</span></h6>
                
                        </center>
                       
                    </div>
                    @endcan


                </div>
                		                                
            </div>
			
            		                           
        </div>
         <hr>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                        @can('hr edit record')
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>
                        @endcan                         
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
        
        refreshTable("{{route('documentation.table')}}");

         $('#formEditDocument').show();
        $('#hideButton').click(function(){
          $('#formEditDocument').toggle();
          resetForm();
          $("#wizardPicturePreview").attr("src","{{asset('Massets/images/document.png')}}");
          $("#h6").text('Click On Image to Add Document');

        });

        $("form").submit(function (e) {
         e.preventDefault();
         });

    	$("#document_name").change(function (){
			var other = $('#document_name').val();
				if (other == 'Other'){
					$('.hideDiv').show();
                    $('#documentNameExist').show();
					$('#description').attr('data-validation','required');
				}else{
					$('.hideDiv').hide();
                    $('#documentNameExist').hide();
					$('#description').removeAttr('data-validation');
				}
		});


        //submit function
    	$("#formEditDocument").submit(function(e) {	
			e.preventDefault();
			var url = $(this).attr('action');
            $('.fa-spinner').show(); 
			submitForm(this, url);
            resetForm();
            $('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}").attr('width','150');
            $('#h6').text('Click On Image to Add Document');
            //refreshTable("{{route('documentation.table')}}",1500);

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
	        	alert('File Size is bigger than 3MB');
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