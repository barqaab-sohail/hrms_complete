
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createPayment" data-toggle="modal" >Add Payment</button>
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Invoice No</th>
          <th>Total Amount</th>
          <th>Payment Date</th>
          <th>Cheque No.</th>
          <th>Cheque Date</th>
          <th>Total Deduction</th>
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
                <form id="paymentForm" name="paymentForm" action="{{route('projectPayment.store')}}"class="form-horizontal">
                   
                   <input type="hidden" name="payment_id" id="payment_id">
                    <div class="form-group">
                        <label class="control-label text-right">Invoice No<span class="text_requried">*</span></label>
                        <select  id="invoice_no"   name="invoice_no"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($invoices as $invoice)
                            <option value="{{$invoice->id}}" {{(old("invoice_no")==$invoice->id? "selected" : "")}}>{{$invoice->invoice_no}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Total Amount Reeived<span class="text_requried">*</span></label>
                        <input type="text" name="amount"  id="amount" value="{{old('total_value')}}" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label class="control-label">Payment Date<span class="text_requried">*</span></label>
                        <input type="text" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="form-control date_input" data-validation="required" readonly >
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i> 
                    </div>
                    <div class="form-group">
                      <label class="control-label">Cheque No<span class="text_requried">*</span></label>
                      <input type="text" name="cheque_no" id="cost" value="{{old('cheque_no')}}" class="form-control prc_1" >
                    </div>
                    <div class="form-group">
                        <label class="control-label">Cheque Date<span class="text_requried">*</span></label>
                        <input type="text" name="cheque_date" id="cheque_date" value="{{ old('cheque_date') }}" class="form-control date_input" data-validation="required" readonly >
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i> 
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
  
  //only number value entered
    $('#amount').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#amount').keyup(function(event) {

      // skip for arrow keys
      if(event.which >= 37 && event.which <= 40) return;

      // format number
      $(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
      });
    });


  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('projectPayment.create') }}",
        columns: [
            {data: "invoice_no", name: 'invoice_no'},
            {data: "amount", name: 'amount'},
            {data: "payment_date", name: 'payment_date'},
            {data: "cheque_no", name: 'cheque_no'},
            {data: "cheque_date", name: 'cheque_date'},
            {data: "total_deduction", name: 'total_deduction'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createPayment').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Invoice");
        $('#payment_id').val('');
        $('#paymentForm').trigger("reset");
        $('#modelHeading').html("Create New Payment");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editPayment', function () {
      var payment_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/projectPayment') }}" +'/' + payment_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Payment");
          $('#saveBtn').val("edit-Payment");
          $('#ajaxModel').modal('show');
          $('#payment_id').val(data.id);
          $('#invoice_no').val(data.invoice_no);
          $('#invoice_date').val(data.invoice_date);
          $('#invoice_type_id').val(data.invoice_type_id);
          $('#invoice_type_id').trigger('change');
          $('#reference').val(data.reference);
          $('#description').val(data.description);
          var cost = (data.cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#cost').val(cost);
          var salesTax = (data.sales_tax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#sales_tax').val(salesTax); 
          //+ convert string to number
          var totalValue = (+(data.cost) + +(data.sales_tax));
          totalValue = (totalValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#total_value').val(totalValue); 

      })
   });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#paymentForm').serialize(),
          url: "{{ route('projectPayment.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#paymentForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
        
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteInvoice', function () {
     
        var payment_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectPayment.store') }}"+'/'+payment_id,
            success: function (data) {
                table.draw();
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                
            }
          });
        }
    });
     
  });
});
</script>
