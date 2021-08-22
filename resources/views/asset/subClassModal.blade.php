<!-- Modal -->
<div class="modal fade" id="assetSubClassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Sub Class</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
           <form id="subClassModalFrom" action="" method="post" class="form-horizontal form-prevent-multiple-submits form-prevent-multiple-submits" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                          <select  name="as_class_id"  id= "modal_as_class_id" class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach($asClasses as $class)
                             <option value="{{$class->id}}" {{(old("as_class_id")==$class->id? "selected" : "")}}>{{$class->name}}</option>
                              @endforeach     
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                   <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Name of Sub Class<span class="text_requried">*</span></label><br>
                        <input type="text" name="name" id="sub_class_name" value="{{ old('name') }}" class="form-control" placeholder="Enter Asset Sub Class Name" required>
                      </div>
                    </div>                                                                
                  </div>
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


    $('#subClassModalFrom').on('submit', function(event){
       
      var url = "{{route('asset.storeSubClass')}}"
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
                     
                      if(data.subClasses ==''){
                          $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                          $('.spinner').hide();
                           $('.btn-prevent-multiple-submits').removeAttr('disabled');
                          $('#assetSubClassModal').modal('toggle');
                          $('#modal_as_class_id').val('').select2('val', 'All');
                          $('#sub_class_name').val('');
                          $('html,body').scrollTop(0);
                      }else{
                          $("#as_sub_class_id").empty();
                    

                          $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                           $('.spinner').hide();
                           $('.btn-prevent-multiple-submits').removeAttr('disabled');
                           //after sucessfull save clear input fields
                            $('#sub_class_name').val('');
                            $('#modal_as_class_id').val('').select2('val', 'All');
                          $('#assetSubClassModal').modal('toggle');
                        clearMessage(); 
                      }

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
                       $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
                       $('html,body').scrollTop(0);
                        $('.spinner').hide();
                           $('.btn-prevent-multiple-submits').removeAttr('disabled');
                       $('#assetSubClassModal').modal('toggle');
                      $('#sub_class_name').val('');                
                          
                  }//end error
              }); //end ajax

     
    }); //end submit




});
</script>
<!--end Model--> 