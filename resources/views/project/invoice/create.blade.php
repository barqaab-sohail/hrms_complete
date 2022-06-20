
<div class="card-body">
  @if(isEditInvoice(session('pr_detail_id')) || isDeleteInvoice(session('pr_detail_id')))
  <button type="button" class="btn btn-success float-right"  id ="createInvoice" data-toggle="modal" >Add Invoice</button>
  @endif
  <br>

  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Invoice No</th>
          <th>Invoice Date</th>
          <th>Invoice Month</th>
          <th>Invoice Type</th>
          <th>Value Exc. Sales Tax</th>
          <th>Sales Tax</th>
          <th>Total Value</th>
          <th>Payment Status</th>
          <th>Document</th>
          @if(isEditInvoice(session('pr_detail_id')) || isDeleteInvoice(session('pr_detail_id')))
          <th>Edit</th>
          @endif
          @if(isDeleteInvoice(session('pr_detail_id')))
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
                  <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Invoice No.<span class="text_requried">*</span></label>
                            <input type="text" name="invoice_no" id="invoice_no"  value="{{old('invoice_no')}}" class="form-control exempted" data-validation="required" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Invoice Date<span class="text_requried">*</span></label>
                            <input type="text" name="invoice_date" id="invoice_date" value="{{ old('invoice_date') }}" class="form-control date_input" data-validation="required" readonly >
                            <br>
                            <i class="fas fa-trash-alt text_requried"></i> 
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Invoice Month</label>
                            <input type="text" name="invoice_month" id="invoice_month" value="{{ old('invoice_month') }}" class="form-control date-picker" data-validation="required" readonly >
                            <br>
                            <i class="fas fa-trash-alt text_requried"></i> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-right">Invoice Type<span class="text_requried">*</span></label>
                            <select  id="invoice_type_id"   name="invoice_type_id"  class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($invoiceTypes as $invoiceType)
                                <option value="{{$invoiceType->id}}" {{(old("invoice_type_id")==$invoiceType->id? "selected" : "")}}>{{$invoiceType->name}}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label">Reference</label>
                          <textarea  rows=3 cols=5  name="reference" id="reference"   class="form-control">{{old('reference')}}</textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label">Description</label>
                          <textarea  rows=3 cols=5 type="text" name="description" id="description" class="form-control" >{{old('description')}}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Value Excluding Sales Tax<span class="text_requried">*</span></label>
                        <input type="text" name="amount" id="amount" value="{{old('amount')}}" class="form-control prc_1" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Sales Tax<span class="text_requried">*</span></label>
                        <input type="text" name="sales_tax"  id="sales_tax" value="{{old('sales_tax')}}" class="form-control prc_1" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Total Value</label>
                        <input type="text" name="total_value"  id="total_value" value="{{old('total_value')}}" readonly class="form-control" >
                      </div>
                    </div>
                  </div>
                  <!--/row-->
                  <div class="row">
                    <div class="col-md-8 pdfView">
                    <embed id="pdf" src=""  type="application/pdf" height="300" width="100%" />
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <center >
                            <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src"  id="wizardPicturePreview"  title="" width="150" >
                            </input>
                            <input type="file"  name="document" id="view" data-validation="required" class="" hidden>
                                                                            
                            <h6 id="h6" class="card-title m-t-10">Click On Image to Add Document<span class="text_requried">*</span></h6>
                    
                            </center>  
                        </div>
                    </div>                                    
                  </div>
                  <!--end row-->
                    
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
    max-width: 90%;
    display: flex;
}
</style>

<script type="text/javascript">


$(document).ready(function() {
  //function view from list table
  
  $(function() {
      $('.date-picker').datepicker( {
      changeMonth: true,
      changeYear: true,
      showButtonPanel: true,
      dateFormat: 'MM yy',
      onClose: function(dateText, inst) { 
          $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            if($('.date-picker').val()!=''){
              $('.date-picker').siblings('i').show();
            }else{
              $('.date-picker').siblings('i').hide();
            }
        }
      });
      $('.date-picker').siblings('i').hide();
     
      
  });
  //only number value entered
    $('#amount, #sales_tax').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#amount, #sales_tax').keyup(function(event) {

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
            {data: "invoice_month", name: 'invoice_month'},
            {data: "invoice_type", name: 'invoice_type'},
            {data: "amount", name: 'amount'},
            {data: "sales_tax", name: 'sales_tax'},
            {data: "total_value", name: 'total_value'},
            {data: "payment_status", name: 'payment_status'},
            {data: "invoice_document", name: 'invoice_document'},
             @if(isEditInvoice(session('pr_detail_id')) || isDeleteInvoice(session('pr_detail_id')))
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            @endif
            @if(isDeleteInvoice(session('pr_detail_id')))
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
            @endif

        ],
     
        order: [[ 0, "desc" ]],
        drawCallback:function(){
          if($('[id^="ViewPDF"]').length > 0) {
            $('[id^="ViewPDF"]').EZView();
          }
        }
    });
   

    $('#createInvoice').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Invoice");
        $('#invoice_id').val('');
        $('#invoiceForm').trigger("reset");
        $('#invoice_type_id').trigger('change');
        $('#modelHeading').html("Create New Invoice");
        $('#ajaxModel').modal('show');
        document.getElementById("pdf").src='';
        document.getElementById("h6").innerHTML = "PDF Document is Attached";
    });
    $('body').unbind().on('click', '.editInvoice', function () {
      var invoice_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectInvoice') }}" +'/' + invoice_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Invoice");
          $('#saveBtn').val("edit-Invoice");
          $('#invoice_id').val(data.id);
          $('#invoice_no').val(data.invoice_no);
          $('#invoice_date').val(dateInDayMonthYear(data.invoice_date));
          $('#invoice_month').val(data.invoice_month);
          $('#invoice_type_id').val(data.invoice_type_id);
          $('#invoice_type_id').trigger('change');
          $('#reference').val(data.reference);
          $('#description').val(data.description);
          var amount = (data.amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#amount').val(amount);
          var salesTax = (data.sales_tax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#sales_tax').val(salesTax); 
          //+ convert string to number
          var totalValue = (+(data.amount) + +(data.sales_tax));
          totalValue = (totalValue).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#total_value').val(totalValue); 
          var docUrl = "{{asset("")}}"+"storage/"+data.path;
          $( "#pdf" ).show();
          document.getElementById("pdf").src=docUrl;
          //$('#pdf').trigger('change');
          $('#ajaxModel').modal('show');
         
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
        var data = new FormData($("#invoiceForm")[0]); 
        $.ajax({
          data: data,
          url: "{{ route('projectInvoice.store') }}",
          type: "POST",
          async: false, 
          cache: false, 
          contentType: false, 
          processData: false, 
          //dataType: 'json',
          success: function (data) {
     
              $('#invoiceForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
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
  
  $("#pdf" ).hide();
            // Prepare the preview for profile picture
        $("#view").change(function(){
                var fileName = this.files[0].name;
                var fileType = this.files[0].type;
                var fileSize = this.files[0].size;
                //var fileType = fileName.split('.').pop();
                
            //Restrict File Size Less Than 2MB
            if (fileSize> 300000){
                alert('File Size is bigger than 300KB');
                $(this).val('');
            }else{
                //Restrict File Type
               if(fileType=='application/pdf')
                {
                readURL(this);// for Default Image
                document.getElementById("h6").innerHTML = "PDF Document is Attached";
                document.getElementById("pdf").src="{{asset('Massets/images/document.png')}}";  
                $( "#pdf" ).show();
                }else{
                    alert('Only PDF Allowed');
                $(this).val('');
                }
            }
            
        });


        function readURL(input) {
            var fileName = input.files[0].name;
            var fileType = input.files[0].type;
            //var fileType = fileName.split('.').pop();
                                
            if (fileType !='application/pdf'){
            //Read URL if image
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                        reader.onload = function (e) {
                            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow').attr('width','100%');
                        }
                        reader.readAsDataURL(input.files[0]);
                }
                    
            }else{
               
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                        reader.onload = function (e) {
                            $('embed').attr('src', e.target.result.concat('#toolbar=0&navpanes=0&scrollbar=0'));
                        }
                        reader.readAsDataURL(input.files[0]);
                }   
                document.getElementById("wizardPicturePreview").src="{{asset('Massets/images/document.png')}}"; 
                document.getElementById("h6").innerHTML = "PDF File is Attached";
                 $('#wizardPicturePreview').attr('width','150');
            }           
        }
            
        $("#wizardPicturePreview" ).click (function() {
           $("input[id='view']").click();
         });

    



});
</script>
