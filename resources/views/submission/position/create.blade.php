
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubmissionPosition" data-toggle="modal" >Add Position</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:15%">Position</th>
          <th style="width:15%">Proposed Name</th>
          <th style="width:60%">Man Month</th>
          <th style="width:60%">CV</th>
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
              <form id="submissionPositionForm" name="submissionPositionForm" class="form-horizontal">
                   
                  <input type="hidden" name="sub_position_id" id="sub_position_id">
                  
                        <div class="form-group">
                            <label class="control-label">Position<span class="text_requried">*</span></label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}" class="form-control">     
                        </div>
                        <div class="form-group">
                        <label class="control-label text-right">Employee</label><br>
                          <select  name="hr_employee_id"  id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach ($employees as $employee)
                              <option value="{{$employee->id}}">{{$employee->employee_no}} - {{$employee->full_name}}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Proposed Person<span class="text_requried">*</span></label>
                          <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control">     
                        </div>
                   
                      <div class="form-group">
                          <label class="control-label">Man Month</label>
                          <input type="text" name="man_month"  id="man_month" value="{{old('man_month')}}" class="form-control">
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
  .ui-timepicker-container{ 
     z-index:1151 !important; 
  }
</style>

<script type="text/javascript">


$(document).ready(function() {

  $("#hr_employee_id").change(function (){
    $("#name").val($("#hr_employee_id option:selected").text());
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
        destroy: true,
        ajax: "{{ route('submissionPosition.create') }}",
        columns: [
            {data: "position", name: 'position'},
            {data: "name", name: 'name'},
            {data: "man_month", name: 'man_month'},
            {data: "cv", name: 'cv'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createSubmissionPosition').click(function () {
        $('#json_message_modal').html('');
        $('#submissionPositionForm').trigger("reset");
        $('#sub_position_id').val('');
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editSubmissionPosition', function () {
      var sub_position_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionPosition') }}" +'/' + sub_position_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Position");
          $('#saveBtn').val("edit-Position");
          $('#sub_position_id').val(data.id);
          $('#position').val(data.position);
          if(data.sub_nominate_person){
            $('#hr_employee_id').val(data.sub_nominate_person.hr_employee_id);
            $('#hr_employee_id').trigger('change');
            $('#name').val(data.sub_nominate_person.name);
          }
          if(data.sub_man_month){
            $('#man_month').val(data.sub_man_month.man_month);
          }


          $('#ajaxModel').modal('show');
      })
    });
    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save'); 
        $.ajax({
          data: $('#submissionPositionForm').serialize(),
          url: "{{ route('submissionPosition.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#submissionPositionForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw(); 
              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
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
    
    $('body').on('click', '.deleteSubmissionPosition', function () {
     
        var sub_position_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('submissionPosition.store') }}"+'/'+sub_position_id,
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
