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
                <form id= "createInvoiceForm" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('invoice.store')}}" enctype="multipart/form-data">
                @csrf
                    <div class="form-body">
                            
                        <h3 class="box-title">Create Invoice</h3>
                        
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
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Invoice No.<span class="text_requried">*</span></label><br>     
                                        <input type="text" name="invoice_no" value="{{ old('invoice_no') }}" class="form-control exempted" data-validation="required" >
                                    </div>
                                </div>
                            </div>
                              <!--/span-->
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Invoice Date<span class="text_requried">*</span></label><br>
                                        <input type="text" name="invoice_date" value="{{ old('invoice_date') }}" class="form-control date_input" data-validation="required" readonly>     
                                        <br>
                                       <i class="fas fa-trash-alt text_requried"></i>   
                                    </div>
                                </div>
                            </div>
                              <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Description<span class="text_requried">*</span></label><br>      
                                        <input type="text" name="description" value="{{ old('description') }}" class="form-control exempted"  data-validation="required">
                                    </div>
                                </div>
                            </div>
                              <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="control-label text-right">Reference<span class="text_requried">*</span></label><br>      
                                        <input type="text" name="reference" value="{{ old('reference') }}" class="form-control exempted"  data-validation="required">
                                    </div>
                                </div>
                            </div>
                        </div><!--/End Row-->

                        <!--row 4-->
                        <div class="row cost" id='cost_1' >
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
                                        <input type="text" name="cost" value="{{old('cost')}}" class="form-control prc_1" data-validation="required" >
                                    </div>
                                </div>
                            </div>
                             <!--/span 4-2 -->
                             <!--/span 4-2 -->
                            <div class="col-md-2">
                                <div class="form-group row">
                                    <div class="col-md-12 ">
                                        <label class="control-label">Sales Tax</label>
                                        <input type="text" name="sales_tax" value="{{old('sales_tax')}}" class="form-control prc_1" data-validation="required">
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
                        <div class="row" >
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end card body --> 
        </div>   
	</div>
		
</div>



<script>
$(document).ready(function() {

formFunctions();
    // $('.cost').find('select').chosen();
     $('#divFrom, #divTo').hide();

     $('#escalation').click(function(){
        $('.hideDiv').toggle();
    });

    $("#invoice_type_id").change(function(){
        
        if($(this).val()==1){
            $('#divFrom, #divTo').show();
            $('#divProject').addClass('col-md-6').removeClass('col-md-8');
            $("#to").attr("data-validation","required");
            $("#from").attr("data-validation","required");
            $('.hideDiv').show();
        }else{
            $('#divFrom, #divTo').hide();
            $('#divProject').addClass('col-md-8').removeClass('col-md-6');
            $("#to").removeAttr("data-validation");
            $("#from").removeAttr("data-validation");
             $('.hideDiv').hide();
        }

    })

     //Enter Comma after three digit and restrict only digit
    // $(document).on('keyup', ".prc_1, .esc_1", function(event){
    // //$(".prc_1, .prc_2").keyup(function(event) {

    //   // skip for arrow keys
    //   if(event.which >= 37 && event.which <= 40) return;

    //   // format number
    //   $(this).val(function(index, value) {
    //     return value
    //     .replace(/\D/g, "")
    //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    //     ;
    //   });
    // });

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





    // //Dynamic add Cost
    
    // // Add new element
    //  $("#add").click(function(){
        
    //   // Finding total number of elements added
    //   var total_element = $(".cost").length;
        
    //   // last <div> with element class id
    //   var lastid = $(".cost:last").attr("id");
    //   var split_id = lastid.split("_");
    //   var nextindex = Number(split_id[1]) + 1;
    //   var max = 2;
    //   // Check total number elements
    //   if(total_element < max ){
    //    //Clone Cost div and copy 
    //     $('.cost').find('select').chosen('destroy');
    //     var clone = $("#cost_1").clone();
    //     clone.prop('id','cost_'+nextindex).find('input:text').val('');
    //     clone.find('.prc_1').addClass('prc_2').removeClass('prc_1');
    //     clone.find('#total_1').attr("id","total_2");
    //     clone.find("#add").html('X').prop("class", "btn btn-danger remove remove_cost");
    //     clone.insertAfter("div.cost:last");
    //     $('.cost').find('select').chosen();
       
    //   }
     
    // });
    //  // Remove element
    //  $(document).on("click", '.remove_cost', function(){
    //  $(this).closest(".cost").remove();
    // }); 
	
   
    //  //automatic total.
    // $(document).on("input", ".prc_2", function() {
    //     var sum = 0;
    //     $(".form-group .prc_2").each(function(){
    //         var inputVal = $(this).val();
    //         inputVal=inputVal.replace(/\,/g,'') // remove comma
    //         if ($.isNumeric(inputVal)){
    //         sum += parseFloat(inputVal);
    //         }
    //     });
    //     sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
    //     $("#total_2").val(sum);      
    // });

    // //total Invoice
    // $(document).on('keyup', ".prc_1, .prc_2", function(event){
    //     var total_1=$("#total_1").val();
    //     total_1=total_1.replace(/\,/g,'') // remove comma
    //     var total_invoice=0;
    //     if(typeof $("#total_2").val()==='undefined'){
    //         total_invoice= parseInt(total_1);
    //         total_invoice = total_invoice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
    //         $("#total_invoice").text('Total Invoice: '+total_invoice);
    //     }else{
    //         var total_2=$("#total_2").val();
    //         total_2=total_2.replace(/\,/g,'') // remove comma
    //         total_invoice= parseInt(total_2)+parseInt(total_1);
    //         total_invoice = total_invoice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
    //         $("#total_invoice").text('Total Invoice: '+total_invoice);
    //     }
        
    // });

    // $(document).on('keyup', ".esc_1", function(event){
    //     var total_1=$("#total_1").val();
    //     total_1=total_1.replace(/\,/g,'') // remove comma
    //     var total_invoice=0;
    //     if(typeof $("#total_2").val()==='undefined'){
    //         total_invoice= parseInt(total_1);
    //         total_invoice = total_invoice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
    //         $("#total_invoice").text('Total Invoice: '+total_invoice);
    //     }else{
    //         var total_2=$("#total_2").val();
    //         total_2=total_2.replace(/\,/g,'') // remove comma
    //         total_invoice= parseInt(total_2)+parseInt(total_1);
    //         total_invoice = total_invoice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
    //         $("#total_invoice").text('Total Invoice: '+total_invoice);
    //     }
        
    // });

      

            
});
</script>

@stop