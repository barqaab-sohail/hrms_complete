@can('pr edit document')
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
</div>
@endcan

<div class="card-body">

<form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-body">

            <h3 class="box-title">Document</h3>
            <hr class="m-t-0 m-b-40">
            <input type="hidden" name="pr_document_id" id="pr_document_id"/>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Folder Name<span class="text_requried">*</span></label>

                            <select name="pr_folder_name_id" id="pr_folder_name_id" data-validation="required" class="form-control selectTwo">
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
                            <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}" class="form-control exempted" data-validation="length" data-validation-length="max190" placeholder="Enter Document Reference">
                            <div id="check_reference"></div>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                            <input type="text" name="document_date" id="document_date" value="{{ old('document_date') }}" class="form-control date_input" data-validation="required" readonly >
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
                            <input type="text" name="description" id="forward_slash"  value="{{ old('description') }}" class="form-control" data-validation="required length" data-validation-length="max190" placeholder="Enter Document Detail">

                        </div>
                    </div>
                </div>
                @can('large file upload')
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Upload File Size</label>
                            <input type="number" name="size" id="size"  value="{{ old('size') }}" class="form-control" placeholder="38,400,000 Size in Bytes">

                        </div>
                    </div>
                </div>
                @endcan
            </div>
            <!--/row-->

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
                            <input type="file" name="document" id="view" data-validation="required" class="" hidden>

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
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                <span class="sr-only">Loading...</span>
                                <span class="btn-text">Save Document</span>
                            </button>
                            <hr>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="row">
        @foreach($prFolderNames as $prFolderName)
        <div class="col-md-3 ">
            <div class="form-group row">
                <div class="col-md-12">
                    <a id="documentList{{$prFolderName->id}}" href="{{route('projectDocument.showFolder',[$prFolderName->id, $id])}}" data-toggle="tooltip" data-original-title="Edit">
                        <i class="fa fa-folder fa-3x" style="color:#cfca3e;" aria-hidden="true"></i>
                        <p style="color:black;">{{$prFolderName->name}}</p>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
       
    <table class="table table-bordered data-table" width=100%>
            <thead>
            <tr>
                <th>Document Name</th>
                <th>Reference No</th>
                <th>Date</th>
                <th>View</th>
                <th>Copy Link</th>
                <th>Edit</th>
                <th>Delete</th> 
            </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>

</div>   
<style>
.spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: text-bottom;
    border: .25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: spinner-border .75s linear infinite;
    animation: spinner-border .75s linear infinite;
}

@-webkit-keyframes spinner-border {
    to { -webkit-transform: rotate(360deg); }
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}
</style>
        
<script>
    $(document).ready(function() {

        $('#formDocument').hide();

        $('.employeeName').hide();
        $('#employee_file').click(function() {
            $('.employeeName').toggle();
            $("#hr_employee_id").val('').select2('val', 'All');
        });

        // $('#hideDiv').hide();
        // $('#hideButton').click(function() {
        //     $('#hideDiv').toggle();
        // });


        


        //refreshTable("{{route('projectDocument.table')}}");


        //submit function
        // $("#formDocument").submit(function(e) {
        //     e.preventDefault();
        //     var url = "{{route('projectDocument.store')}}";
        //     $('.fa-spinner').show();
        //     submitForm(this, url);
        //     resetForm();
        //     $('#wizardPicturePreview').attr('src', "{{asset('Massets/images/document.png')}}").attr('width', '150');
        //     $('#pdf').attr('src', '');
        //     $('#h6').text('Click On Image to Add Document');

        //     var folderUrl = $('.fa-folder-open').closest('a').attr('href');
        //     if (typeof folderUrl !== "undefined") {
        //         refreshTable(folderUrl,1000);
        //     }

        // });
        $("#pdf").hide();
        // Prepare the preview for profile picture
        $("#view").change(function() {
            var fileName = this.files[0].name;
            var fileType = this.files[0].type;
            var fileSize = this.files[0].size;
            //var fileType = fileName.split('.').pop();
            var size = $("#size").val();
            if(size == ''){
                size = 38400000;
            }
            //Restrict File Size Less Than 30MB
            if (fileSize > size) {
                alert('File Size is bigger than 30MB');
                $(this).val('');
            } else {
                //Restrict File Type
                if ((fileType == 'application/msword') || (fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                    $("#pdf").hide();
                    document.getElementById("h6").innerHTML = "MS Word Document is Attached";
                } else if (fileType == 'application/pdf') {
                    readURL(this); // for Default Image
                    document.getElementById("h6").innerHTML = "PDF Document is Attached";
                    document.getElementById("pdf").src = "{{asset('Massets/images/document.png')}}";
                    $("#pdf").show();
                } else if (fileType == 'application/vnd.ms-excel' || fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    $("#pdf").hide();
                    document.getElementById("h6").innerHTML = "MS Excel Document is Attached";
                } else {
                    alert('Only Doc, Docx, Xls, Xlsx, PDF Allowed');
                    $(this).val('');
                }
            }

        });

        $('#reference_no').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('projectDocument.reference') }}",
                    method: "GET",
                    data: {
                        query: query,
                        _token: _token
                    },
                    success: function(data) {
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

    }); //end document ready

//Create Datatabel Function
function createDatatable(url){
    return $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
        ],
        ajax:{url:url, data: {
            prDetailId: $("#pr_detail_id").val()
        }},
        columns: [
            {data: "description", name: 'description'},
            {data: "reference_no", name: 'reference_no'},
            {data: "document_date", name: 'document_date'},
            {data: "document", name: 'document'},
            {data: "copy_link", name: 'copy_link'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
        "drawCallback": function(settings) {
            if(this.api().rows().data().length>0){
			    $("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
            }

            $('.copyLink').click(function() {
			var text = $(this).attr('link').replace(" ", "%20");
			navigator.clipboard.writeText(text);
			alert('Link Copied');
            });
            $('.copyLink').hover(function() {
                $(this).css('cursor', 'pointer').attr('title', 'Click to copy short URL');
            }, function() {
                $(this).css('cursor', 'auto');
            });

        },
        order: [[ 1, "desc" ]]
    });

}

//start function
$(function () {

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = createDatatable ("{{ route('projectDocument.create') }}");

    $('a[id^=documentList]').click(function(e) {
            $('#hideDiv').hide();
            e.preventDefault();
            $('a[id^=documentList]').not(this).find('i').attr('class', 'fa fa-folder fa-3x')

            var url = $(this).attr('href');
            var $el = $(this).find("i").toggleClass('fa-folder-open');
            if ($el.hasClass('fa-folder-open')) {
               table.destroy();
               table = createDatatable (url);
            } else {
               table.destroy();
               table = createDatatable ("{{ route('projectDocument.create') }}");
            }
    });


    $('#hideButton').click(function(){
            $('#pdf').attr('src','').hide();
            $('#h6').html("Click On Image to Add Pdf Document<span class='text_requried'>*</span>");
            $('#formDocument').toggle();
            $('#formDocument').trigger("reset");
            $('#pr_document_id').val('');
            $('#view').attr('data-validation','required');
            $('#pr_folder_name_id').trigger('change');
    });

    $('body').unbind().on('click', '.editDocument', function () {
      var pr_document_id = $(this).data('id');
        
      $.get("{{ url('hrms/project/projectDocument') }}" +'/' + pr_document_id +'/edit', function (data) {
        $('#formDocument').toggle();
            $('#formDocument').trigger("reset"); 
        
        $('#formDocument').show(); 
         
          $('#formHeading').html("Edit Document");
          $('#pr_document_id').val(data.id);
          $('#document_date').val(data.document_date);
          $('#forward_slash').val(data.description);
          $('#reference_no').val(data.reference_no);
          $('#pr_folder_name_id').val(data.pr_folder_name_id).trigger('change');
          if(data.extension=='pdf'){
            $("#pdf").show();
            $('#pdf').attr('src',data.full_path);
            $('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}");
          }else{
            $('#wizardPicturePreview').attr('src',data.full_path);
            $("#pdf").hide();
            $('#pdf').attr('src','');
          }
          
          $('#view').removeAttr('data-validation');

      })
   });
    
      $("#formDocument").submit(function(e) {
        e.preventDefault();
        // Disable button and show spinner
        var $submitBtn = $(this).find('.btn-prevent-multiple-submits');
        $submitBtn.prop('disabled', true);
        $submitBtn.find('.spinner-border').show();
        $submitBtn.find('.btn-text').text('Saving...');


        var formData = new FormData(this);
        formData.append("pr_detail_id", $("#pr_detail_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('projectDocument.store') }}",
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
                clearMessage();
              }
              // Re-enable button and hide spinner
            $submitBtn.prop('disabled', false);
            $submitBtn.find('.spinner-border').hide();
            $submitBtn.find('.btn-text').text('Save Document');
        
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
               // Re-enable button and hide spinner on error too
            $submitBtn.prop('disabled', false);
            $submitBtn.find('.spinner-border').hide();
            $submitBtn.find('.btn-text').text('Save Document');
          }
      });
    });
    
    $('body').on('click', '.deleteDocument', function () {
     
        var pr_document_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectDocument.store') }}"+'/'+pr_document_id,
            success: function (data) {
                table.draw();
                clearMessage();
                if($('#formDocument:visible').length != 0)
                    {
                        $('#formDocument').toggle();
                    }
                
                if(data.status=="Not OK"){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');    
                }else{
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                }
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