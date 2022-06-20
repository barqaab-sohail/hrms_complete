
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createNewManager" data-toggle="modal" >Add New HOD</button>
  <br>
  <table class="table table-striped data-table">
    <thead>
      <tr>
          <th>HOD</th>
          <th>Effective Date</th>
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
                <form id="managerForm" name="manageForm" action="{{route('manager.store')}}"class="form-horizontal">
                   <input type="hidden" name="employee_manager_id" id="employee_manager_id">
                   <input type="hidden" name="hr_employee_id" id="hr_employee_id" value="{{session('hr_employee_id')}}">
                    <div class="form-group">
                        <label class="control-label text-right">HOD<span class="text_requried">*</span></label><br>
                          <select  name="hr_manager_id"  id="hr_manager_id" class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach ($employees as $employee)
                              <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}} -  {{$employee->hrDesignation->last()->name??''}}</option>
                              @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>
                                
                                <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" data-validation="required" readonly >

                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
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
  
  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('manager.create') }}",
        columns: [
            {data: "fullName", name: 'hr_manager_id'},
            {data: 'effective_date', name: 'effective_date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#createNewManager').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-HOD");
        $('#employee_manager_id').val('');

        $('#managerForm').trigger("reset");
        $('#hr_manager_id').trigger('change');
        $('#modelHeading').html("Create New HOD");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editManager', function () {
      var manager_id = $(this).data('id');

      $('#json_message_modal').html('');
      $.get("{{ url('hrms/manager') }}" +'/' + manager_id +'/edit', function (data) {
          $('#modelHeading').html("Edit HOD");
          $('#saveBtn').val("edit-HOD");
          $('#ajaxModel').modal('show');
          $('#employee_manager_id').val(data.id);
          $('#hr_manager_id').val(data.hr_manager_id);
          $('#hr_manager_id').trigger('change');
          $('#effective_date').val(data.effective_date); 
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
          data: $('#managerForm').serialize(),
          url: "{{ route('manager.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#managerForm').trigger("reset");
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
    
    $('body').on('click', '.deleteManager', function () {
     
        var employee_manager_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('manager.store') }}"+'/'+employee_manager_id,
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
