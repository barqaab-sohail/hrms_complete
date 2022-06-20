
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createNewSalary" data-toggle="modal" >Add New Salary</button>
  <br>
  <table class="table table-striped data-table">
    <thead>
      <tr>
          <th>Total Salary</th>
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
                <form id="salaryForm" name="salaryForm" action="{{route('employeeSalary.store')}}"class="form-horizontal">
                   <input type="hidden" name="employee_salary_id" id="employee_salary_id">
                   <input type="hidden" name="hr_employee_id" id="hr_employee_id" value="{{session('hr_employee_id')}}">
                    <div class="form-group">
                        <label class="control-label text-right">Total Salary<span class="text_requried">*</span></label><br>
                        <input type="text" name="hr_salary" id="hr_salary" value="{{old('hr_salary')}}" class="form-control" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>
                                
                        <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" data-validation="required" readonly >

                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
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

  //only number value entered
    $('#hr_salary').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#hr_salary').keyup(function(event) {

      // skip for arrow keys
      if(event.which >= 37 && event.which <= 40) return;

      // format number
      $(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
      });
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
        ajax: "{{ route('employeeSalary.create') }}",
        columns: [
            {data: "totalSalary", name: 'hr_salary'},
            {data: 'effective_date', name: 'effective_date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#createNewSalary').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-Salary");
        $('#employee_salary_id').val('');
        $('#salaryForm').trigger("reset");
        $('#hr_salary').val('');
        $('#modelHeading').html("Create New Salary");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editSalary', function () {
      var employee_salary_id = $(this).data('id');

      $('#json_message_modal').html('');
      $.get("{{ url('hrms/employeeSalary') }}" +'/' + employee_salary_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Salary");
          $('#saveBtn').val("edit-Salary");
          $('#ajaxModel').modal('show');
          $('#employee_salary_id').val(data.id);
          var totalSalary = (data.hr_salary.total_salary).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#hr_salary').val(totalSalary);
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
          data: $('#salaryForm').serialize(),
          url: "{{ route('employeeSalary.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#salaryForm').trigger("reset");
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
    
    $('body').on('click', '.deleteSalary', function () {
     
        var employee_salary_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('employeeSalary.store') }}"+'/'+employee_salary_id,
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
