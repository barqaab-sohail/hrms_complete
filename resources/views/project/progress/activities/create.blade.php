
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createActivity" data-toggle="modal" >Add Activity</button>
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Activity Name</th>
          <th>Weightage</th>
          <th>Edit</th>
          <th>Delete</th>
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
                <form id="activityForm" name="activityForm" action="{{route('projectProgressActivities.store')}}"class="form-horizontal">
                   
                  <input type="hidden" name="activity_id" id="activity_id">

                  <div class="row">
                    <div class="col-md-9">
                      <div class="form-group">
                        <label class="control-label">Activity Nane</label>
                        <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control notCapital" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label">Weightage</label>
                        <input type="text" name="weightage" id="weightage" value="{{old('weightage')}}" class="form-control notCapital" data-validation="required">
                      </div>
                    </div>
                    
                  </div>
                
                    
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save changes
                   </button>
                  </div>
              </form>
            </div>
        </div>
    </div>
</div>

<style>
  .modal-dialog {
    max-width: 80%;
    display: flex;
}
</style>

<script type="text/javascript">

$(document).ready(function() {
  
  
  //only number value entered
    $('#weightage').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    

  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('projectProgressActivities.create') }}",
        columns: [
            {data: "name", name: 'name'},
            {data: "weightage", name: 'weightage'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createActivity').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Activity");
        $('#activity_id').val('');name
        $('#activityForm').trigger("reset");
        $('#modelHeading').html("Create New Activity");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editActivity', function () {
      
      var activity_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectProgressActivities') }}" +'/' + activity_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Activity");
          $('#saveBtn').val("edit-Activity");
          $('#ajaxModel').modal('show');
          $('#activity_id').val(data.id);
          $('#name').val(data.name);
          $('#weightage').val(data.weightage);
      })
   });
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        $(this).html('Save');
         
        $.ajax({
          data: $('#activityForm').serialize(),
          url: "{{ route('projectProgressActivities.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#activityForm').trigger("reset");
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
    
    $('body').on('click', '.deleteActivity', function () {
     
        var activity_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectProgressActivities.store') }}"+'/'+activity_id,
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
