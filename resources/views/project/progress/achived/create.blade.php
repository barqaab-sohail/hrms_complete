
<div class="card-body">
  <br>
  <div id="json_message">
  </div>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Activity Name</th>
          <th>Total Weightage</th>
          <th>Total Progress Achieved</th>
          <th>Date</th>
          <th>Progress Completed</th>
          <th>Save</th>
          <th>Edit</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>
</div>
<!-- Modal for eadit progress -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
              <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
              <table class="table table-bordered edit-data-table">
                <thead>
                  <tr>
                      <th>Activity Name</th>
                      <th>Date</th>
                      <th>Progress</th>
                      <th>Save</th>
                      <th>Delete</th>
                  </tr>
                </thead>
                <tbody>
                    
                </tbody>
              </table>
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
  

  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('projectProgress.create') }}",
        columns: [
            {data: "name", name: 'name'},
            {data: "weightage", name: 'weightage'},
            {data: "progress_achived", name: 'progress_achived'},
            {data: 'Date', name: 'Date', orderable: false, searchable: false},
            {data: 'Progress', name: 'Progress', orderable: false, searchable: false},
            {data: 'Save', name: 'Save', orderable: false, searchable: false},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
        ],
     
        order: []
    });

    $('.data-table').unbind().on('click', '.editProgress', function () {
      
      var activity_id = $(this).data('id');
      console.log(activity_id);
      $('#ajaxModel').modal('show');
    $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    var table = $('.edit-data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('hrms/project/projectProgress') }}" +'/' + activity_id +'/edit',
        columns: [
            {data: "activity_name", name: 'activity_name'},
            {data: "date", name: 'date'},
            {data: "percentage_complete", name: 'percentage_complete'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
    
    });
    });

    });

    //$('.saveProgress').click(function (e) {  
     $(".data-table").on('click', '.saveProgress', function () {
       var activity_id = $(this).data('id');
       var date = $("#date"+activity_id).val();
       var progress = $("#progress"+activity_id).val();
        $.ajax({
          data: {'activity_id':activity_id,'date':date,'progress':progress},
          url: "{{ route('projectProgress.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
            $('#json_message').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
            
            clearMessage();

            table.draw();
        
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message').html('<div class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });

    //only number value entered
    $('body').unbind().on('change, keyup', '.progressInput', function () {
      var currentInput = $(this).val();
      var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
      $(this).val(fixedInput);
    });

    // DatePicker
    //$(".date_input").datepicker({
    $('body').on('mouseenter', '.date_input', function () {
      $(this).datepicker({
      dateFormat: 'D, d-M-yy',
      yearRange: '2022:'+ (new Date().getFullYear()+1),
      changeMonth: true,
      changeYear: true,
      });
    });

  });
     

});
</script>
