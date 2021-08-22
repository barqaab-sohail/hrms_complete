<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Month and Year</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i>
</div>
           <form id="editModalFrom"  class="form-horizontal form-prevent-multiple-submits form-prevent-multiple-submits" enctype="multipart/form-data">
                  @method('PATCH')
                  @csrf
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Month<span class="text_requried">*</span></label><br>
                          <select  name="month"  id="modalMonth" class="form-control" data-validation="required">
                            <option value=""></option>
                            @foreach($months as $month)
                            <option value="{{$month}}" {{(old("month")==$month? "selected" : "")}}>{{$month}}</option>
                            @endforeach     
                            </select>
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Year<span class="text_requried">*</span></label><br>
                        <select  name="year"  id="modalYear" class="form-control" data-validation="required">
                          <option value=""></option>
                          @foreach($years as $year)
                          <option value="{{$year}}" {{(old("year")==$year? "selected" : "")}}>{{$year}}</option>
                          @endforeach     
                          </select>
                      </div>
                    </div>                                                                
                  </div>
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Status<span class="text_requried">*</span></label><br>
                        <select  name="is_lock"  id="modalStatus" class="form-control" data-validation="required">
                            <option value=""></option>
                            <option value="0">Open</option>
                            <option value="1">Lock</option>  
                        </select>
                      </div>
                    </div>                                                                
                  </div>
                  
                  
                  <hr>
                  <div class="form-actions">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="row">
                                  <div class="col-md-offset-3 col-md-9">
                                      <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="spinner fa fa-spinner fa-spin" ></i>Save Changes</button>   
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

    $(document).on('click','a[id^=editMonthInput]',function(event){
      $("#editModal").modal('show');
      var tableTr = $(this).closest('tr').children('td:first');
      var tr = $(this).closest('tr').children('td:first').text().split('-');
      var isLock = $(this).closest('tr').children('td:nth-child(2)').text();
      var modalUrl = $(this).closest('tr').children('td:nth-child(3)').find('a').attr('url').replace('/edit','');
      var month = tr[0];
      var year = tr[1];
      
      $("#modalMonth").val(month);
      $("#modalYear").val(year);
      $("#modalStatus").val(isLock);
        
        //$(document).on('submit','#editModalFrom',function(e){
        $("#editModalFrom").submit(function(e) { 
          e.preventDefault();
          $('.fa-spinner').show(); 

        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });
        var data = new FormData(this)
        $.ajax({
           url:modalUrl,
           method:"POST",
           data:data,
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(res){
                if(res.status == 'OK'){
                  
                  $("#editModal").modal('hide');
                  tableTr
                 

                }
                else{
                $('#json_message_modal').html('<div id="j_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+res.message+'</strong></div>');    
                }
            $('html,body').scrollTop(0);
            $('.fa-spinner').hide();
            clearMessage();
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
                 $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
                 $('html,body').scrollTop(0);
                $('.fa-spinner').hide();                   
                    
            }//end error
       }); //end ajax

      }); //end submit

    }); //end edit modal


   

});
</script>
<!--end Model--> 