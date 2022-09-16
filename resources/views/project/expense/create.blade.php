<div class="card-body">
  <button type="button" class="btn btn-success float-right" id="createExpense" data-toggle="modal">Add Expense</button>
  <br>
  Total Expenses = {{$totalExpenses}} ::
  Payment Received = {{$totalReceived}} ::
  Pending Invoices without Sales Tax = {{$pendingInvoicesWOSTax?$pendingInvoicesWOSTax:'No Pending Invoice'}}
  <table class="table table-bordered data-table">
    <thead>
      <tr>
        <th>Month</th>
        <th>Salary Expense</th>
        <th>Non Salary Expense</th>
        <th>Total Expense</th>
        <th>Remarks</th>
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
        <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
        <form id="ExpenseForm" name="ExpenseForm" class="form-horizontal">

          <input type="hidden" name="expense_id" id="expense_id">

          <div class="form-group">
            <label class="control-label text-right">Month<span class="text_requried">*</span></label>
            <input type="text" id="month" name="month" value="{{ old('month') }}" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label class="control-label">Salary Expense<span class="text_requried">*</span></label>
            <input type="text" name="salary_expense" id="salary_expense" value="{{old('salary_expense')}}" class="form-control prc_1" data-validation="required">
          </div>

          <div class="form-group">
            <label class="control-label">Non Salary Expense<span class="text_requried">*</span></label>
            <input type="text" name="non_salary_expense" id="non_salary_expense" value="{{old('non_salary_expense')}}" class="form-control prc_1">
          </div>
          <div class="form-group">
            <label class="control-label">Total Expenses</label>
            <input type="text" name="total_expenses" id="total_expenses" value="{{old('total_expenses')}}" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label class="control-label text-right">Remarks</label>
            <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control">
          </div>


          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {

    //automatic total
    $(".form-group").on("input", ".prc_1", function() {
      var sum = 0;
      $(".form-group .prc_1").each(function() {
        var inputVal = $(this).val();
        inputVal = inputVal.replace(/\,/g, '') // remove comma
        if ($.isNumeric(inputVal)) {
          sum += parseFloat(inputVal);
        }
      });
      sum = sum?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") ?? ''; //add comma
      $("#total_expenses").val(sum);
    });

    //only number value entered
    $('#salary_expense, #non_salary_expense').on('change, keyup', function() {
      var currentInput = $(this).val();
      var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
      $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#salary_expense, #non_salary_expense').keyup(function(event) {

      // skip for arrow keys
      if (event.which >= 37 && event.which <= 40) return;

      // format number
      $(this).val(function(index, value) {
        return value
          .replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      });
    });

    $('#month').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'MM yy',

      onClose: function() {
        var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
      },

      beforeShow: function() {
        if ((selDate = $(this).val()).length > 0) {
          iYear = selDate.substring(selDate.length - 4, selDate.length);
          iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
            $(this).datepicker('option', 'monthNames'));
          $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
          $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
        }
      }
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
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, 'All'],
        ],
        dom: 'Blfrtip',
        buttons: [{
            extend: 'copyHtml5',
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          },
          {
            extend: 'excelHtml5',
            title: 'Monthly Expense Detail',
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          },
          {
            extend: 'pdfHtml5',
            title: 'Monthly Expense Detail',
            exportOptions: {
              columns: [0, 1, 2, 3, 4]
            }
          },
        ],
        ajax: "{{ route('projectMonthlyExpense.create') }}",
        columns: [{
            data: "month",
            name: 'month'
          },
          {
            data: "salary_expense",
            name: 'salary_expense'
          },
          {
            data: "non_salary_expense",
            name: 'non_salary_expense'
          },
          {
            data: "total_expense",
            name: 'total_expense'
          },
          {
            data: "remarks",
            name: 'remarks'
          },
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
          [0, "desc"]
        ]
      });

      $('#createExpense').click(function() {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Expense");
        $('#expense_id').val('');
        $('#ExpenseForm').trigger("reset");
        $('#modelHeading').html("Create New Expense");
        $('#ajaxModel').modal('show');
      });
      $('body').unbind().on('click', '.editExpense', function() {
        var expense_id = $(this).data('id');
        $('#json_message_modal').html('');
        $.get("{{ url('hrms/project/projectMonthlyExpense') }}" + '/' + expense_id + '/edit', function(data) {
          $('#modelHeading').html("Edit Expense");
          $('#saveBtn').val("edit-Expense");
          $('#ajaxModel').modal('show');
          $('#expense_id').val(data.id);
          $('#month').val(data.month);
          $('#remarks').val(data.remarks);
          var salary = 0;
          var nonSalary = 0;

          var salaryExpense = data.salary_expense?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") ?? '';
          $('#salary_expense').val(salaryExpense);
          salary = parseInt(data.salary_expense);
          var nonSalaryExpense = data.non_salary_expense?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") ?? '';
          $('#non_salary_expense').val(nonSalaryExpense);
          nonSalary = parseInt(data.non_salary_expense);
          var totalExpense = salary + nonSalary?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") ?? '';
          $('#total_expenses').val(totalExpense);

        })
      });
      $('#saveBtn').unbind().click(function(e) {
        e.preventDefault();
        $(this).html('Save');

        $.ajax({
          data: $('#ExpenseForm').serialize(),
          url: "{{ route('projectMonthlyExpense.store') }}",
          type: "POST",
          dataType: 'json',
          success: function(data) {

            $('#ExpenseForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            $('#json_message_modal').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
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

      $('body').on('click', '.deleteExpense', function() {

        var expense_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if (con) {
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectMonthlyExpense.store') }}" + '/' + expense_id,
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