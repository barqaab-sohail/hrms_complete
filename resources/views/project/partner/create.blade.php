
        
    <div class="card-body">
        <form id= "formPartnerDetail" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('projectPartner.store')}}" enctype="multipart/form-data">
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

                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Partner Role<span class="text_requried">*</span></label>
                                 <select  id="pr_role_id"   name="pr_role_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($prRoles as $prRole)
                                    <option value="{{$prRole->id}}" {{(old("pr_role_id")==$prRole->id? "selected" : "")}}>{{$prRole->name}}</option>
                                    @endforeach 
                                </select>
                               
                            </div>
                        </div>
                    </div>
                    
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Partner Share<span class="text_requried">*</span></label>
                                
                               <input type="text" name="share" value="{{ old('share') }}" class="form-control" data-validation="required length"  data-validation-length="max190">

                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Authorize Person<span class="text_requried">*</span></label>
                                
                               <input type="text" name="authorize_person" value="{{ old('authorize_person') }}" class="form-control" data-validation="required length"  data-validation-length="max190" placeholder="Enter Authorize Person Name">
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Designation<span class="text_requried">*</span></label>
                                <input type="text" name="designation" value="{{ old('designation') }}" class="form-control" data-validation="required length"  data-validation-length="max190" placeholder="Enter Authorize Person Designation">
                                
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                     <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Mobile No.<span class="text_requried">*</span></label>
                                
                               <input type="text" name="mobile" id="mobile" pattern="[0-9.-]{12}" title= "11 digit without dash" value="{{ old('mobile') }}" data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0345-0000000">
                               
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                   
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Landline No.</label>
                                
                               <input type="text" name="landline" value="{{ old('landline') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Email</label>
                                
                               <input type="email" name="email" value="{{ old('emal') }}" data-validation="length"  data-validation-length="max50" class="form-control" placeholder="Enter Email Address">
                                
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
  

      //submit function
      $("#formPartnerDetail").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url,1);
      //refreshTable("{{route('contact.table')}}");
    });
   
});
</script>

