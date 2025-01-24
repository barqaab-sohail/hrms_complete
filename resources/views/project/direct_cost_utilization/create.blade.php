<div class="card-body">

    <button type="button" class="btn btn-success float-right" id="createNewUtilization" data-toggle="modal">Add New Utilization</button>
    <br>
    <table class="table table-striped data-table">
        <div id="dfference_div">
            <label class="control-label text-danger">Following Difference in Invoices and Utilization</label>
            <select id="difference" name="difference" class="form-control">
            </select>
        </div>
        <br>
        <a id="report_button" type="button" href="{{url('hrms/project/exportViewDirectCost')}}" target="_blank" class="btn btn-danger float-left">View Report</a>
        <thead>
            <tr>
                <th>Description</th>
                <th>Month</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="directCostUtilizationModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="utilizationForm" name="utilizationForm" class="form-horizontal">
                    <input type="hidden" name="utilization_id" id="utilization_id">
                    <div class="form-group">
                        <label class="control-label text-right">Direct Cost Description</label><br>
                        <select name="direct_cost_detail_id" id="direct_cost_detail_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach ($directCostDetails as $detail)
                            <option value="{{$detail->id}}">{{$detail->directCostDescription->name??''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Invoice No<span class="text_requried">*</span></label><br>
                        <select name="invoice_id" id="invoice_id" class="form-control" data-validation="required">
                            <option value=""></option>
                            @foreach($invoices as $invoice)
                            <option value="{{$invoice->id}}" {{(old("invoice_id")==$invoice->id? "selected" : "")}}>{{$invoice->invoice_no??''}} - {{$invoice->invoiceMonth->invoice_month}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <label class="control-label">Invoice Month</label> -->
                    <input type="text" name="month_year" id="month_year" value="{{ old('month_year') }}" class="form-control" data-validation="required" hidden readonly>
                    <div class="row">
                    <div class="col-md-5">
                    <div class="form-group">
                        <label class="control-label text-right">Amount<span class="text_requried">*</span></label><br>
                        <input type="text" id="amount" name="amount" value="{{ old('amount') }}" class="form-control" data-validation-allowing="range[10000;10000000],float">
                    </div>
                    </div>
                    <div class="col-md-7" id="charing_div">
                            <div class="form-check">
                                <br>
                                <input class="form-check-input float-right" type="checkbox" name="double_charging" value="true" id="double_charging" checked>
                                <label class="form-check-label float-right" for="double_charging">
                                   Avoid Double Charging
                                </label>
                            </div>
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Remarks</label>
                        <input type="text" id="remarks" name="remarks" value="{{ old('remarks') }}" class="form-control">
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
        $("#dfference_div").hide();
        $("#report_button").hide();

        $("#double_charging").change(function() {
          if (this.checked) {
              $("#double_charging").val('true');
          } else {
              $("#double_charging").val('false');
          }
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

        $("#invoice_id").change(function() {
            var invoiceId = $('#invoice_id option').filter(':selected').val();
            if (invoiceId) {
                $.get("{{ url('hrms/project/invoice') }}" + '/' + invoiceId, function(data) {
                    $('#month_year').val(data.invoice_month);
                })
            }


        });



        $('#invoice_id').select2({
            dropdownParent: $('#directCostUtilizationModal'),
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
                ajax: "{{ route('prDirectCostUtilization.create') }}",
                drawCallback: function(data) {
                    if (data.json.count > 0) {
                        $("#report_button").show();
                    } else {
                        $("#report_button").hide();
                    }
                    if (data.json.difference.length > 0) {
                        $("#dfference_div").show();
                    } else {
                        $("#dfference_div").hide();
                    }
                    $("#difference").empty();
                    // $("#cycle").append('<option value=""></option>');
                    $.each(data.json.difference, function(key, value) {
                        $("#difference").append('<option >' + value['month'] + ' - Invoice Value =' + value['cost'] + ' - Utilization Value =' + value['utilization'] +
                            ' - Total Difference = ' + value['difference'] +
                            '</option>');
                    });
                    $('#difference').select2({
                        width: "100%",
                        theme: "classic"
                    });
                },
                columns: [{
                        data: "direct_cost_detail_id",
                        name: 'direct_cost_detail_id'
                    },
                    {
                        data: 'month_year',
                        name: 'month_year'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
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
                order: [
                    [1, "desc"]
                ]
            });

            $('#createNewUtilization').click(function() {
                $('#json_message_modal').html('');
                $('#charing_div').addClass('hide');
                $('#saveBtn').val("create-Utilization");
                $('#utilization_id').val('');
                $('#utilizationForm').trigger("reset");
                $('#direct_cost_detail_id').trigger('change');
                $('#invoice_id').trigger('change');
                $('#modelHeading').html("Create New Utilization");
                $('#directCostUtilizationModal').modal('show');
            });
            $('body').unbind().on('click', '.editUtilization', function() {
                var utilization_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/prDirectCostUtilization') }}" + '/' + utilization_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Utilization");
                    $('#saveBtn').val("edit-Utilization");
                    $('#directCostUtilizationModal').modal('show');
                    $('#utilization_id').val(data.id);
                    $('#direct_cost_detail_id').val(data.direct_cost_detail_id);
                    $('#direct_cost_detail_id').trigger('change');
                    $('#invoice_id').val(data.invoice_id);
                    $('#invoice_id').trigger('change');
                    $('#month_year').val(data.month_year);
                    var amount = data.amount;
                    amount = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
                    $('#amount').val(amount);
                    $('#remakrs').val(data.remakrs);
                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');

                e.preventDefault();
                $(this).html('Save');
                console.log($('#utilizationForm').serialize());
                $.ajax({
                    data: $('#utilizationForm').serialize(),
                    url: "{{ route('prDirectCostUtilization.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#utilizationForm').trigger("reset");
                        $('#directCostUtilizationModal').modal('hide');
                        table.draw();

                    },
                    error: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                            if (value == 'The month year has already been taken.' ) {
                                $('#charing_div').removeClass('hide');
                            }
                        });
                        
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteUtilization', function() {

                var utilization_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('prDirectCostUtilization.store') }}" + '/' + utilization_id,
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