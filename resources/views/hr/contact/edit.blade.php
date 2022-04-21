         
    <div class="card-body">
        <form id= "formEditContact" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('contact.update',$data->id??'')}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Edit Contact Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Contact Type<span class="text_requried">*</span></label>
                                 <select  id="hr_contact_type_id"   name="hr_contact_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($hrContactTypes as $hrContactType)
                                    <option value="{{$hrContactType->id}}" {{(old('hr_contact_type_id',$data->hr_contact_type_id??'')==$hrContactType->id? "selected" : "")}}>{{$hrContactType->name}}</option>
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

                                <input type="text"  name="house" value="{{ old('house',$data->house??'') }}"  class="form-control" placeholder="Enter House No" data-validation="length"  data-validation-length="max190">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Street No/Society</label>
                                
                                <input type="text" name="street" value="{{ old('street',$data->street??'') }}" class="form-control" placeholder="Enter Street No and Society" data-validation="length"  data-validation-length="max190">

                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Town/Village<span class="text_requried">*</span></label>
                                
                               <input type="text" name="town" value="{{ old('town',$data->town??'') }}" class="form-control" placeholder="Enter Town or Village" data-validation="required length" data-validation-length="max190">

                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Tehsil</span></label>
                                
                               <input type="text" name="tehsil" value="{{ old('tehsil',$data->tehsil??'') }}" class="form-control" placeholder="Enter Tehsil" data-validation="length"  data-validation-length="max190">
                                
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">City<span class="text_requried">*</span></label>
                                <select class="form-control selectTwo" name="city_id" data-placeholder="First Select Province" data-validation="required"  id="city">
                                    <option value=""></option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" {{(old("city_id",$data->city_id??'')==$city->id? "selected" : "")}}>{{$city->name}}</option>
                                    @endforeach
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
                                        <option value="">'</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->id}}" {{(old("state_id",$data->state_id??'')==$state->id? "selected" : "")}}>{{$state->name}}</option>
                                    @endforeach     

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
                                    <option value="{{$country->id}}" {{(old("country_id", $data->country_id??'')==$country->id? "selected" : "")}}>{{$country->name}}</option>
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
                                
                               <input type="text" name="mobile" id="mobile" pattern="[0-9.-]{12}" title= "11 digit without dash" value="{{ old('mobile',$data->mobile->mobile??'') }}" class="form-control" placeholder="0345-0000000" data-validation="required">
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Landline No.</label>
                                
                               <input type="text" name="landline" value="{{ old('landline',$data->landline->landline??'') }}" class="form-control" placeholder="0092-42-00000000">
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Email</label>
                                
                               <input type="email" name="email" value="{{ old('emal',$data->email->email??'') }}" class="form-control" placeholder="Enter Email Address">
                                
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

    <div class="row">
        <div class="col-md-12 table-container">
            
        </div>
    </div>
	</div> <!-- end card body -->    

<script>
$(document).ready(function(){
   
     refreshTable("{{route('contact.table')}}");
     formFunctions();

      // $("form").submit(function (e) {
      //    e.preventDefault();
      //    });


    $('#formEditContact').on('submit', function(event){  
      var url = $(this).attr('action');
     $('.fa-spinner').show();     
     event.preventDefault();
      submitForm(this, url);
      refreshTable("{{route('contact.table')}}",300);
        
    }); //end submit

    

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
                       $('#state').select2('destroy');
                       $('#state').select2();
                  }
             }

          });
        }

    });
});
</script>

