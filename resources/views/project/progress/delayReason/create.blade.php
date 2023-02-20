<!-- Model -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Delay Reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="delayReasonForm" name="delayReasonForm" action="{{route('delayReason.store')}}" class="form-horizontal">
                    <input type="hidden" name="delay_reason_id" id="delay_reason_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Contractor<span class="text_requried">*</span></label>
                                <select id="pr_contractor_id" name="pr_contractor_id" class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($prContractors as $prContractor)
                                    <option value="{{$prContractor->id}}" {{(old("pr_contractor_id")==$prContractor->id? "selected" : "")}}>{{$prContractor->contract_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-right">Reason<span class="text_requried">*</span></label>
                                <textarea rows=5 cols=5 id="reason" name="reason" class="form-control ">{{ old('reason') }}</textarea>
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
        <button type="button" class="btn btn-success float-right" id="createDelayReason" data-toggle="modal">Add Reason</button>
        <h4 class="card-title" style="color:black">List of Delay Reasons</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:20%">Contract Name</th>
                        <th style="width:70%">Delay Reason</th>
                        <th style="width:5%">Edit</th>
                        <th style="width:5%">Delete</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('delayReason.create') }}",
                },
                columns: [{
                        data: 'contract_name',
                        name: 'contract_name'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
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
                    }

                ]
            });

            $('#createDelayReason').click(function(e) {
                $('#json_message_modal').html('');
                $('#pr_contractor_id').val('').trigger('change');
                $('#delay_reason_id').val("");
                $('#delayReasonForm').trigger("reset");
                $('#ajaxModel').modal('show');


            });

            $('body').unbind().on('click', '.editDelayReason', function() {
                var delay_reason_id = $(this).data('id');
                $.get("{{ url('hrms/project/delayReason') }}" + '/' + delay_reason_id + '/edit', function(data) {
                    $('#json_message_modal').html('');
                    $('#delay_reason_id').val(data.id);
                    $('#pr_contractor_id').val(data.pr_contractor_id).trigger('change');;
                    $('#reason').val(data.reason);
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
                    data: $('#delayReasonForm').serialize(),
                    url: "{{ route('delayReason.store') }}",
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

            $('body').on('click', '.deleteDelayReason', function() {
                var delay_reason_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('delayReason.store') }}" + '/' + delay_reason_id,
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