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
                <form id="contractorForm" name="contractorForm" action="{{route('projectContractor.store')}}" class="form-horizontal">
                    <input type="hidden" name="contractor_id" id="contractor_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-right">Contractor Name<span class="text_requried">*</span></label>
                                <input type="text" name="contractor_name" id="contractor_name" value="{{ old('contractor_name') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-right">Contract Name<span class="text_requried">*</span></label>
                                <input type="text" name="contract_name" id="contract_name" value="{{ old('contract_name') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-right">Contractor Signing Date</label><br>
                                <input type="text" id="contract_signing_date" name="contract_signing_date" value="{{ old('contract_signing_date') }}" class="form-control date_input" readonly>
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-right">Effective Date</label><br>
                                <input type="text" id="effective_date" name="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" readonly>
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-right">Contractual Completion Date</label><br>
                                <input type="text" id="contractual_completion_date" name="contractual_completion_date" value="{{ old('contractual_completion_date') }}" class="form-control date_input" readonly>
                                <br>
                                <i class="fas fa-trash-alt text_requried"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-right">Completion Period<span class="text_requried">*</span></label>
                                <input type="text" name="completion_period" id="completion_period" value="{{ old('completion_period') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label text-right">Contract Price<span class="text_requried">*</span></label>
                                <input type="text" name="contract_price" id="contract_price" value="{{ old('contractor_name') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save
                        </button>
                    </div>


                </form>
                <div class="float-right">

                    <img id="ViewIMG" src="{{asset('examples/example.jpg') }}" href="{{asset('examples/example_contract_detail.pdf') }}" width="100" />

                </div>
            </div>
        </div>
    </div>
    <script>
        formFunctions();
    </script>
</div> <!--End Modal  -->
<style>
    .modal-dialog {
        max-width: 80%;
        display: flex;
    }

    .ui-timepicker-container {
        z-index: 1151 !important;
    }
</style>
<!-- End Modal -->
<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-success float-right" id="createIssue" data-toggle="modal">Add Contractor</button>
        <h4 class="card-title" style="color:black">List of Contractors</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:20%">Contractor Name</th>
                        <th style="width:20%">Contract Name</th>
                        <th style="width:10%">Contract Price</th>
                        <th style="width:10%">Signging Date</th>
                        <th style="width:10%">Effective Date</th>
                        <th style="width:10%">Contractual Completion Date</th>
                        <th style="width:10%">Completion Period</th>
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
        $(function() {
            $('#ViewIMG').EZView();
        });



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
                    url: "{{ route('projectContractor.create') }}",
                },
                columns: [{
                        data: 'contractor_name',
                        name: 'contractor_name'
                    },
                    {
                        data: 'contract_name',
                        name: 'contract_name'
                    },
                    {
                        data: 'contract_price',
                        name: 'contract_price'
                    },
                    {
                        data: 'contract_signing_date',
                        name: 'contract_signing_date'
                    },
                    {
                        data: 'effective_date',
                        name: 'effective_date'
                    },
                    {
                        data: 'contractual_completion_date',
                        name: 'contractual_completion_date'
                    },
                    {
                        data: 'completion_period',
                        name: 'completion_period'
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
                ]
            });

            $('#createIssue').click(function(e) {
                $('#json_message_modal').html('');

                $('#contractor_id').val("");
                $('#contractorForm').trigger("reset");
                $('#ajaxModel').modal('show');


            });

            $('body').unbind().on('click', '.editContractor', function() {
                var contractor_id = $(this).data('id');
                $.get("{{ url('hrms/project/projectContractor') }}" + '/' + contractor_id + '/edit', function(data) {
                    $('#json_message_modal').html('');
                    $('#contractor_id').val(data.id);
                    $('#contractor_name').val(data.contractor_name);
                    $('#contract_name').val(data.contract_name);
                    $('#contract_signing_date').val(data.contract_signing_date);
                    $('#effective_date').val(data.effective_date);
                    $('#contractual_completion_date').val(data.contractual_completion_date);
                    $('#completion_period').val(data.completion_period);
                    $('#contract_price').val(data.contract_price);
                    $('#ajaxModel').modal('show');
                });


            });



            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');
                //submit enalbe after 3 second
                setTimeout(function() {
                    $('.btn-prevent-multiple-submits').removeAttr('disabled');
                }, 3000);

                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $('#contractorForm').serialize(),
                    url: "{{ route('projectContractor.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#ajaxModel').modal('hide');
                        $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
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

            $('body').on('click', '.deleteContractor', function() {
                var contractor_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('projectContractor.store') }}" + '/' + contractor_id,
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

    }); //End document ready function
</script>

</div>
