<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Permission</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formPermission" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Employee Permission</h3>
            <hr class="m-t-0 m-b-40">
              <input type="hidden" name="contact_id" id="contact_id"/>
              
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

                                <input type="text"  name="house"  id="house" value="{{ old('house') }}"  class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter House No">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Street No/Society</label>
                                
                                <input type="text" name="street" id="street" value="{{ old('street') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter Street No and Society">

                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Town/Village</label>
                               <input type="text" name="town" id="town" value="{{ old('town') }}" class="form-control" data-validation=" required length"  data-validation-length="max190" placeholder="Enter Town or Village">

                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                              <label class="control-label text-right">Tehsil</span></label> 
                              <input type="text" name="tehsil" id="tehsil" value="{{ old('tehsil') }}" class="form-control" data-validation="length"  data-validation-length="max190" placeholder="Enter Tehsil">
                                
                               
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
                                
                               <input type="text" name="landline" id="landline" value="{{ old('landline') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
                                
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                              <label class="control-label text-right">Email</label>
                                
                              <input type="email" name="email" id="email"  value="{{ old('emal') }}" data-validation="length"  data-validation-length="max50" class="form-control" placeholder="Enter Email Address">
                                
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

    <br>
        <table class="table table-bordered data-table" width=100%>
            <thead>
            <tr>
                <th>Contact Type</th>
                <th>Address</th> 
                <th>Mobile</th> 
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th> 
                <!-- <th colspan="2" class="text-center"style="width:10%"> Actions </th>  -->
            </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>


   

        
<script type="text/javascript">
$(document).ready(function(){
    // formFunctions();
    $('#formPermission').hide();   
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
      //start function
$(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax:{url:"{{ route('contact.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "contact_type_id", name: 'contact_type_id'},
            {data: "address", name: 'address'},
            {data: "mobile", name: 'mobile'},
            {data: "email", name: 'email'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
          
            $('#formPermission').toggle();
            $('#formPermission').trigger("reset");
            $('#contact_id').val('');
            $('#hr_contact_type_id').trigger('change');
            $('#city').trigger('change');
            $('#state').trigger('change');
            $('#country').trigger('change');
    });

    $('body').unbind().on('click', '.editContact', function () {


      var contact_id = $(this).data('id');
        
      $.get("{{ url('hrms/contact') }}" +'/' + contact_id +'/edit', function (data) {
          $('#formPermission').show(); 
          $('#formHeading').html("Edit Contact");
          $('#contact_id').val(data.id);
          $('#hr_contact_type_id').val(data.hr_contact_type_id).trigger('change');
          $('#country').val(data.country_id).trigger('change');
          $('#house').val(data.house);
          $('#street').val(data.street);
          $('#town').val(data.town);
          $('#tehsil').val(data.tehsil);
          $('#mobile').val(data.mobile?.mobile);
          $('#landline').val(data.landline?.landline);
          $('#email').val(data.email?.email);
            setTimeout(function() { 
            $('#state').val(data.state_id).trigger('change');
            }, 1000);
            setTimeout(function() { 
                $('#city').val(data.city_id).trigger('change');
            }, 2000);
         
      })
   });
    
      $("#formPermission").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        console.log(this);
        $.ajax({
          data: formData,
          url: "{{ route('contact.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{

                $('#formPermission').trigger("reset");
                $('#formPermission').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');  

                table.draw();
              }
        
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteContact', function () {
     
        var contact_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('contact.store') }}"+'/'+contact_id,
            success: function (data) {
                table.draw();
                $('#formPermission').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                
            }
          });
        }
    });
  });// end function

      
            
</script>