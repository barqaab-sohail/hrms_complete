
<div class="card-body">
 @if(projectPaymentRight(session('pr_detail_id'))==3 || projectPaymentRight(session('pr_detail_id'))==4)
  <button type="button" class="btn btn-success float-right"  id ="createPayment" data-toggle="modal" >Add Payment</button>
  @endif
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Invoice No</th>
          <th>Net Amount Received</th>
          <th>Payment Date</th>
          <th>Cheque No.</th>
          <th>Cheque Date</th>
          <th>Total Deduction</th>
          <th>Payment Status</th>
          @if(projectPaymentRight(session('pr_detail_id'))==3 || projectPaymentRight(session('pr_detail_id'))==4)
          <th>Edit</th>
          @endif
          @if(projectPaymentRight(session('pr_detail_id'))==4)
          <th>Delete</th>
          @endif
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
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label text-right">Invoice No<span class="text_requried">*</span></label>
                          <select  id="invoice_id"   name="invoice_id"  class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach($invoices as $invoice)
                              <option value="{{$invoice->id}}" {{(old("invoice_id")==$invoice->id? "selected" : "")}}>{{$invoice->invoice_no}}</option>
                              @endforeach 
                          </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Total Invoice Value</label>
                        <input type="text" name="total_invoice_value"  id="total_invoice_value" value="{{old('total_invoice_value')}}" readonly class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Net Amount Recived<span class="text_requried">*</span></label>
                          <input type="text" name="amount"  id="amount" value="{{old('amount')}}" class="form-control" data-validation="required">
                          <span id="total_invoice" class="text_requried"></span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Payment Date<span class="text_requried">*</span></label>
                          <input type="text" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="form-control date_input" data-validation="required" readonly >
                          <br>
                          <i class="fas fa-trash-alt text_requried"></i> 
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Witholding Tax Deduction<span class="text_requried">*</span></label>
                        <input type="text" name="withholding_tax" id="withholding_tax" value="{{old('withholding_tax')}}" class="form-control prc_1" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Sales Tax Deduction<span class="text_requried">*</span></label>
                        <input type="text" name="sales_tax"  id="sales_tax" value="{{old('sales_tax')}}" class="form-control prc_1" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Other Deduction<span class="text_requried">*</span></label>
                        <input type="text" name="other_deduction"  id="other_deduction" value="{{old('other_deduction')}}" class="form-control prc_1" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Total Deduction</label>
                        <input type="text" name="total_deduction"  id="total_deduction" value="{{old('total_deduction')}}" readonly class="form-control" >
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Cheque No</label>
                        <input type="text" name="cheque_no" id="cheque_no" value="{{old('cheque_no')}}" class="form-control prc_1" >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Cheque Date</label>
                          <input type="text" name="cheque_date" id="cheque_date" value="{{ old('cheque_date') }}" class="form-control date_input" data-validation="required" readonly >
                          <br>
                          <i class="fas fa-trash-alt text_requried"></i> 
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label text-right">Payment Status<span class="text_requried">*</span></label>
                          <select  id="payment_status_id"   name="payment_status_id"  class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach($paymentStatuses as $paymentStatus)
                              <option value="{{$paymentStatus->id}}" {{(old("payment_status_id")==$paymentStatus->id? "selected" : "")}}>{{$paymentStatus->name}}</option>
                              @endforeach 
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Remarks</label>
                        <input type="text" name="remarks" id="remarks" value="{{old('remarks')}}" class="form-control prc_1 notCapital" >
                      </div>
                    </div>
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

<style>
  .modal-dialog {
    max-width: 80%;
    display: flex;
}
</style>

<script type="text/javascript">

$(document).ready(function() {
  
  
  //only number value entered
    $('#amount, #withholding_tax, #sales_tax, #other_deduction').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#amount, #withholding_tax, #sales_tax, #other_deduction').keyup(function(event) {

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

    //automatic total
    $(".form-group").on("input", ".prc_1", function() {
        var sum = 0;
        $(".form-group .prc_1").each(function(){
            var inputVal = $(this).val();
            inputVal=inputVal.replace(/\,/g,'') // remove comma
            if ($.isNumeric(inputVal)){
            sum += parseFloat(inputVal);
            }
        });
        sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
        $("#total_deduction").val(sum);
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
            {data: "payment_status", name: 'payment_status'},
            @if(projectPaymentRight(session('pr_detail_id'))==3 || projectPaymentRight(session('pr_detail_id'))==4)
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            @endif
            @if(projectPaymentRight(session('pr_detail_id'))==4)
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
            @endif

        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createPayment').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Invoice");
        $('#payment_id').val('');
        $('#invoice_id').val('');
        $('#payment_status_id').val('');
        $('#invoice_id').trigger('change');
        $('#payment_status_id').trigger('change');
        $('#paymentForm').trigger("reset");
        $('#modelHeading').html("Create New Payment");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editPayment', function () {
      
      var payment_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectPayment') }}" +'/' + payment_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Payment");
          $('#saveBtn').val("edit-Payment");
          $('#ajaxModel').modal('show');
          $('#payment_id').val(data.id);
          $('#invoice_id').val(data.invoice_id);
          $('#invoice_id').trigger('change');
          $('#payment_date').val(data.payment_date);
          var withholdingTax = (data.withholding_tax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#withholding_tax').val(withholdingTax);
           var salesTax = (data.sales_tax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#sales_tax').val(salesTax);
           var others = (data.others).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#other_deduction').val(others);
          var totalDeduction = parseInt(data.withholding_tax) + parseInt(data.sales_tax)+parseInt(data.others);
          totalDeduction = (totalDeduction).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#total_deduction').val(totalDeduction);
          $('#cheque_no').val(data.cheque_no);
          $('#cheque_date').val(data.cheque_date);
          $('#payment_status_id').val(data.payment_status_id);
          $('#payment_status_id').trigger('change');
          var amount = (data.amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#amount').val(amount);
      })
   });
    $('#saveBtn').unbind().click(function (e) {
        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);


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
    
    $('body').on('click', '.deletePayment', function () {
     
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

    $('#invoice_id').change(function(){
      var sid = $(this).val();
        if(sid){
          $.ajax({
             type:"get",
              url: "{{url('hrms/project/invoiceValue')}}"+"/"+sid,
             success:function(res)
             {       
                  if(res)
                  {
                      $('#total_invoice_value').val(res);
                  }
             }

          });
        }

    });

});
</script>
