        
    <div class="card-body">
        <form id= "formConsultancyCost" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('projectPartner.store')}}" enctype="multipart/form-data">
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Partner Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Name of Partner<span class="text_requried">*</span></label>

                                <select  id="partner_id"   name="partner_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($partners as $partner)
                                    <option value="{{$partner->id}}" {{(old("partner_id")==$partner->id? "selected" : "")}}>{{$partner->name}}</option>
                                    @endforeach 
                                </select>

                            </div>
                        </div>
                    </div>
                     <!--/span-->

                    <div class="col-md-4">
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
                    
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Man Month Cost</label>
                                
                               <input type="text" name="mm_cost" id="mm_cost" value="{{ old('mm_cost') }}" class="form-control">
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Direct Cost</label>
                                <input type="text" name="direct_cost" id="direct_cost" value="{{ old('direct_cost') }}" class="form-control">
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Tax</label>
                                
                               <input type="text" name="tax" id="tax"  value="{{ old('tax') }}" class="form-control">
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Total Cost<span class="text_requried">*</span></label>
                                
                               <input type="text" name="total_cost" id="total_cost" value="{{ old('total_cost') }}" data-validation="required"  class="form-control" >
                               
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
               
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

        <div class="row">
             <div class="col-md-12 table-container">
           
            
            </div>
        </div>
	</div> <!-- end card body -->    

<script>
$(document).ready(function(){
    
    //refreshTable("{{route('contact.table')}}",300);
    $('#mm_cost').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    console.log(fixedInput);
    });

    //submit function
    $("#formConsultancyCost").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url,1);
      //refreshTable("{{route('contact.table')}}");
    });
   
});
</script>

