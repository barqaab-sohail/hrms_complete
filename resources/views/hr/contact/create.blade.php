
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Contact</button>
    </div>
         
    <div class="card-body">
        <form id= "formContact" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('contact.store')}}" enctype="multipart/form-data">
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Contact Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Contact Type<span class="text_requried">*</span></label>
                                 <select  id="hr_contact_type_id"   name="hr_contact_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($hrContactTypes as $hrContactType)
                                    <option value="{{$hrContactType->id}}" {{(old("hr_contact_type_id")==$hrContactType->id? "selected" : "")}}>{{$hrContactType->name}}</option>
                                    @endforeach 
                                </select>
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">House No.</label><br>

                                <input type="text"  name="house" value="{{ old('house') }}"  class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter House No">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Street No/Society</label>
                                
                                <input type="text" name="street" value="{{ old('street') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter Street No and Society">

                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Town/Village</label>
                               <input type="text" name="town" value="{{ old('town') }}" class="form-control" data-validation=" required length"  data-validation-length="max190" placeholder="Enter Town or Village">

                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                              <label class="control-label text-right">Tehsil</span></label> 
                              <input type="text" name="tehsil" value="{{ old('tehsil') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter Tehsil">
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">City<span class="text_requried">*</span></label>
                                <select class="form-control selectTwo" name="city_id" data-placeholder="First Select Province" data-validation="required"  id="city">
                                </select>
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Province<span class="text_requried">*</span></label>
                                <select class="form-control selectTwo" name="state_id" data-placeholder="First Select Country" data-validation="required" id="state">
                                </select>  
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                     <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Country<span class="text_requried">*</span></label><br>
                                <select  name="country_id" id="country" data-validation="required" class="form-control selectTwo">
                                    <option value=""></option>
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{(old("country_id")==$country->id? "selected" : "")}}>{{$country->name}}</option>
                                    @endforeach     
                                </select> 
                                    
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Mobile No.<span class="text_requried">*</span></label>
                                
                               <input type="text" name="mobile" id="mobile" pattern="[0-9.-]{12}" title= "11 digit without dash" value="{{ old('mobile') }}" data-validation="required length"  data-validation-length="max15" class="form-control" placeholder="0345-0000000">
                               
                            </div>
                        </div>
                    </div>
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
            @can('hr edit contact')
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Contact</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </form>

        
	</div> <!-- end card body -->    
    <div class="row">
      <div class="col-md-12 table-container">

      </div>
    </div>
<script>
$(document).ready(function(){
    
    refreshTable("{{route('contact.table')}}",300);
    isUserData(window.location.href, "{{URL::to('/hrms/employee/user/data')}}");
     
    $('#formContact').hide();
    $('#hideButton').click(function(){
        $('#formContact').toggle();
        resetForm();
    });

      //submit function
      $("#formContact").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url,1);
      refreshTable("{{route('contact.table')}}");
    });


    $('#country').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('country/states')}}"+"/"+cid,

             //url:"http://localhost/hrms4/public/country/"+cid, **//Please see the note at the end of the post**
             success:function(res)
             {       
                  if(res)
                  {
                      $("#state").empty();
                     $("#city").empty();
                      $("#state").append('<option value="">Select State</option>');
                      $.each(res,function(key,value){
                          $("#state").append('<option value="'+key+'">'+value+'</option>');
                          
                      });
                       $('#state').select2('destroy');
                       $('#state').select2();

                  }
             }

          });//end ajax
        }
    }); 

    //get cities through ajax
    $('#state').change(function(){
      var sid = $(this).val();
        if(sid){
          $.ajax({
             type:"get",
              url: "{{url('country/cities')}}"+"/"+sid,
             success:function(res)
             {       
                  if(res)
                  {
                      $("#city").empty();
                      $("#city").append('<option value="">Select City</option>');
                      $.each(res,function(key,value){
                          $("#city").append('<option value="'+key+'">'+value+'</option>');
                      });
                       $('#city').select2('destroy');
                       $('#city').select2();
                  }
             }

          });
        }

    });
});
</script>

