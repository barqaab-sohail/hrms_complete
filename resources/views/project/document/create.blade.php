


@can('pr edit document')
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Document</button>
</div>
@endcan

@can('pr search document')
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="searchButton"  class="btn btn-info float-right mr-2">Search Document</button>
</div>
@endcan

<!-- Search Form -->
<div id="searchForm" style="display: none; margin-bottom: 20px;" class="card card-body bg-light">
    <form method="GET" id="formSearch">
        <h4>Search Documents</h4>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Reference No</label>
                    <input type="text" name="search_reference" id="search_reference" class="form-control" placeholder="Enter reference number">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Description</label>
                    <input type="text" name="search_description" id="search_description" class="form-control" placeholder="Enter description">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Date</label>
                    <input type="text" name="search_date" id="search_date" class="form-control date_input" readonly placeholder="Select date">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Content</label>
                    <input type="text" name="search_content" id="search_content" class="form-control" placeholder="Search in document content">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" id="applySearch" class="btn btn-primary">Search</button>
                <button type="button" id="clearSearch" class="btn btn-secondary">Clear</button>
            </div>
        </div>
    </form>
</div>

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
                @can('pr edit document')
                <th>Edit</th>
                @endcan
                @can('pr delete document')
                <th>Delete</th> 
                @endcan
            </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>

        <div class="search-loading" id="searchLoading">
            <i class="fa fa-spinner fa-spin"></i> Searching documents...
        </div>
        <div id="searchResultsInfo"></div>
        

</div>   
<style>
    .search-loading {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    background: rgba(255, 255, 255, 0.9);
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.search-loading i {
    margin-right: 8px;
}

.dataTables_wrapper {
    position: relative;
}

#searchResultsInfo {
    margin-top: 10px;
    font-style: italic;
    color: #6c757d;
}
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

/* Loading indicator */
#table-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
}

.dataTables_wrapper {
    position: relative;
}

.dataTables_processing {
    display: none !important;
}
</style>
        
<script>
    // Declare table variable in the global scope
    var table;

    $(document).ready(function() {
        // Show loading indicator immediately
        $('.data-table').before('<div id="table-loading" style="text-align: center; padding: 20px;"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Loading documents...</div>');

        $('#formDocument').hide();

        $('.employeeName').hide();
        $('#employee_file').click(function() {
            $('.employeeName').toggle();
            $("#hr_employee_id").val('').select2('val', 'All');
        });

        // Initialize date picker for search form
        $('#search_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

         // Toggle search form
            $('#searchButton').click(function() {
                $('#searchForm').toggle();
                if ($('#searchForm').is(':visible')) {
                    $('#formDocument').hide();
                }
            });

            // Clear search form
            $('#clearSearch').click(function() {
                clearSearch();
            });


         // Apply search
         $('#applySearch').click(function() {
            performSearch();
        });

        // Allow pressing Enter to search
        $('#formSearch input').keypress(function(e) {
            if (e.which === 13) {
                performSearch();
                return false;
            }
        });

       

        $("#pdf").hide();
        // Prepare the preview for profile picture
        $("#view").change(function() {
            var fileName = this.files[0].name;
            var fileType = this.files[0].type;
            var fileSize = this.files[0].size;
            //var fileType = fileName.split('.').pop();
            var size = $("#size").val();
            if(size == ''){
                size = 10485760;
            }
            //Restrict File Size Less Than 30MB
            if (fileSize > size) {
                alert('File Size is bigger than 10MB');
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

// Function to perform search with loading indicator
function performSearch() {
    // Show loading indicator
    $('#searchLoading').show();
    $('#searchResultsInfo').hide();
    
    // Disable search button during search
    $('#applySearch').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Searching...');
    $('#clearSearch').prop('disabled', true);
    
    // Perform the search
    table.draw();
}

// Function to clear search with loading indicator
function clearSearch() {
    // Show loading indicator
    $('#searchLoading').show();
    $('#searchResultsInfo').hide();
    
    // Disable buttons during clear operation
    $('#clearSearch').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Clearing...');
    $('#applySearch').prop('disabled', true);
    
    // Reset the form
    $('#formSearch')[0].reset();
    
    // Refresh the table
    table.draw();
    
    // Hide the search form after a brief delay to show the clearing process
    setTimeout(function() {
        $('#searchForm').hide();
    }, 500);
}

//Create Datatabel Function
function createDatatable(url){
    return $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true, // Defer rendering for speed
        stateSave: true, // Save state (page, filter, etc.)
        lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
        ],
        pageLength: 10, // Default page length
        ajax: {
            url: url,
            data: function (d) {
                d.prDetailId = $("#pr_detail_id").val(),
                d.search_reference = $('input[name="search_reference"]').val(),
                d.search_description = $('input[name="search_description"]').val(),
                d.search_date = $('input[name="search_date"]').val(),
                d.search_content = $('input[name="search_content"]').val(),
                d.draw = d.draw,
                d.start = d.start,
                d.length = d.length,
                d.search = d.search,
                d.order = d.order,
                d.columns = d.columns
            }
        },
        columns: [
            {data: "description", name: 'description'},
            {data: "reference_no", name: 'reference_no'},
            {data: "document_date", name: 'document_date'},
            {data: "document", name: 'document', orderable: false, searchable: false},
            {data: "copy_link", name: 'copy_link', orderable: false, searchable: false},
            @can('pr edit document')
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            @endcan
            @can('pr delete document')
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
            @endcan
        ],
        "drawCallback": function(settings) {
            // Hide loading indicators when table is drawn
            $('#searchLoading').hide();
            $('#table-loading').remove();
            
            // Re-enable buttons
            $('#applySearch').prop('disabled', false).html('Search');
            $('#clearSearch').prop('disabled', false).html('Clear');
            
            // Show search results info
            var api = this.api();
            var pageInfo = api.page.info();
            var searchTerms = getSearchTerms();
            
            if (searchTerms) {
                var resultsText = 'Showing ' + pageInfo.recordsDisplay + ' documents';
                if (pageInfo.recordsDisplay === 1) {
                    resultsText += ' matching your search: ' + searchTerms;
                } else if (pageInfo.recordsDisplay > 1) {
                    resultsText += ' matching your search: ' + searchTerms;
                } else {
                    resultsText = 'No documents found matching your search: ' + searchTerms;
                }
                
                $('#searchResultsInfo').html(resultsText).show();
            } else {
                $('#searchResultsInfo').hide();
            }
            
            if(api.rows().data().length>0){
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
        "initComplete": function(settings, json) {
            // Hide loading indicator when table is initialized
            $('#table-loading').remove();
        },
        order: [[2, 'desc']] // Default sort by date descending
    });
}

// Helper function to get search terms for results info
function getSearchTerms() {
    var terms = [];
    
    if ($('input[name="search_reference"]').val()) {
        terms.push('Reference: "' + $('input[name="search_reference"]').val() + '"');
    }
    
    if ($('input[name="search_description"]').val()) {
        terms.push('Description: "' + $('input[name="search_description"]').val() + '"');
    }
    
    if ($('input[name="search_date"]').val()) {
        terms.push('Date: "' + $('input[name="search_date"]').val() + '"');
    }
    
    if ($('input[name="search_content"]').val()) {
        terms.push('Content: "' + $('input[name="search_content"]').val() + '"');
    }
    
    return terms.join(', ');
}

//start function
$(function () {

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    table = createDatatable ("{{ route('projectDocument.create') }}");

    $('a[id^=documentList]').click(function(e) {
            $('#hideDiv').hide();
            e.preventDefault();
            $('a[id^=documentList]').not(this).find('i').attr('class', 'fa fa-folder fa-3x')

            var url = $(this).attr('href');
            var $el = $(this).find("i").toggleClass('fa-folder-open');
            if ($el.hasClass('fa-folder-open')) {
               table.destroy();
               // Show loading indicator
               $('.data-table').before('<div id="table-loading" style="text-align: center; padding: 20px;"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Loading folder contents...</div>');
               table = createDatatable (url);
            } else {
               table.destroy();
               // Show loading indicator
               $('.data-table').before('<div id="table-loading" style="text-align: center; padding: 20px;"><i class="fa fa-spinner fa-spin fa-3x"></i><br>Loading documents...</div>');
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