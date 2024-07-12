<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Employee Document</h3>
            <hr class="m-t-0 m-b-40">
            <input type="hidden" name="document_id" id="document_id"/>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Name<span class="text_requried">*</span></label>

                            <select name="hr_document_name_id" id="hr_document_name_id" data-validation="required" class="form-control selectTwo">
                                <option value=""></option>
                                <option value="Other">Other</option>
                                @foreach($documentNames as $documentName)
                                <option value="{{$documentName->id}}" {{(old("hr_document_name_id")==$documentName->id? "selected" : "")}}>{{$documentName->name}}</option>
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

                            <input type="text" name="document_date" id="document_date" value="{{ old('document_date') }}" class="form-control date_input" readonly placeholder="Enter Document Detail" data-validation="required">
                            <br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan

                        </div>
                    </div>
                </div>

                <!--/span-->
                <div class="col-md-6 hideDiv">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Document Description</label>

                            <input type="text" id="forward_slash" name="description" value="{{ old('description') }}" class="form-control exempted" data-validation="required" placeholder="Enter Document Detail">
                        </div>
                    </div>
                </div>
            </div>

            <!--/row-->
            <div class="row">
                <div class="col-md-8 pdfView">
                    <embed id="pdf" src="" type="application/pdf" height="300" width="100%" />
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <center>
                            <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src" id="wizardPicturePreview" title="" width="150">

                            </input>
                            <input type="file" name="document" id="view" data-validation="required" class="form-control" hidden>


                            <h6 id="h6" class="card-title m-t-10">Click On Image to Add Document<span class="text_requried">*</span></h6>

                        </center>

                    </div>



                </div>

            </div>    
              
               
        </div> <!--/End Form Boday-->

            <hr>
            @can('hr edit posting')
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Document</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
    </form>

    <br>
        <table class="table table-bordered data-table" width=100%>
            <thead>
            <tr>
                <th>Document Name</th>
                <th>Date</th>
                <th>View</th>
                <th>Copy Link</th>
                <th>Edit</th>
                <th>Delete</th> 
                <!-- <th colspan="2" class="text-center"style="width:10%"> Actions </th>  -->
            </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>


   

        
<script type="text/javascript">


$(document).ready(function(){
   


    $('#formDocument').hide();   
    $("#pdf").hide();
    $("#document_name").change(function() {
        var other = $('#document_name').val();
        if (other == 'Other') {
            $('.hideDiv').show();
            $('#forward_slash').attr('data-validation', 'required');
        } else {
            $('.hideDiv').hide();
            $('#forward_slash').removeAttr('data-validation').val('');
        }
    });

        $("#view").change(function() {
            var fileName = this.files[0].name;
            var fileType = this.files[0].type;
            var fileSize = this.files[0].size;
            //var fileType = fileName.split('.').pop();

            //Restrict File Size Less Than 2MB
            if (fileSize > 4096000) {
                alert('File Size is bigger than 3MB');
                $(this).val('');
            } else {
                //Restrict File Type
                if ((fileType == 'image/jpeg') || (fileType == 'image/png')) {
                    $("#pdf").hide();
                    readURL(this);
                    document.getElementById("h6").innerHTML = "Image is Attached";
                } else if (fileType == 'application/pdf') {
                    readURL(this); // for Default Image

                    document.getElementById("pdf").src = "{{asset('Massets/images/document.png')}}";
                    $("#pdf").show();
                } else {
                    alert('Only PDF, JPG and PNG Files Allowed');
                    $(this).val('');
                }
            }

        });

        function readURL(input) {
        var fileName = input.files[0].name;
        var fileType = input.files[0].type;
        //var fileType = fileName.split('.').pop();

        if (fileType != 'application/pdf') {
            //Read URL if image
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow').attr('width', '100%');
                }
                reader.readAsDataURL(input.files[0]);
            }

        } else {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('embed').attr('src', e.target.result.concat('#toolbar=0&navpanes=0&scrollbar=0'));
                }
                reader.readAsDataURL(input.files[0]);
            }
            document.getElementById("wizardPicturePreview").src = "{{asset('Massets/images/document.png')}}";
            document.getElementById("h6").innerHTML = "PDF File is Attached";
            $('#wizardPicturePreview').attr('width', '150');
        }
    }

    $("#wizardPicturePreview").click(function() {
        $("input[id='view']").click();
    });

}); 
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
        ajax:{url:"{{ route('documentation.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "description", name: 'description'},
            {data: "document_date", name: 'document_date'},
            {data: "document", name: 'document'},
            {data: "copy_link", name: 'copy_link'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        "drawCallback": function(settings) {
			$("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
            $('.copyLink').click(function() {
			var text = $(this).attr('link').replace(" ", "%20");
			navigator.clipboard.writeText(text);
			alert('Link Copied');
            });

            $('.copyLink').hover(function() {
                $(this).css('cursor', 'pointer').attr('title', 'Click for Copy Link');
            }, function() {
                $(this).css('cursor', 'auto');
            });

        },
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#pdf').attr('src','').hide();
            $('#h6').html("Click On Image to Add Pdf Document<span class='text_requried'>*</span>");
            $('#formDocument').toggle();
            $('#formDocument').trigger("reset");
            $('#document_id').val('');
            $('#view').attr('data-validation','required');
            $('#hr_document_name_id').trigger('change');
    });

    $('body').unbind().on('click', '.editDocument', function () {
      var document_id = $(this).data('id');
        
      $.get("{{ url('hrms/documentation') }}" +'/' + document_id +'/edit', function (data) {
          $('#formDocument').show(); 
          $('#formHeading').html("Edit Document");
          $('#document_id').val(data.id);
          $('#hr_document_name_id').val(data.hr_document_name?.hr_document_name_id).trigger('change');
          $('#document_date').val(data.document_date);
          $('#description').val(data.description);
          $('#pdf').val(data.full_path);
          $('#view').removeAttr('data-validation');
      })
   });
    
      $("#formDocument").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('documentation.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{
              
                $('#formDocument').trigger("reset");
                $('#formDocument').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');  

                table.draw();
              }
        
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
     
        var document_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('documentation.store') }}"+'/'+document_id,
            success: function (data) {
                table.draw();
                $('#formDocument').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
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

      
            
</script>