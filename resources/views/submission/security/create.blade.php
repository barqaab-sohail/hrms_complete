@extends('layouts.master.master')
@section('title', 'Securities')
<h3 class="text-themecolor"></h3>
@section('content')


<div class="row">
    <div class="col-lg-12">

        <div class="card card-outline-info addAjax">
            @can('personal documents')
            <div style="margin-top:10px; margin-right: 10px;">
                <button type="button" id="hideButton" class="btn btn-success float-right">Add Secuirty</button>
            </div>
            @endcan

            <div class="card-body">
                <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formDocument" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-body">

                        <h3 class="box-title">Security</h3>
                        <hr class="m-t-0 m-b-40">
                        <input type="hidden" name="security_id" id="security_id" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="control-label">Type<span class="text_requried">*</span></label>
                                    <select name="type" id="type" class="form-control selectTwo" data-validation="required">
                                        <option value="">Select Type</option>
                                        <option value="bid_security" {{ old('type') == 'bid_security' ? 'selected' : '' }}>Bid Security</option>
                                        <option value="performance_guarantee" {{ old('type') == 'performance_guarantee' ? 'selected' : '' }}>Performance Guarantee</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6 dependent">
                                <div class="form-group">

                                    <label for="bid_security_type" class="control-label">Bid Security Type</label>
                                    <select name="bid_security_type" id="bid_security_type" class="form-control selectTwo">
                                        <option value="">Select Bid Security Type</option>
                                        <option value="pay_order_cdr" {{ old('bid_security_type') == 'pay_order_cdr' ? 'selected' : '' }}>Pay Order/CDR</option>
                                        <option value="bank_guarantee" {{ old('bid_security_type') == 'bank_guarantee' ? 'selected' : '' }}>Bank Guarantee</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="control-label text-right">Security In Favor</label>
                                    <input type="text" name="favor_of" id="favor_of" value="{{ old('favor_of') }}" class="form-control" data-validation="required length" data-validation-length="max190" placeholder="Enter Security in favour of">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Amount<span
                                                class="text_requried">*</span></label><br>
                                        <input type="text" name="amount" id="amount" value="{{old('amount')}}"
                                            class="form-control" data-validation="required">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <!--/span-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Date Issued<span class="text_requried">*</span></label>
                                        <input type="text" name="date_issued" id="date_issued" value="{{ old('date_issued') }}" class="form-control date_input" data-validation="required" readonly>
                                        <br>
                                        <i class="fas fa-trash-alt text_requried"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Expiry Date<span class="text_requried">*</span></label>
                                        <input type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="form-control date_input" data-validation="required" readonly>
                                        <br>
                                        <i class="fas fa-trash-alt text_requried"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">Project Name</label>
                                    <input type="text" name="project_name" id="project_name" value="{{ old('project_name') }}" class="form-control" data-validation="required length" data-validation-length="max190" placeholder="Enter Project Name">
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label text-right">Remarks</label>
                                    <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control" data-validation="length" data-validation-length="max190" placeholder="Enter Project Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label text-right">Reference Number</label>
                                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number') }}" class="form-control" data-validation="length" data-validation-length="max190" placeholder="Enter Project Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label text-right">Status</label>
                                    <select name="status" id="status" class="form-select selectTwo" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="released" {{ old('status') == 'released' ? 'selected' : '' }}>Released</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">Client Name</label>
                                    <select name="client_id" id="client_id" class="form-select selectTwo">
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">Security Preapred By</label>
                                    <select name="submitted_by" id="submitted_by" class="form-select selectTwo">
                                        <option value="">Select Name of Firm</option>
                                        @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}" {{ old('submitted_by') == $partner->id ? 'selected' : '' }}>{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-right">Bank</label>
                                    <select name="bank_id" id="bank_id" class="form-select selectTwo">
                                        <option value="">Select Bank</option>
                                        @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>
                                        <hr>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class=" d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <!-- Excel Export Button -->
                        <a href="{{ route('securities.export.excel') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>

                        <!-- PDF Export Button -->
                        <a href="{{ route('securities.export.pdf') }}" class="btn btn-danger ml-2">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <table class="table table-bordered data-table" width=100%>
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Type</th>
                            <th>Security Provided By</th>
                            <th>Issued Date</th>
                            <th>Expiry Date</th>
                            <th>Amount</th>
                            <th>Status</th>
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
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
       
        // show and hide bid security type
        $('.dependent').hide();
        $('#type').change(function() {
            var type = $(this).val();
            if (type == '') {
                $('.dependent').hide();
                $('#bid_security_type').removeAttr('data-validation');
            } else if (type == 'performance_guarantee') {
                $('.dependent').hide();
                $('#bid_security_type').removeAttr('data-validation');
            } else if (type == 'bid_security') {
                $('.dependent').show();
                $('#bid_security_type').attr('data-validation', 'required');
            } else {
                $('.dependent').hide();
                $('#bid_security_type').removeAttr('data-validation');
            }
        });

        //only number value entered
        $('#amount').on('change, keyup', function() {
            var currentInput = $(this).val();
            var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
            $(this).val(fixedInput);
        });

        //Enter Comma after three digit
        $('#amount').keyup(function(event) {

            // skip for arrow keys
            if (event.which >= 37 && event.which <= 40) return;

            // format number
            $(this).val(function(index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });
        });


        (function() {
            $("#formDocument").on("submit", function() {
                $(".btn-prevent-multiple-submits").attr("disabled", "ture");
                $(".fa-spinner").show();
                //submit enalbe after 3 second
                // setTimeout(function() {
                //     $(".btn-prevent-multiple-submits").removeAttr("disabled");
                // }, 3000);
            });
        })();

        formFunctions();

        $('#formDocument').hide();

        $("#pdf").hide();
        // Prepare the preview for profile picture
        $("#view").change(function() {
            var fileName = this.files[0].name;
            var fileType = this.files[0].type;
            var fileSize = this.files[0].size;
            //var fileType = fileName.split('.').pop();
            var size = $("#size").val();
            if (size == '') {
                size = 3840000;
            }
            //Restrict File Size Less Than 30MB
            if (fileSize > size) {
                alert('File Size is bigger than 3MB');
                $(this).val('');
            } else {
                //Restrict File Type
                if (fileType == 'application/pdf') {
                    readURL(this); // for Default Image
                    document.getElementById("h6").innerHTML = "PDF Document is Attached";
                    document.getElementById("pdf").src = "{{asset('Massets/images/document.png')}}";
                    $("#pdf").show();
                }
                // else if ((fileType == 'application/msword') || (fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                //     $("#pdf").hide();
                //     document.getElementById("h6").innerHTML = "MS Word Document is Attached";
                // } else if ((fileType == 'image/jpeg') || (fileType == 'image/png')) {
                //     $("#pdf").hide();
                //     readURL(this);
                //     document.getElementById("h6").innerHTML = "Image is Attached";
                // } else if (fileType == 'application/vnd.ms-excel' || fileType == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                //     $("#pdf").hide();
                //     document.getElementById("h6").innerHTML = "MS Excel Document is Attached";
                // } 
                else {
                    alert('Only PDF Allowed');
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

    }); //end document ready



    //Create Datatabel Function
    function createDatatable(url) {
        return $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All'],
            ],
            ajax: {
                url: url,

            },
            columns: [{
                    data: "project_name",
                    name: 'project_name'
                },
                {
                    data: "type",
                    name: 'type'
                },
                {
                    data: "submitted_by",
                    name: 'submitted_by'
                },
                {
                    data: "date_issued",
                    name: 'date_issued'
                },
                {
                    data: "expiry_date",
                    name: 'expiry_date'
                },
                {
                    data: "amount",
                    name: 'amount'
                },
                {
                    data: "status",
                    name: 'status'
                },
                {
                    data: "document_path",
                    name: 'document_path'
                },
                {
                    data: "copy_link",
                    name: 'copy_link'
                },
                {
                    data: 'edit',
                    name: 'edit',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'delete',
                    name: 'delete',
                    orderable: false,
                    searchable: false
                },
            ],
            "drawCallback": function(settings) {
                // Check if elements exist before calling EZView
                var $viewElements = $("[id^='ViewIMG'], [id^='ViewPDF']");
                if ($viewElements.length > 0) {
                    $viewElements.EZView();
                }

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
            order: [
                [1, "desc"]
            ]
        });

    }

    //start function
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = createDatatable("{{ route('securities.index') }}");


        $('#hideButton').click(function() {
            $('#pdf').attr('src', '').hide();
            $('#wizardPicturePreview').attr('src', "{{asset('Massets/images/document.png')}}");
            $('#h6').html("Click On Image to Add Pdf Document<span class='text_requried'>*</span>");
            $('#formDocument').toggle();
            $('#formDocument').trigger("reset");
            $('#security_id').val('');
            $('#client_id').val('').trigger('change');
            $('#submitted_by').val('').trigger('change');
            $('#bank_id').val('').trigger('change');
            $('#type').val('').trigger('change');
            $('#bid_security_type').val('').trigger('change');;

        });

        $('body').unbind().on('click', '.editSecurity', function() {
            var security_id = $(this).data('id');

            $.get("{{ url('hrms/securities') }}" + '/' + security_id + '/edit', function(data) {
              
                $('#formDocument').toggle();
                $('#formDocument').trigger("reset");

                $('#formDocument').show();

                $('#security_id').val(data.id);
                $('#type').val(data.type).trigger('change');
                $('#bid_security_type').val(data.bid_security_type).trigger('change');
                $('#favor_of').val(data.favor_of);

             
                $('#date_issued').val(data.date_issued);
                $('#expiry_date').val(data.expiry_date);

                $('#amount').val(data.amount);
                $('#project_name').val(data.project_name);
                $('#remarks').val(data.remarks);
                $('#reference_number').val(data.reference_number);
                $('#status').val(data.status).trigger('change');
                $('#client_id').val(data.client_id).trigger('change');
                $('#submitted_by').val(data.submitted_by).trigger('change');
                $('#bank_id').val(data.bank_id).trigger('change');


                $("#pdf").show();
                $('#pdf').attr('src', "{{ asset('storage') }}/" + data.document_path);
                $('#wizardPicturePreview').attr('src', "{{asset('Massets/images/document.png')}}");


                $('#view').removeAttr('data-validation');


            })
        });

        $("#formDocument").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                data: formData,
                url: "{{ route('securities.store') }}",
                type: "POST",
                //dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.error) {
                        $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                    } else {

                        $('#formDocument').trigger("reset");
                        $('#formDocument').toggle();
                        $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.message + '</strong></div>');

                        table.draw();
                        clearMessage();
                        $(".btn-prevent-multiple-submits").removeAttr("disabled");
                        $(".fa-spinner").hide();
                    }

                },
                error: function(data) {

                    var errorMassage = '';
                    $.each(data.responseJSON.errors, function(key, value) {
                        errorMassage += value + '<br>';
                    });
                    $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                    $('#saveBtn').html('Save Changes');
                    $(".fa-spinner").hide();
                }
            });
        });

        $('body').on('click', '.deleteSecurity', function() {

            var security_id = $(this).data("id");

            var con = confirm("Are You sure want to delete !");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('securities.store') }}" + '/' + security_id,
                    success: function(data) {
                        table.draw();
                        clearMessage();
                        if ($('#formDocument:visible').length != 0) {
                            $('#formDocument').toggle();
                        }

                        if (data.status == "Not OK") {
                            $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.message + '</strong></div>');
                        } else {
                            $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.message + '</strong></div>');
                        }
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
</script>
@stop