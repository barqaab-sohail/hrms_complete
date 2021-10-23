        
    
    <div class="card-body">
            <button type="button" id="addButton" class="btn btn-success float-right">Add Consultancy Cost</button>

        <h3 class="box-title">Consultancy Cost</h3>
        
        <form id= "formConsultancyCost" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('projectConsultancyCost.store')}}" enctype="multipart/form-data">
        @csrf         
            <div class="form-body" >
                     
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Cost Type<span class="text_requried">*</span></label>
                                 <select  id="pr_cost_type_id"   name="pr_cost_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($prCostTypes as $prCostType)
                                    <option value="{{$prCostType->id}}" {{(old("pr_cost_type_id")==$prCostType->id? "selected" : "")}}>{{$prCostType->name}}</option>
                                    @endforeach 
                                </select>
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12 ">
                                <label class="control-label">Total Cost including Tax</label>
                                <input type="text" name="total_cost" value="{{old('total_cost')}}" class="form-control" data-validation="required" >
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12 ">
                                <label class="control-label">Man-Month Cost</label>
                                <input type="text" name="man_month_cost" value="{{old('man_month_cost')}}" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12 ">
                                <label class="control-label">Direct Cost</label>
                                <input type="text" name="direct_cost" value="{{old('direct_cost')}}" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12 ">
                                <label class="control-label">Contingency</label>
                                <input type="text" name="contingency" value="{{old('contingency')}}" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12 ">
                                <label class="control-label">Sales Tax</label>
                                <input type="text" name="sales_tax" value="{{old('sales_tax')}}" class="form-control" >
                            </div>
                        </div>
                    </div>
                   
                    
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Remarks</label>
                               <input type="text" name="remarks" value="{{ old('remarks') }}"  class="form-control" readonly>
                               
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
    $("#addButton").hide();
    @if($prConsultancyCosts->count()!=0)
    $("#formConsultancyCost").hide();
    $("#addButton").show();
    @endif

    $("#addButton").click(function(){
         $("#formConsultancyCost").toggle();
    });

    refreshTable("{{route('projectConsultancyCost.table')}}",1000);
    
    //only number value entered
    $('#man_month_cost, #direct_cost, #contingency_cost, #tax_cost').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });


    //Enter Comma after three digit
    $('#man_month_cost, #direct_cost, #contingency_cost, #tax_cost').keyup(function(event) {

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


    //submit function
    $("#formConsultancyCost").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url,1);
      refreshTable("{{route('projectConsultancyCost.table')}}",1000);
    });
   
});
</script>

