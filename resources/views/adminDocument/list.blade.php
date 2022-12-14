@extends('layouts.master.master')
@section('title', 'Admin Documents')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<!-- Model -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="documentForm" name="documentForm" action="{{route('submission.store')}}" class="form-horizontal">
                    <input type="hidden" name="document_id" id="document_id">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Reference No</label>
                                <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}" class="form-control exempted" data-validation="length" data-validation-length="max190" placeholder="Enter Document Reference">
                                <div id="check_reference"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                                <input type="text" name="document_date" id="document_date" value="{{ old('document_date') }}" class="form-control date_input" data-validation="required" readonly placeholder="Enter Document Detail">
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-right">Document Description</label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control" data-validation="required length" data-validation-length="max190" placeholder="Enter Document Detail">
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

                                    <input type="file" name="document" id="view" data-validation="required" class="" hidden />

                                    <h6 id="h6" class="card-title m-t-10">Click On Image to Add Document<span class="text_requried">*</span></h6>

                                </center>

                            </div>

                        </div>

                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        formFunctions();
    </script>
</div>
<!--End Modal  -->
<style>
    .modal-dialog {
        max-width: 80%;
        display: flex;
    }

    .ui-timepicker-container {
        z-index: 1151 !important;
    }
</style>

<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-success float-right" id="createDocument" data-toggle="modal">Add Document</button>
        <h4 class="card-title" style="color:black">List of Admin Documents</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:10%">Reference No</th>
                        <th style="width:10%">Date</th>
                        <th style="width:55%">Description</th>
                        <th style="width:10%">View</th>
                        <th style="width:5%">Copy Link</th>
                        <th style="width:5%">Edit</th>
                        @role('Super Admin')
                        <th style="width:5%">Delete</th>
                        @endrole
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#reference_no').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('adminDocument.reference') }}",
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

        $('#hideDiv').hide();

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                "aaSorting": [],
                ajax: {
                    url: "{{ route('adminDocument.index') }}",
                },
                columns: [{
                        data: 'reference_no',
                        name: 'reference_no'
                    },
                    {
                        data: 'document_date',
                        name: 'document_date'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'view',
                        name: 'view'
                    },
                    {
                        data: 'link',
                        name: 'link'
                    },
                    {
                        data: 'edit',
                        name: 'edit',
                        orderable: false,
                        searchable: false
                    },
                    @role('Super Admin') {
                        data: 'delete',
                        name: 'delete',
                        orderable: false,
                        searchable: false
                    }
                    @endrole
                ],
                "drawCallback": function(settings) {
                    $("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
                },
            });

            $('#createDocument').click(function(e) {
                $('#json_message_modal').html('');
                $('#document_id').val('');
                $('#documentForm').trigger("reset");
                $('#wizardPicturePreview').attr('src', "{{asset('Massets/images/document.png')}}").attr('width', '30%');
                document.getElementById("h6").innerHTML = "Click On Image to Add Document";
                $("#pdf").hide();
                // Prepare the preview for profile picture
                view();
                $('#ajaxModel').modal('show');

            });


            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');
                //submit enalbe after 3 second
                setTimeout(function() {
                    $('.btn-prevent-multiple-submits').removeAttr('disabled');
                }, 3000);

                e.preventDefault();
                $(this).html('Save');
                var data = new FormData($("#documentForm")[0]);

                $.ajax({
                    data: data,
                    url: "{{ route('adminDocument.store') }}",
                    type: "POST",
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    // dataType: 'json',
                    success: function(data) {
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                        });
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });


            $('body').on('click', '.editDocument', function() {
                var document_id = $(this).data("id");
                $('#json_message_modal').html('');
                $('#documentForm').trigger("reset");
                $.get("{{ url('hrms/adminDocument') }}" + '/' + document_id + '/edit', function(data) {
                    $('#document_id').val(data.id);
                    $('#reference_no').val(data.reference_no);
                    $('#document_date').val(data.document_date);
                    $('#description').val(data.description);
                });
                view();
                $('#ajaxModel').modal('show');


            });
            $('body').on('click', '.copyLink', function() {
                var text = $(this).data("link").replace(" ", "%20");
                navigator.clipboard.writeText(text);
                alert('Link Copied');
            });


            $('body').on('click', '.deleteDocument', function() {
                var document_id = $(this).data("id");
                console.log('testing');
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('adminDocument.store') }}" + '/' + document_id,
                        success: function(data) {
                            table.draw();
                            if (data.error) {
                                $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                            }

                        },
                        error: function(data) {

                        }
                    });
                }
            });

        }); // end function

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

        function view() {
            $("#view").change(function() {
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;

                //Restrict File Size Less Than 30MB
                if (fileSize > 38400000) {
                    alert('File Size is bigger than 30MB');
                    $(this).val('');
                } else {
                    //Restrict File Type
                    if ((fileType == 'application/msword') || (fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                        $("#pdf").hide();
                        document.getElementById("h6").innerHTML = "MS Word Document is Attached";
                    } else if ((fileType == 'image/jpeg') || (fileType == 'image/png')) {
                        $("#pdf").hide();
                        readURL(this);
                        document.getElementById("h6").innerHTML = "Image is Attached";
                    } else if (fileType == 'application/pdf') {
                        readURL(this); // for Default Image
                        document.getElementById("h6").innerHTML = "PDF Document is Attached";
                        document.getElementById("pdf").src = "{{asset('Massets/images/document.png')}}";
                        $("#pdf").show();
                    } else {
                        alert('Only PDF, JPG and PNG Files Allowed');
                        $(this).val('');
                    }
                }

            });
        }
    }); //End document ready function
</script>

</div>

@stop
