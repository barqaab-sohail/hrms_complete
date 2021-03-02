@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor"></h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<div class="row">
            <div class="card-body" >
                <form id= "invoiceEditForm" method="post" class="form-horizontal form-prevent-multiple-submits" action="" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                    <div class="form-body">
                            
                        <h3 class="box-title">List of Invoices</h3>
                        
                        <hr class="m-t-0 m-b-40">

                        <div class="row">
                            <div class="col-md-8" id="divProject">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Name of Project<span class="text_requried">*</span></label><br>
                                        <select id="pr_detail_id" name="pr_detail_id" data-validation="required" class="form-control selectTwo">
                                           <option></option>
                                            @foreach($projects as $project)
                                            <option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->name}}</option>
                                            @endforeach   
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-2 hide_1">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                         <label class="control-label text-right">Invoice Type<span class="text_requried">*</span></label><br>
                                        <select id="invoice_type_id" name="invoice_type_id" data-validation="required" class="form-control selectTwo">
                                           <option></option>
                                            @foreach($invoiceTypes as $invoiceType)
                                            <option value="{{$invoiceType->id}}" {{(old("invoice_type_id")==$invoiceType->id? "selected" : "")}}>{{$invoiceType->name}}</option>
                                            @endforeach   
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>
                             <!--/span-->
                            <div class="col-md-2" id="divFrom">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">From<span class="text_requried">*</span></label><br>
                                        <input type="text" id="from" name="from" value="{{ old('from') }}" class="form-control date_input" readonly>     
                                        <br>
                                       <i class="fas fa-trash-alt text_requried"></i>      
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-2" id="divTo">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">To<span class="text_requried">*</span></label><br>
                                        <input type="text" id="to" name="to" value="{{ old('to') }}" class="form-control date_input" readonly>     
                                        <br>
                                       <i class="fas fa-trash-alt text_requried"></i>
                                    </div>
                                </div>
                            </div>
                        </div><!--/End Row-->
                        <div class="row">    
                             <!--/span-->
                            <div class="col-md-2 hide_1">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Invoice No.<span class="text_requried">*</span></label><br>     
                                        <input type="text" name="invoice_no" id="invoice_no" value="{{ old('invoice_no') }}" class="form-control exempted" data-validation="required" >
                                    </div>
                                </div>
                            </div>
                              <!--/span-->
                            <div class="col-md-2 hide_1">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Invoice Date<span class="text_requried">*</span></label><br>
                                        <input type="text" name="invoice_date" id="invoice_date" value="{{ old('invoice_date') }}" class="form-control date_input" data-validation="required" readonly>     
                                        <br>
                                       <i class="fas fa-trash-alt text_requried"></i>   
                                    </div>
                                </div>
                            </div>
                              <!--/span-->
                            <div class="col-md-4 hide_1">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Description<span class="text_requried">*</span></label><br>      
                                        <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control exempted"  data-validation="required">
                                    </div>
                                </div>
                            </div>
                              <!--/span-->
                            <div class="col-md-4 hide_1">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Reference<span class="text_requried">*</span></label><br>      
                                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" class="form-control exempted"  data-validation="required">
                                    </div>
                                </div>
                            </div>
                        </div><!--/End Row-->

                        <!--row 4-->
                        <div class="row cost hide_1" id='cost_1' >
                            <div class="col-md-2">
                                <!--/span 4-1 -->
                                <div class="form-group row">
                                    <div class="col-md-12 required">
                                        <label class="control-label text-right">Cost Type<span class="text_requried ">*</span></label><br>
                                            <select id="cost_type_id" name="cost_type_id" data-validation="required" class="form-control selectTwo">
                                           <option></option>
                                            @foreach($costTypes as $costType)
                                            <option value="{{$costType->id}}" {{(old("cost_type_id")==$costType->id? "selected" : "")}}>{{$costType->name}}</option>
                                            @endforeach
                                          
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <!--/span 4-2 -->
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                       
                                    </div>
                                </div>
                            </div>
                             <!--/span 4-2 -->
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                        <label class="control-label">Invoice Cost</label>
                                        <input type="text" name="cost" id="cost" value="{{old('cost')}}" class="form-control prc_1" data-validation="required" >
                                    </div>
                                </div>
                            </div>
                             <!--/span 4-2 -->
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                        <label class="control-label">Sales Tax</label>
                                        <input type="text" name="sales_tax" id="sales_tax" value="{{old('sales_tax')}}" class="form-control prc_1" data-validation="required">
                                    </div>
                                </div>
                            </div>
                            <!--/span 4-3 -->
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <div class="col-md-8">
                                         <label class="control-label">Total</label>
                                        <input type="text" id='total_1' name="total" value="{{old('total')}}" class="form-control" readonly>
                                    </div>
                                    <!-- <div class="col-md-8">
                                    <br>
                                        <div class="float-right">
                                        <button type="button" name="add" id="add" class="btn btn-success add" >+</button>
                                        </div>
                                    </div> -->
                                </div>
                            </div>

                        </div>
                        <!--row 4-->
                        <div class="row hide_1" >
                            <div class="col-md-4">
                                <!--/span 4-1 -->
                                <div class="form-group row">
                                    <div class="col-md-12 required">
                                        <div class="form-check form-switch">
                                          <input class="form-check-input" type="checkbox" id="escalation">
                                          <label class="form-check-label" for="escalation">if Escalation included in the above cost</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/span 4-2 -->
                            <div class="col-md-2 hideDiv">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                        <label class="control-label">Escalation Cost</label>
                                        <input type="text" name="esc_cost" id="esc_cost" value="{{old('esc_cost')}}" class="form-control esc_1" >

                                    </div>
                                </div>
                            </div>
                             <!--/span 4-2 -->
                            <div class="col-md-2 hideDiv">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                        <label class="control-label">Sales Tax</label>
                                        <input type="text" name="esc_sales_tax" id="esc_sales_tax" value="{{old('esc_sales_tax')}}" class="form-control esc_1">
                                    </div>
                                </div>
                            </div>
                             <!--/span 4-2 -->
                            <div class="col-md-4 hideDiv">
                                <div class="form-group row">
                                    <div class="col-md-8">
                                         <label class="control-label">Total Escalation</label>
                                        <input type="text" id='total_escalation' name="esc_total" value="{{old('esc_total')}}" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                       
                    </div> <!--/End Form Boday-->

                    <hr>

                    <div class="form-actions">
                        <div class="row hide_1">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" Id=saveBtn class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end card body --> 
        </div>   
	</div>
    <table class="table table-bordered data-table">
            <thead>
              <tr>
                  <th>Invoice No</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
              </tr>
            </thead>
            <tbody> 
            </tbody>
        </table>
		
</div>



<script>
$(document).ready(function() {
    $('#divFrom, #divTo, .hide_1, .hideDiv').hide();

    $('#escalation').click(function(){
        $('.hideDiv').toggle();
    });

formFunctions();
    $(function () {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        
         $('#invoiceEditForm').on('submit', function(e){  
        //$('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Save.......');
                var url = $(this).attr('action');
                $.ajax({
                  data: $('#invoiceEditForm').serialize(),
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  success: function (data) {
                    if(data.status == 'OK'){
                        $('#json_message').html('<div id="j_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                    }
                    else{
                    $('#json_message').html('<div id="j_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');    
                    }
                    table.draw();

                    //$('#invoiceRightsForm').trigger("reset");
                    
                    $('#saveBtn').html('Updated');
                
                  },
                  error: function (data) {
                      console.log(data.responseJSON.errors);
                      var errorMassage = '';
                      $.each(data.responseJSON.errors, function (key, value){
                        errorMassage += value + '<br>';  
                        });
                         $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

                      $('#saveBtn').html('Save Changes');
                  }
                });
        });

        $('#pr_detail_id').change(function(){
        var cid = $(this).val();
        var url = "{{url('invoice/invoice')}}"+"/"+cid;
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: url,
                columns: [
                    {data: "invoiceNo", name: 'invoice_no'},
                    {data: 'status', name: 'invoice_status_id'},
                    {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
                    {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
                ]
            });

            $('#divFrom, #divTo, .hide_1, .hideDiv').hide();
           
        });

        //Edit
        $('body').unbind().on('click', '.editInvoice', function () {
            var invoice_id = $(this).data('id');

            $('#json_message_modal').html('');
            $.get("{{ url('invoice/invoice') }}" +'/' + invoice_id +'/edit', function (data) {
                var action = "{{route('invoice.store')}}" + "/" + data.id; 
                $('#invoiceEditForm').attr('action',action);
                $('#saveBtn').text("Edit-Invoice");
                $('#invoice_id').val(data.id);
                
              
                    if(data.invoice_period){
                      $('#from').val(data.invoice_period.from);
                      $('#to').val(data.invoice_period.to);
                    };

                $('#invoice_type_id').val(data.invoice_type_id);
                $('#invoice_no').val(data.invoice_no);
                $('#invoice_date').val(data.invoice_date);
                $('#description').val(data.description);
                $('#reference').val(data.reference);
                $('#cost_type_id').val(data.invoice_cost.cost_type_id);
                var cost = new Intl.NumberFormat('en-US').format(data.invoice_cost.cost) 
                $('#cost').val(cost);
                var sales_tax = new Intl.NumberFormat('en-US').format(data.invoice_cost.sales_tax) 
                $('#sales_tax').val(sales_tax);
                var total = parseInt(data.invoice_cost.cost) + parseInt(data.invoice_cost.sales_tax);

                var total_1 = new Intl.NumberFormat('en-US').format(total); 
                $('#total_1').val(total_1);
                
                $('#invoice_type_id, #cost_type_id').trigger('change');

                $('.hide_1').show();
                console.log(data)
            })
        }); //end edit

    });//End Function

    
    

    $('body').on('click', '.deleteInvoice', function () {
     
        var invoice_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('invoice.store') }}"+'/'+invoice_id,
            success: function (data) {
                table.draw();
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                console.log('Error:', data);
            }
          });
        }
    });//End Delete







    // $('.cost').find('select').chosen();
   

    $("#invoice_type_id").change(function(){
        
        if($(this).val()==1){
            $('#divFrom, #divTo').show();
            $('#divProject').addClass('col-md-6').removeClass('col-md-8');
            $("#to").attr("data-validation","required");
            $("#from").attr("data-validation","required");
            $('.hideDiv').show();
        }else{
            $('#from, #to').val('');
            $('#divFrom, #divTo').hide();
            $('#divFrom').val('');
            $('#divProject').addClass('col-md-8').removeClass('col-md-6');
            $("#to").removeAttr("data-validation");
            $("#from").removeAttr("data-validation");
             $('.hideDiv').hide();
        }

    })

    $('.prc_1').keyup(function(){
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
        $("#total_1").val(sum);
    });

     //total escalation
    $(".form-group").on("input", ".esc_1", function() {
    var sum = 0;
        $(".form-group .esc_1").each(function(){
            var inputVal = $(this).val();
            if ($.isNumeric(inputVal)){
            sum += parseFloat(inputVal);
            }
        });
        sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
        $("#total_escalation").val(sum);
    });

       //submit function
     $("#createInvoiceForm").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url);
        $("#total_invoice").text('');
    });


      

            
});
</script>

@stop