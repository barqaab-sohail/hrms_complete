
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubmissionDate" data-toggle="modal" >Add Date & Time</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:15%">Date</th>
          <th style="width:15%">Time</th>
          <th style="width:60%">Address</th>
          <th style="width:5%">Edit</th>
          <th style="width:5%">Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
              <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
              <form id="submissionDateForm" name="submissionDateForm" class="form-horizontal">
                   
                  <input type="hidden" name="sub_date_id" id="sub_date_id">
                  
                        <div class="form-group">
                            <label class="control-label">Submission Date<span class="text_requried">*</span></label>
                            <input type="text" id="submission_date" name="submission_date" value="{{ old('submission_date') }}" class="form-control date_input" readonly>     
                            <br>
                            <i class="fas fa-trash-alt text_requried"></i>
                            
                   
                    
                        <div class="form-group">
                          <label class="control-label">Submission Time<span class="text_requried">*</span></label>
                          <input type="text" id="submission_time" name="submission_time" value="{{ old('submission_time') }}" class="form-control time_input" readonly>     
                          <br>
                          <i class="fas fa-trash-alt text_requried"></i>
                          
                        </div>
                   
                      <div class="form-group">
                          <label class="control-label">Address</label>
                          <input type="text" name="address"  id="address" value="{{old('address')}}" class="form-control">
                      </div>
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save changes
                   </button>
                  </div>
              </form>
            </div>
        </div>
    </div>
</div>
<style>
  .ui-timepicker-container{ 
     z-index:1151 !important; 
  }
</style>

<script type="text/javascript">


$(document).ready(function() {
  
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: "{{ route('submissionDate.create') }}",
        columns: [
            {data: "submission_date", name: 'submission_date'},
            {data: "submission_time", name: 'submission_time'},
            {data: "address", name: 'address'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createSubmissionDate').click(function () {
        $('#json_message_modal').html('');
        $('#submissionDateForm').trigger("reset");
        $('#sub_date_id').val('');
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editSubmissionDate', function () {
      var sub_date_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionDate') }}" +'/' + sub_date_id +'/edit', function (data) {

          $('#modelHeading').html("Edit Date");
          $('#saveBtn').val("edit-Date");
          $('#sub_date_id').val(data.id);
          $('#submission_date').val(data.submission_date);
          $('#submission_time').val(data.submission_time);
          $('#address').val(data.address);
          $('#ajaxModel').modal('show');
      })
    });
    $('#saveBtn').unbind().click(function (e) {
        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);
        
        e.preventDefault();
        $(this).html('Save'); 
        $.ajax({
          data: $('#submissionDateForm').serialize(),
          url: "{{ route('submissionDate.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#submissionDateForm').trigger("reset");
              $('#ajaxModel').modal('hide');
                table.draw(); 
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteSubmissionDate', function () {
     
        var sub_date_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('submissionDate.store') }}"+'/'+sub_date_id,
            success: function (data) {
                table.draw();
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                
            }
          });
        }
    });
     
  });
  
 
});
</script>
