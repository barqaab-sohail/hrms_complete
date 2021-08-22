<!-- Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Employee Input</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i>
</div>
           <form id="projectModalFrom"  class="form-horizontal form-prevent-multiple-submits form-prevent-multiple-submits" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Name of Employee<span class="text_requried">*</span></label><br>
                          <select  id="hr_employee_id"   name="hr_employee_id"  class="form-control" data-validation="required">
                            <option></option>
                            @foreach($hrEmployees as $employee)
                            <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}}</option>
                            @endforeach  
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Designation<span class="text_requried">*</span></label><br>
                        <select  id="hr_designation_id"   name="hr_designation_id"  class="form-control" data-validation="required">
                            <option></option>
                            @foreach($hrDesignations as $hrDesignation)
                            <option value="{{$hrDesignation->id}}" {{(old("hr_designation_id")==$hrDesignation->id? "selected" : "")}}>{{$hrDesignation->name}}</option>
                            @endforeach  
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Input<span class="text_requried">*</span></label><br>
                        <input type="text" name="input" id="input" value="{{ old('input') }}" class="form-control" data-validation="required number" data-validation-allowing="range[0.03;1.0],float">
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Remarks</label><br>
                        <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control">
                      </div>
                    </div>                                                                
                  </div>
                  <input type="text" name="hr_input_project_id" id="hr_input_project_id" class="form-control" hidden required>

                  <input type="text" name="month_id" id="month_id" class="form-control" hidden required>
                  
                   <hr>
                  <div class="form-actions">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="row">
                                  <div class="col-md-offset-3 col-md-9">
                                      <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="spinner fa fa-spinner fa-spin" ></i>Save</button>   
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </form>

      </div>
      
    </div>
  </div>
</div>

<script>

$(document).ready(function(){
    $.validate({
    validateHiddenInputs: true,
    });

    $('#hr_employee_id, #hr_designation_id').select2({
        dropdownParent: $('#projectModal'),
        width: "100%",
        theme: "classic"
    });


    $('#projectModalFrom').on('submit', function(event){
      var url = "{{route('input.store')}}"
        event.preventDefault();
              //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
              $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
                  var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

                  if (token) {
                      return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
                  }
              });
               var data = new FormData(this);

             // ajax request
              $.ajax({
                 url:url,
                 method:"POST",
                 data:data,
                 //dataType:'JSON',
                 contentType: false,
                 cache: false,
                 processData: false,
                 success:function(data){
                    
                     var num = parseInt($("#inputTable tr:last-child td:first-child").html())+1;
                        if(!num){num=1;}
                      var editUrl='{{route("input.edit",":id")}}';
                          editUrl= editUrl.replace(':id', data['id']);

                      $("#inputTable tbody").append('<tr><td>'+ num +
                        '</td><td>'+data['hr_employee']['first_name']+' '+data['hr_employee']['last_name']+
                        '</td><td>'+data['hr_designation']['name']+
                        '</td><td>'+data['input']+
                        '</td><td>'+data['remarks']+
                        '</td><td><form id=deleteForm'+num+' action='+"{{route('input.destroy','')}}"+
                        '/'+data['id']+' method="POST"><a class="btn btn-primary"href='
                          +editUrl+'>Edit</a>@csrf @method("DELETE")<button type="submit" class="btn btn-danger">Delete</button></form></td></tr>');  

                     $('#hr_employee_id, #hr_designation_id').val('').select2('val', 'All');
                     $('#input, #remarks').val('');
                     $("#projectModal").modal('hide');     
                 },
                  error: function (jqXHR, textStatus, errorThrown){
                      if (jqXHR.status == 401){
                          location.href = "{{route ('login')}}"
                          }      
                      var test = jqXHR.responseJSON // this object have two more objects one is errors and other is message.
                      
                      var errorMassage = '';

                      //now saperate only errors object values from test object and store in variable errorMassage;
                      $.each(test.errors, function (key, value){
                      errorMassage += value + '<br>';  
                      });
                       $('#json_message_modal').html('<div id="json_message_modal" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
                       $('html,body').scrollTop(0);                
                          
                  }//end error
              }); //end ajax

     
    }); //end submit

});
</script>
<!--end Model--> 