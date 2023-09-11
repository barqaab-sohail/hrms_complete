<div class="card-body">

    <button type="button" class="btn btn-success float-right" id="createNewMmUtilization" data-toggle="modal">Add New Utilization</button>
    <br>
    <table class="table table-striped data-table">
        <div id="dfference_div">
            <label class="control-label text-danger">Following Difference in Invoices and Utilization</label>
            <select id="difference" name="difference" class="form-control">
            </select>
        </div>

        <br>
        <a id="report_button" type="button" href="{{url('hrms/project/exportView')}}" target="_blank" class="btn btn-danger float-left">View Report</a>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Month</th>
                <th>Manmonth</th>
                <th>Billing Rate</th>
                <th>Total</th>
                <th>Remarks</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<style>
    .modal-dialog {
        max-width: 35%;
        display: flex;
    }
</style>
<div class="modal fade" id="mmUtilizationModal" aria-hidden="true">
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
                        <label class="control-label text-right">Employee Name</label><br>
                        <select name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach ($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->employee_no}}-{{$employee->full_name}}-{{$employee->employeeCurrentDesignation->name??''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Position Against Charged<span class="text_requried">*</span></label><br>
                        <select name="pr_position_id" id="pr_position_id" class="form-control" data-validation="required">
                            <option value=""></option>
                            @foreach($positions as $position)
                            <option value="{{$position->id}}" {{(old("pr_position_id")==$position->id? "selected" : "")}}>{{$position->hrDesignation->name}} (Total MM-{{$position->total_mm}})</option>
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
                                <label class="control-label text-right">Man Month<span class="text_requried">*</span></label><br>
                                <input type="number" step="0.001" id="man_month" name="man_month" value="{{ old('man_month') }}" class="form-control" data-validation-allowing="range[0.001;1.00],float">
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
                        <label class="control-label text-right">Billing Rate<span class="text_requried">*</span></label><br>
                        <input type="text" id="billing_rate" name="billing_rate" value="{{ old('billing_rate') }}" class="form-control" data-validation-allowing="range[10000;10000000],float">
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Total Amount</label><br>
                        <input type="text" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" class="form-control" readonly>
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
            console.log('ok');
            if (this.checked) {
                $("#double_charging").val('true');
            } else {
                $("#double_charging").val('false');
            }
        });
        //Enter Comma after three digit
        $('#billing_rate').keyup(function(event) {

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


        $('#man_month, #billing_rate').change(function() {
            var manMonth = $("#man_month").val();
            var billingRate = $("#billing_rate").val();
            billingRate = billingRate.replace(/\,/g, '');
            if (manMonth && billingRate) {
                var total = (manMonth * parseInt(billingRate)).toFixed(2);
                total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
                $("#total_amount").val(total);
            }
        });


        $(function() {
            $('.date-picker').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'MM yy',
                onClose: function(dateText, inst) {
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                    if ($('.date-picker').val() != '') {
                        $('.date-picker').siblings('i').show();
                    } else {
                        $('.date-picker').siblings('i').hide();
                    }
                }
            });
            $('.date-picker').siblings('i').hide();


        });


        $('#pr_position_id, #invoice_id').select2({
            dropdownParent: $('#mmUtilizationModal'),
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
                ajax: "{{ route('mmUtilization.create') }}",
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
                        data: "hr_employee_id",
                        name: 'hr_employee_id'
                    },
                    {
                        data: "pr_position_id",
                        name: 'pr_position_id'
                    },
                    {
                        data: 'month_year',
                        name: 'month_year'
                    },

                    {
                        data: 'man_month',
                        name: 'man_month'
                    },
                    {
                        data: 'billing_rate',
                        name: 'billing_rate'
                    },
                    {
                        data: 'total',
                        name: 'total'
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
                    [2, "desc"]
                ]
            });


            $('#createNewMmUtilization').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("create-Utilization");
                $('#utilization_id').val('');
                $('#charing_div').addClass('hide');
                $('#utilizationForm').trigger("reset");
                $('#pr_position_id').trigger('change');
                $('#hr_employee_id').trigger('change');
                $('#invoice_id').trigger('change');
                $('#modelHeading').html("Create New MM Utilization");
                $('#mmUtilizationModal').modal('show');
            });
            $('body').unbind().on('click', '.editMmUtilization', function() {
                var utilization_id = $(this).data('id');

                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/mmUtilization') }}" + '/' + utilization_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit MM Utilization");
                    $('#saveBtn').val("edit-MmUtilization");
                    $('#mmUtilizationModal').modal('show');
                    $('#utilization_id').val(data.id);
                    $('#hr_employee_id').val(data.hr_employee_id);
                    $('#hr_employee_id').trigger('change');
                    $('#pr_position_id').val(data.pr_position_id);
                    $('#pr_position_id').trigger('change');
                    $('#invoice_id').val(data.invoice_id);
                    $('#invoice_id').trigger('change');
                    $('#month_year').val(data.month_year);
                    $('#man_month').val(data.man_month);
                    var billingRate = data.billing_rate;
                    billingRate = billingRate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
                    $('#billing_rate').val(billingRate);
                    var total = data.billing_rate * data.man_month;
                    total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
                    $('#total_amount').val(total);
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
                    url: "{{ route('mmUtilization.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        $('#utilizationForm').trigger("reset");
                        $('#mmUtilizationModal').modal('hide');
                        table.draw();

                    },
                    error: function(data) {
                        $('.btn-prevent-multiple-submits').removeAttr('disabled');
                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                            if (value == 'This Employee already entered') {
                                $('#charing_div').removeClass('hide');
                            }
                        });
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.deleteMmUtilization', function() {

                var utilization_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('mmUtilization.store') }}" + '/' + utilization_id,
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