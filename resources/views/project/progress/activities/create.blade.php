
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createActivity" data-toggle="modal" >Add Activity</button>
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Level</th>
          <th>Belong to Activity</th>
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
                    <div class="col-md-1">
                      <div class="form-group">
                        <label class="control-label">Level</label>
                        <input type="text" name="level" id="level" value="{{old('level')}}" class="form-control notCapital" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Activity Name</label>
                        <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control notCapital" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-1" id="hideDivWeightage">
                      <div class="form-group">
                        <label class="control-label">Weightage</label>
                        <input type="text" name="weightage" id="weightage" data-validation="required" value="{{old('weightage')}}" class="form-control notCapital">
                      </div>
                    </div> 
                    <div class="col-md-4 hide" id="hideDiv">
                      <div class="form-group">
                        <label class="control-label">belong to Activity</label>
                        <select  name="belong_to_activity" id="belong_to_activity"  class="form-control selectTwo" >
                          <option value=""></option>
                            
                        </select>

                      </div>
                    </div>
                  </div>
                  <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                        <div class="form-check form-switch">
                        <br>
                          <input class="form-check-input" type="checkbox" id="sub_activity">
                          <label class="form-check-label" for="sub_activity">This Activity has Sub Activities</label>
                        </div>
                      </div>
                    </div>
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
  .modal-dialog {
    max-width: 90%;
    display: flex;
}
</style>

<script type="text/javascript">

$(document).ready(function() {
  

  //only number value entered
    $('#weightage, #level, #belong_to_activity').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    $('#sub_activity').change(function(){
      if(this.checked) {
        $('#hideDivWeightage').hide();
        $("#weightage").val('');
        $("#weightage").removeAttr("data-validation");  
      }else{
        $('#hideDivWeightage').show();
        $("#weightage").val('');
         $("#weightage").attr("data-validation","required");
      }
    });

    $("#level").focusout(function(){
        if($(this).val()>1){
          $("#hideDiv").show();
          $("#belong_to_activity").val('')
          getActivities($(this).val());
        }else{
          $("#hideDiv").hide();
          $("#belong_to_activity").empty();
        }
    });

    function getActivities($level, callback){
      $.ajax({
        type:"get",
        url: "{{url('hrms/project/proejctProgressMainActivities')}}"+"/"+$level,
        success:function(res)
        {       
            if(res)
            {
              $("#belong_to_activity").empty();
              $("#belong_to_activity").append('<option value="">Select Activity</option>');
              $.each(res,function(key,value){
                  $("#belong_to_activity").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        }

      });//end ajax
      
      if (typeof callback === 'function') {    
        setTimeout(function() { 
         callback(); 
        }, 1000);
         
      }
    }

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
            {data: "level", name: 'level'},
            {data: "belong_to_activity_name", name: 'belong_to_activity_name'},
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
        $('#activity_id').val('');
        $('#activityForm').trigger("reset");
        $('#hideDivWeightage').show();
        $("#belong_to_activity").empty();
        $("#hideDiv").hide();
        $('#modelHeading').html("Create New Activity");
        $('#ajaxModel').modal('show');
        
    });
    $('body').unbind().on('click', '.editActivity', function () {
      
      var activity_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectProgressActivities') }}" +'/' + activity_id +'/edit', function (data) {
          getActivities(data.level, function(){
            $('#belong_to_activity').val(data.belong_to_activity);
            $('#belong_to_activity').trigger('change');
          });

          $('#modelHeading').html("Edit Activity");
          $('#saveBtn').val("edit-Activity");
          $('#activity_id').val(data.id);
          $('#name').val(data.name);
          $('#level').val(data.level);
          $("#hideDivWeightage").show();
          $('#weightage').val(data.weightage);
          $("#hideDiv").show();
          $("#hideDiv").removeClass("hide");
          $('#ajaxModel').modal('show');
          
          
         
           
      })
    });
    $('#saveBtn').unbind().click(function (e) {
        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);
        
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
