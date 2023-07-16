<div class="card-body">
    <button type="button" class="btn btn-success float-right" id="createDetail" data-toggle="modal">Add New Position</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Direct Cost Description</th>
                <th>Amount</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="detailModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="detailForm" name="detailForm" class="form-horizontal">
                    <input type="hidden" name="direct_cost_detail_id" id="direct_cost_detail_id">
                    <div class="form-group">
                        <label class="control-label text-right">Description</label><br>
                        <select name="direct_cost_description_id" id="direct_cost_description_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach ($directCostDescription as $description)
                            <option value="{{$description->id}}">{{$description->name??''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Amount<span class="text_requried">*</span></label>
                        <input type="text" id="amount" name="amount" value="{{ old('amount') }}" class="form-control" data-validation-allowing="range[1000;90000000],float">
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {


        $('#direct_cost_description_id').select2({
            dropdownParent: $('#detailModal'),
            width: "100%",
            theme: "classic"
        });

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('directCostDetail.create') }}",
                columns: [{
                        data: "direct_cost_description_id",
                        name: 'direct_cost_description_id'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
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

                ],
            });

            $('#createDetail').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-Position");
                $('#direct_cost_detail_id').val('');
                $('#detailForm').trigger("reset");
                $('#direct_cost_description_id').trigger('change');
                $('#modelHeading').html("Create New Detail");
                $('#detailModal').modal('show');
            });
            $('body').unbind().on('click', '.editDetail', function() {
                var direct_cost_detail_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/directCostDetail') }}" + '/' + direct_cost_detail_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Detail");
                    $('#saveBtn').val("edit-Detail");
                    $('#detailModal').modal('show');
                    $('#direct_cost_detail_id').val(data.id);
                    $('#direct_cost_description_id').val(data.direct_cost_description_id);
                    $('#direct_cost_description_id').trigger('change');
                    $('#amount').val(data.amount);
                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');


                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $('#detailForm').serialize(),
                    url: "{{ route('directCostDetail.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#detailForm').trigger("reset");
                        $('#detailModal').modal('hide');
                        table.draw();

                    },
                    error: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                        });
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteDetail', function() {

                var direct_cost_detail_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('directCostDetail.store') }}" + '/' + direct_cost_detail_id,
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

        });
    });
</script>
