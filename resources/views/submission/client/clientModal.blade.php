<!-- Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
           <form id="clientModalFrom" action="" method="post" class="form-horizontal form-prevent-multiple-submits form-prevent-multiple-submits" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Client Name<span class="text_requried">*</span></label><br>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Client Name" required>
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


    $('#clientModalFrom').on('submit', function(event){
       
      var url = "{{route('addClient')}}"
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
                     
                      if(data.clients ==''){
                          $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                          $('.spinner').hide();
                           $('.btn-prevent-multiple-submits').removeAttr('disabled');
                          $('#clientModal').modal('toggle');
                          $('html,body').scrollTop(0);
                      }else{
                          $("#client_id").empty();
                          $("#client_id").append('<option value="">Select Client</option>');
                          $.each(data.clients, function(key,value){
                                   $("#client_id").append('<option value="'+value.id+'">'+value.name+'</option>');
                          });
                          $('#client_id').select2('destroy');
                          selectTwo('#client_id');

                          $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                           $('.spinner').hide();
                           $('.btn-prevent-multiple-submits').removeAttr('disabled');
                           //after sucessfull save clear input fields
                          $('#name').val('');
                          $('#clientModal').modal('toggle');
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
                          
                  }//end error
              }); //end ajax

     
    }); //end submit


});
</script>
<!--end Model--> 