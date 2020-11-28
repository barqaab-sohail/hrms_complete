        
    
    <div class="card-body">
           
        <h3 class="box-title">Edit Consultancy Cost</h3>
        
        <form id= "formConsultancyCost" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('projectConsultancyCost.update',$data->id??'')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        @method('PATCH')      
            <div class="form-body" >
                     
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    @if($prDetail->pr_role_id != 1)
                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-md-12">
                              
                                <label class="control-label text-right">Name of Firm<span class="text_requried">*</span></label>

                                <select  id="partner_id"   name="partner_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($partners as $partner)
                                    <option value="{{$partner->id}}" {{(old("partner_id")==$partner->id? "selected" : "")}}>{{$partner->name}}</option>
                                    @endforeach 
                                </select>
                               
                            </div>
                        </div>
                    </div>
                    @endif
                    <!--/span-->

                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Cost Type<span class="text_requried">*</span></label>
                                 <select  id="pr_cost_type_id"   name="pr_cost_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($prCostTypes as $prCostType)
                                    <option value="{{$prCostType->id}}" {{(old("pr_cost_type_id", $data->pr_cost_type_id)==$prCostType->id? "selected" : "")}}>{{$prCostType->name}}</option>
                                    @endforeach 

                                </select>
                               
                            </div>
                        </div>
                    </div>
                    
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Man Month Cost<span class="text_requried">*</span></label>
                                
                               <input type="text" name="man_month_cost" id="man_month_cost" value="{{ old('man_month_cost',  $data->prConsultancyCostMm->man_month_cost??'') }}" class="form-control total" data-validation="required">
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Direct Cost</label>
                                <input type="text" name="direct_cost" id="direct_cost" value="{{ old('direct_cost', $data->prConsultancyCostDirect->direct_cost??'') }}" class="form-control total">
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Tax</label>
                                
                               <input type="text" name="tax_cost" id="tax_cost"  value="{{ old('tax_cost',$data->prConsultancyCostTax->tax_cost??'') }}" class="form-control total">
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Contigency</label>
                                
                               <input type="text" name="contingency_cost" id="contingency_cost" value="{{ old('contingency_cost',$data->prConsultancyCostContingency->contingency_cost??'') }}"  class="form-control total" >
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Total Cost</label>
                                
                               <input type="text" name="total_cost" id="total_cost" value="{{ old('total_cost') }}"  class="form-control" readonly>
                               
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
               
            </div> <!--/End Form Boday-->

          

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
        <hr>
        <div class="row">
             <div class="col-md-12 table-container">
           
            
            </div>
        </div>
	</div> <!-- end card body -->    

<script>
$(document).ready(function(){
    

    refreshTable("{{route('projectConsultancyCost.table')}}",1000);
    
    //only number value entered
    $('#man_month_cost, #direct_cost, #contingency_cost, #tax_cost').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    var totalFunction = function() {
        var sum = 0;
            $(".form-group .total").each(function(){
                var inputVal = $(this).val();
                inputVal=inputVal.replace(/\,/g,'') // remove comma
                if ($.isNumeric(inputVal)){
                sum += parseFloat(inputVal);
                }
            });
            sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
        $("#total_cost").val(sum);    
    }

    $('.total').keyup(totalFunction).each(totalFunction);

    var commaFunction = function (event){
         // skip for arrow keys
      if(event.which >= 37 && event.which <= 40) return;

      // format number
      $(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
      });
    }

    $('.total').keyup(commaFunction).each(commaFunction);



    //submit function
    $("#formConsultancyCost").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url);
      //refreshTable("{{route('projectConsultancyCost.table')}}",1000);
    });
   
});
</script>

