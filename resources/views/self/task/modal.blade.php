<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
           <form id="taskFrom" action="" method="post" class="form-horizontal form-prevent-multiple-submits form-prevent-multiple-submits" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="form-body">
                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Task Detail<span class="text_requried">*</span></label>
                                  
                         <input type="text"  id='task_detail' name="task_detail" value="{{ old('task_detail') }}" class="form-control" placeholder="Enter Task Detail" data-validation=" required">
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Completion Date<span class="text_requried">*</span></label>
                                      
                        <input type="text" id="completion_date" name="completion_date" value="{{ old('completion_date') }}" class="form-control date_input readonly"  placeholder="Enter Task Completion Date" data-validation=" required" readonly>
                        
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                                                
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Target Completion Date<span class="text_requried">*</span></label>
                                      
                        <input type="text" id="target_completion" name="target_completion" value="{{ old('target_completion') }}" class="form-control date_input readonly"  placeholder="Target date must be less than completion date" data-validation=" required" readonly>
                        
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                                                
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <label class="control-label text-right">Remarks</label>
                                      
                        <input type="text" name="remakrs" value="{{ old('remakrs') }}" class="form-control"  placeholder="Enter Remarks if any" >
                                                
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
<!--end Model-->

<script>
 $.validate({
    validateHiddenInputs: true,
    });

$(document).ready(function () {
         
    load_data("{{route('task.index')}}");

    // $(".readonly").keydown(function(e){
    //     e.preventDefault();
    // });

    (function(){
    $('.form-prevent-multiple-submits').on('submit', function(){

        $('.btn-prevent-multiple-submits').attr('disabled','ture');
        $('.spinner').show();

        //submit enalbe after 5 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
             $('.spinner').hide();
            }, 500);

    })

    })();


     //task sumbmit
  $('#taskFrom').on('submit', function(event){

  var url = "{{route('task.store')}}"
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
           
            if(data.status =='OK'){
                load_data("{{route('task.index')}}");
                $('#taskFrom').trigger("reset");
                $('#taskModal').modal('toggle');
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');

            }else{
               
                
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


    
});  // end document ready function


     //If Click icon than clear date
    $(".date_input").siblings('i').click(function (){
        if(confirm("Are you sure to clear date")){
        $(this).siblings('input').val("");
        $(this).hide();
        $(this).siblings('span').text("");
        }
    });


    // DatePicker
   $(".date_input").datepicker({
     onSelect: function(value, ui) {
        $(this).siblings('i').show();
        var today = new Date(), 
            age = today.getFullYear() - ui.selectedYear;
        $('#age').text(age+' years total age');
    },
    dateFormat: 'D, d-M-yy',
    yearRange: '1940:'+ (new Date().getFullYear()+15),
    changeMonth: true,
    changeYear: true,

    });



 

</script>