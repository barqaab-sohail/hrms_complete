
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createNewPosition" data-toggle="modal" >Add New Position</button>
  <br>
  <table class="table table-striped data-table">
    <thead>
      <tr>
        <th>Designation</th>
        <th>Position Type</th>
        <th>Total Manmonth</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>
</div>

<div class="modal fade" id="positionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
              <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="positionForm" name="positionForm" class="form-horizontal">
                  <input type="hidden" name="position_id" id="position_id">
                    <div class="form-group">
                        <label class="control-label text-right">Position<span class="text_requried">*</span></label><br>
                          <select  name="hr_designation_id"  id="hr_designation_id" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach($hrDesignations as $designation)
                              <option value="{{$designation->id}}" {{(old("hr_designation_id")==$designation->id? "selected" : "")}}>{{$designation->name}}</option>
                              @endforeach 
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Position Type<span class="text_requried">*</span></label><br>
                          <select  name="pr_position_type_id"  id="pr_position_type_id" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach($positionTypes as $positionType)
                              <option value="{{$positionType->id}}" {{(old("pr_position_type_id")==$positionType->id? "selected" : "")}}>{{$positionType->name}}</option>
                              @endforeach 
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Total Man Month</label><br>
                        <input type="number" step="0.001" id="total_mm"  name="total_mm" value="{{ old('total_mm') }}"  class="form-control" data-validation-allowing="range[0.001;6000.00],float"> 
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

<script type="text/javascript">
$(document).ready(function() {
  $('#hr_designation_id, #pr_position_type_id').select2({
        dropdownParent: $('#positionModal'),
        width: "100%",
        theme: "classic"
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
        ajax: "{{ route('projectPosition.create') }}",
        columns: [
            {data: "hr_designation_id", name: 'hr_designation_id'},
            {data: 'pr_position_type_id', name: 'pr_position_type_id'},
            {data: 'total_mm', name: 'total_mm'},
            {data: 'edit', name: 'edit', orderable: false, searchable: false},
            {data: 'delete', name: 'delete', orderable: false, searchable: false}

        ],
        order: [[ 2, "desc" ]]
    });

    $('#createNewPosition').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-Position");
        $('#position_id').val('');

        $('#positionForm').trigger("reset");
        $('#hr_designation_id').trigger('change');
        $('#pr_position_type_id').trigger('change');
        $('#modelHeading').html("Create New Position");
        $('#positionModal').modal('show');
    });
    $('body').unbind().on('click', '.editPosition', function () {
      var position_id = $(this).data('id');

      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectPosition') }}" +'/' + position_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Position");
          $('#saveBtn').val("edit-Position");
          $('#positionModal').modal('show');
          $('#position_id').val(data.id);
          $('#hr_designation_id').val(data.hr_designation_id);
          $('#hr_designation_id').trigger('change');
          $('#pr_position_type_id').val(data.pr_position_type_id);
          $('#pr_position_type_id').trigger('change');
          $('#total_mm').val(data.total_mm);
         
         
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
          data: $('#positionForm').serialize(),
          url: "{{ route('projectPosition.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#positionForm').trigger("reset");
              $('#positionModal').modal('hide');
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
    
    $('body').on('click', '.deletePosition', function () {
     
        var position_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectPosition.store') }}"+'/'+position_id,
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

