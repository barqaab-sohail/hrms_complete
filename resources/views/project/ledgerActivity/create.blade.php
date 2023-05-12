<div class="card-body">
    <div class="row">
        <div class="col-md-3">
            <button type="button" class="btn btn-success reload float-right mb-3" hidden>Reload</button>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-success float-right" id="syncLedgerActivity" data-toggle="modal">Sync Ledger with Oracle<i class="fa fa-spinner fa-spin" style="font-size:18px"></i></button>

        </div>
    </div>
    <br>
    <!-- @include('project.expense.import') -->

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>Voucher Date</th>
                <th>Voucher No</th>
                <th>Reference Date</th>
                <th>Reference No</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <th style="text-align:right">Page Total:</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>


<script type="text/javascript">
    $(document).ready(function() {



        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Monthly Expense Detail',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        footer: true,
                        title: 'Monthly Expense Detail',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                ],
                ajax: "{{ route('projectLedgerActivity.create') }}",
                columns: [{
                        data: "voucher_date",
                        name: 'voucher_date'
                    },
                    {
                        data: "voucher_no",
                        name: 'voucher_no'
                    },
                    {
                        data: "reference_date",
                        name: 'reference_date'
                    },
                    {
                        data: "reference_no",
                        name: 'reference_no'
                    },
                    {
                        data: "description",
                        name: 'description'
                    },
                    {
                        data: "debit",
                        name: 'debit'
                    },
                    {
                        data: "credit",
                        name: 'credit'
                    },
                    {
                        data: "balance",
                        name: 'balance'
                    },
                    {
                        data: "remarks",
                        name: 'remarks'
                    },


                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };

                    // Total over all pages
                    total5 = api
                        .column(5)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0).toLocaleString("en-US");
                    total6 = api
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0).toLocaleString("en-US");
                    total7 = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0).toLocaleString("en-US");
                    // Total over this page
                    pageTotal = api
                        .column(1, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(5).footer()).html(total5);
                    $(api.column(6).footer()).html(total6);
                    $(api.column(7).footer()).html(total7);
                },

                order: [

                ]
            });
            $(".reload").click(function() {
                table.ajax.reload(null, false);
            });


            $('#syncLedgerActivity').on('click', function(event) {
                $('.fa-spinner').show();
                $('#syncLedgerActivity').attr('disabled', 'disabled');
                var url = "{{route('project.importLedgerActivity')}}"
                event.preventDefault();
                //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
                $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
                    var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

                    if (token) {
                        return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
                    }
                });
                var data = '';
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    //dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $('.fa-spinner').hide();
                        $('#syncLedgerActivity').removeAttr("disabled");
                        if (data.error) {
                            $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');

                        } else {


                            $('#json_message').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
                            $('.reload').click();
                        }
                    },
                    error: function(data) {
                        $('.fa-spinner').hide();
                        $('#syncLedgerActivity').removeAttr("disabled");
                        var errorMassage = '';
                        $.each(data.responseJSON.errors, function(key, value) {
                            errorMassage += value + '<br>';
                        });
                        $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

        });
    });
</script>