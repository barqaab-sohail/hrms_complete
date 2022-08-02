
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createStaff" data-toggle="modal" >Add Staff</button>
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Employee Name</th>
          <th>Position</th>
          <th>From</th>
          <th>To</th>
          <th>Status</th>
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
                <form id="staffForm" name="staffForm" class="form-horizontal">
                   
                   <input type="hidden" name="staff_id" id="staff_id">
                 
                    <div class="form-group">
                        <label class="control-label text-right">Employee Name<span class="text_requried">*</span></label>
                        <select  id="hr_employee_id"   name="hr_employee_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($hrEmployees as $hrEmployee)
                            <option value="{{$hrEmployee->id}}" {{(old("hr_employee_id")==$hrEmployee->id? "selected" : "")}}>{{$hrEmployee->full_name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Position</label>
                        <input type="text" name="position" id="position"  value="{{old('position')}}" class="form-control" data-validation="required" >
                    </div>

                    <div class="form-group">
                      <label class="control-label">From</label>
                       <input type="text" name="from" id="from" value="{{ old('from') }}" class="form-control date_input" data-validation="required" readonly >
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i> 
                    </div>

                    <div class="form-group">
                      <label class="control-label">To</label>
                       <input type="text" name="to" id="to" value="{{ old('to') }}" class="form-control date_input" data-validation="required" readonly >
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i> 
                    </div>

                    <div class="form-group">
                      <label class="control-label text-right">Status</label>
                      <select  name="status"  id="status" class="form-control selectTwo" data-validation="required">
                        <option></option>
                        <option value="Working">Working</option>
                        <option value="Input Ended">Input Ended</option>
                      </select> 
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
        ajax: "{{ route('projectConsultancyCost.create') }}",
        columns: [
            {data: "hr_employee_id", name: 'hr_employee_id'},
            {data: "position", name: 'position'},
            {data: "from", name: 'from'},
            {data: "to", name: 'to'},
            {data: "status", name: 'status'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createStaff').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Cost");
        $('#hr_employee_id').val('');
        $('#hr_employee_id').trigger('change');
        $('#staff_id').val('');
        $('#staffForm').trigger("reset");
        $('#modelHeading').html("Create New Staff");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editStaff', function () {
      var staff_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/projectStaff') }}" +'/' + staff_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Staff");
          $('#saveBtn').val("edit-Staff");
          $('#ajaxModel').modal('show');
          $('#staff_id').val(data.id);
          $('#hr_employee_id').val(data.hr_employee_id);
          $('#hr_employee_id').trigger('change');
          $('#position').val(data.position);
          $('#from').val(data.from);
          $('#to').val(data.to);
          $('#status').val(data.status);
      })
   });
    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#staffForm').serialize(),
          url: "{{ route('projectStaff.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#staffForm').trigger("reset");
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
    
    $('body').on('click', '.deleteCost', function () {
     
        var staff_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectStaff.store') }}"+'/'+staff_id,
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
