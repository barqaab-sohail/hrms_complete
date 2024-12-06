<div class="card-body">

    <button type="button" class="btn btn-success float-right" id="createNewSalary" data-toggle="modal">Add New
        Salary</button>
    <br>
    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>Salary</th>
                <th>Gross Salary</th>
                <th>Effective Date</th>
                @foreach($selectedAllowances as $allowanceName)
                <th>{{$allowanceName->name}}</th>
                @endforeach
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden
                        class="fas fa-times float-right"></i> </div>
                <form id="salaryForm" name="salaryForm" action="{{route('employeeSalary.store')}}"
                    class="form-horizontal">
                    <input type="hidden" name="employee_salary_id" id="employee_salary_id">
                    <input type="hidden" name="hr_employee_id" id="hr_employee_id" value="{{$id}}">
                    <div class="form-group">
                        <label class="control-label text-right">Total Salary<span
                                class="text_requried">*</span></label><br>
                        <input type="text" name="hr_salary" id="hr_salary" value="{{old('hr_salary')}}"
                            class="form-control" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Effective Date<span
                                class="text_requried">*</span></label>

                        <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}"
                            class="form-control date_input" data-validation="required" readonly>

                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                    </div>

                    <div class="allowance" id="allowance_1">
                        <button type="button" name="add" class="btn btn-success add_allowance">+</button>
                        <div class="form-group">
                            <input type="hidden" name="hr_allowance_id" id="hr_allowance_id_1" class="hr_allowance_id">
                            <label class="control-label text-right">Allowance Name</label>
                            <select name="hr_allowance_name_id[]" id="hr_allowance_name_id_1"
                                class="form-control hr_allowance_name">
                                <option value=""></option>
                                @foreach($allowanceNames as $allowance)
                                <option value="{{$allowance->id}}"
                                    {{(old("hr_allowance_name_id.0")==$allowance->id? "selected" : "")}}>
                                    {{$allowance->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-right">Allowance Amount</label><br>
                            <input type="text" name="amount[]" id="amount_1" value="{{old('amount')}}"
                                class="form-control hr_allowance_amount">
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn"
                            value="create">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

    //Dynamic add membership
    // Add new element
    $(".add_allowance").click(function() {

        // Finding total number of elements added
        var total_element = $(".allowance").length;

        // last <div> with element class id
        var lastid = $(".allowance:last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[1]) + 1;
        var max = 5;
        // Check total number elements
        if (total_element < max) {
            //Clone specialization div and copy hr_allowance_name_id_1
            $('.allowance').find('select').chosen('destroy');
            var $clone = $("#allowance_1").clone();
            $clone.prop('id', 'allowance_' + nextindex).find('input:text').val('');
            $clone.find(".hr_allowance_name").prop('id', 'hr_allowance_name_id_' + nextindex);
            $clone.find(".hr_allowance_id").prop('id', 'hr_allowance_id_' + nextindex);
            $clone.find(".hr_allowance_amount").prop('id', 'amount_' + nextindex).find('input:text')
                .val('');;
            $clone.find(".add_allowance").html('X').prop("class",
                "btn btn-danger remove remove_allowance");
            $clone.find('.remove_div').remove();
            $clone.insertAfter("div.allowance:last");
            $('.allowance').find('select').chosen();

        }

    });
    // Remove element
    $(document).on("click", '.remove_allowance', function() {
        $(this).closest(".allowance").remove();

    });


    //only number value entered
    $('#hr_salary, #amount').on('change, keyup', function() {
        var currentInput = $(this).val();
        var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
        $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#hr_salary, #amount').keyup(function(event) {

        // skip for arrow keys
        if (event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
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
            ajax: {
                url: "{{ route('employeeSalary.create') }}",
                data: {
                    hrEmployeeId: $("#hr_employee_id").val()
                }
            },
            columns: [{
                    data: "salary",
                    name: 'salary'
                },
                {
                    data: "gross_salary",
                    name: 'gross_salary'
                },
                {
                    data: 'effective_date',
                    name: 'effective_date'
                },
                @foreach($selectedAllowances as $allowanceName) {
                    data: '{{$allowanceName->name}}',
                    name: '{{$allowanceName->name}}'
                },
                @endforeach

                {
                    data: 'Edit',
                    name: 'Edit',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'Delete',
                    name: 'Delete',
                    orderable: false,
                    searchable: false
                },

            ],
            order: [
                [2, "desc"]
            ],

        });

        $('#createNewSalary').click(function() {
            // $("select").prepend("<option value='' >&nbsp;</option>");
            $('select').chosen({
                width: "100%",
                allow_single_deselect: true
            });
            $('.remove_allowance').click();
            // $("select").val('').trigger('change');
            $('#json_message_modal').html('');
            $('#saveBtn').val("create-Salary");
            $('#employee_salary_id').val('');
            $('#hr_allowance_name_id_1').val('').trigger('chosen:updated');
            $('#salaryForm').trigger("reset");
            $('#hr_salary').val('');
            $('#modelHeading').html("Create New Salary");
            $('#ajaxModel').modal('show');
        });
        $('body').unbind().on('click', '.editSalary', function() {
            var employee_salary_id = $(this).data('id');

            $('#json_message_modal').html('');
            $.get("{{ url('hrms/employeeSalary') }}" + '/' + employee_salary_id + '/edit',
                function(data) {

                    $('.remove_allowance').click();
                    $('#salaryForm').trigger("reset");
                    $('#hr_allowance_name_id_1').val('').trigger('chosen:updated');
                    $('#hr_salary').val('');
                    $.each(data.hr_allowances,
                        function(key, value) {
                            if (key != 0) {
                                $(".add_allowance").click();
                            }
                            $('#hr_allowance_name_id_' + (key + 1)).val(value
                                .hr_allowance_name_id);
                            $('#hr_allowance_id_' + (key + 1)).val(value.id);
                            $('#amount_' + (key + 1)).val(value.amount);
                        });

                    $('.hr_allowance_name').chosen('destroy');
                    $('.hr_allowance_name').chosen({
                        width: "100%",
                        allow_single_deselect: true
                    });
                    $('#modelHeading').html("Edit Salary");
                    $('#saveBtn').val("edit-Salary");
                    $('#ajaxModel').modal('show');
                    $('#employee_salary_id').val(data.id);
                    var totalSalary = data.hr_salary.total_salary?.toString().replace(
                        /\B(?=(\d{3})+(?!\d))/g, ",") ?? '';
                    $('#hr_salary').val(totalSalary);
                    $('#effective_date').val(data.effective_date);
                })
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
                data: $('#salaryForm').serialize(),
                url: "{{ route('employeeSalary.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#salaryForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                    clearMessage();
                },
                error: function(data) {

                    var errorMassage = '';
                    $.each(data.responseJSON.errors, function(key, value) {
                        errorMassage += value + '<br>';
                    });
                    $('#json_message_modal').html(
                        '<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                        errorMassage + '</strong></div>');

                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteSalary', function() {

            var employee_salary_id = $(this).data("id");
            var con = confirm("Are You sure want to delete !");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('employeeSalary.store') }}" + '/' +
                        employee_salary_id,
                    data: {
                        hrEmployeeId: $("#hr_employee_id").val()
                    },
                    success: function(data) {
                        console.log('data');
                        table.draw();
                        clearMessage();
                        if (data.error) {
                            $('#json_message').html(
                                '<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                                data.error + '</strong></div>');
                        }

                    },
                    error: function(data) {
                        console.log('error...');
                    }
                });
            }
        });

    });
});
</script>