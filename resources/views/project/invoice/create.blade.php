
<div class="card-body">
  @if(projectInvoiceRight(session('pr_detail_id'))==2)
  <button type="button" class="btn btn-success float-right"  id ="createInvoice" data-toggle="modal" >Add Invoice</button>
  @endif
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Invoice No</th>
          <th>Invoice Date</th>
          <th>Invoice Type</th>
          <th>Value Exc. Sales Tax</th>
          <th>Sales Tax</th>
          <th>Total Value</th>
          <th>Payment Status</th>
          @if(projectInvoiceRight(session('pr_detail_id'))==3)
          <th>Edit</th>
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
                <form id="invoiceForm" name="invoiceForm" action="{{route('projectInvoice.store')}}"class="form-horizontal">
                   
                   <input type="hidden" name="invoice_id" id="invoice_id">
                 
                    <div class="form-group">
                        <label class="control-label">Invoice No.<span class="text_requried">*</span></label>
                        <input type="text" name="invoice_no" id="invoice_no"  value="{{old('invoice_no')}}" class="form-control exempted" data-validation="required" >
                    </div>
                    <div class="form-group">
                        <label class="control-label">Invoice Date<span class="text_requried">*</span></label>
                        <input type="text" name="invoice_date" id="invoice_date" value="{{ old('invoice_date') }}" class="form-control date_input" data-validation="required" readonly >
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i> 
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Invoice Type<span class="text_requried">*</span></label>
                        <select  id="invoice_type_id"   name="invoice_type_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($invoiceTypes as $invoiceType)
                            <option value="{{$invoiceType->id}}" {{(old("invoice_type_id")==$invoiceType->id? "selected" : "")}}>{{$invoiceType->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Reference<span class="text_requried">*</span></label>
                        <textarea  rows=3 cols=5  name="reference" id="reference"   class="form-control" data-validation="required" >{{old('reference')}}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description<span class="text_requried">*</span></label>
                        <textarea  rows=3 cols=5 type="text" name="description" id="description" class="form-control" data-validation="required" >{{old('description')}}</textarea>
                    </div>

                    <div class="form-group">
                      <label class="control-label">Value Excluding Sales Tax<span class="text_requried">*</span></label>
                      <input type="text" name="cost" id="cost" value="{{old('cost')}}" class="form-control prc_1" data-validation="required">
                    </div>

                    <div class="form-group">
                      <label class="control-label">Sales Tax<span class="text_requried">*</span></label>
                      <input type="text" name="sales_tax"  id="sales_tax" value="{{old('sales_tax')}}" class="form-control prc_1" data-validation="required">
                    </div>
                    <div class="form-group">
                      <label class="control-label">Total Value</label>
                      <input type="text" name="total_value"  id="total_value" value="{{old('total_value')}}" readonly class="form-control" >
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
    $('#cost, #sales_tax').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#cost, #sales_tax').keyup(function(event) {

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
        $("#total_value").val(sum);
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
        ajax: "{{ route('projectInvoice.create') }}",
        columns: [
            {data: "invoice_no", name: 'invoice_no'},
            {data: "invoice_date", name: 'invoice_date'},
            {data: "invoice_type", name: 'invoice_type'},
            {data: "cost", name: 'cost'},
            {data: "sales_tax", name: 'sales_tax'},
            {data: "total_value", name: 'total_value'},
            {data: "payment_status", name: 'payment_status'},
            @if(projectInvoiceRight(session('pr_detail_id'))==3)
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
            @endif

        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createInvoice').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Invoice");
        $('#invoice_id').val('');
        $('#invoiceForm').trigger("reset");
        $('#modelHeading').html("Create New Invoice");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editInvoice', function () {
      var invoice_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/projectInvoice') }}" +'/' + invoice_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Invoice");
          $('#saveBtn').val("edit-Invoice");
          $('#ajaxModel').modal('show');
          $('#invoice_id').val(data.id);
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
          data: $('#invoiceForm').serialize(),
          url: "{{ route('projectInvoice.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#invoiceForm').trigger("reset");
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
     
        var invoice_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectInvoice.store') }}"+'/'+invoice_id,
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
