
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createMonthlyProgress" data-toggle="modal" >Add Monthly Progress</button>
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Activity Name</th>
          <th>Month</th>
          <th>Scheduled Progress</th>
          <th>Actual Progress</th>
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
                <form id="monthlyProgressForm" name="monthlyProgressForm" action="{{route('monthlyProgress.store')}}"class="form-horizontal">
                   
                  <input type="hidden" name="monthlyProgress_id" id="monthlyProgress_id">

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Activity Name</label>
                        <select  id="pr_progress_activity_id"   name="pr_progress_activity_id"  class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach($progressActivities as $progressActivity)
                              <option value="{{$progressActivity->id}}" {{(old("pr_progress_activity_id")==$progressActivity->id? "selected" : "")}}>{{$progressActivity->name}}</option>
                              @endforeach 
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">Month</label>
                        <input type="text" name="date" id="date" value="{{ old('date') }}" class="form-control" data-validation="required" readonly >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">Schedule Progress</label>
                        <input type="text" name="scheduled" id="scheduled" value="{{old('scheduled')}}" class="form-control notCapital" data-validation="required">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="control-label">Actual Progress</label>
                        <input type="text" name="actual" id="actual" value="{{old('actual')}}" class="form-control notCapital" data-validation="required">
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
    max-width: 80%;
    display: flex;
}

.ui-datepicker-calendar {
    display: none;
}?
</style>

<script type="text/javascript">

$(document).ready(function() {
  
  //only number value entered
    $('#scheduled,#acutal ').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    $('#date').datepicker({
     changeMonth: true,
     changeYear: true,
     dateFormat: 'MM yy',

       onClose: function() {
          var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
          var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
          $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
       },

        beforeShow: function() {
         if ((selDate = $(this).val()).length > 0) 
         {
            iYear = selDate.substring(selDate.length - 4, selDate.length);
            iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
                     $(this).datepicker('option', 'monthNames'));
            $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
            $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
         }
        }
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
        ajax: "{{ route('monthlyProgress.create') }}",
        columns: [
            {data: "pr_progress_activity_id", name: 'pr_progress_activity_id'},
            {data: "date", name: 'date'},
            {data: "scheduled", name: 'scheduled'},
            {data: "actual", name: 'actual'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
     
        order: [[ 1, "desc" ]]
    });

    $('#createMonthlyProgress').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Progress");
        $('#monthlyProgress_id').val('');
        $('#pr_progress_activity_id').val('');
        $('#pr_progress_activity_id').trigger('change');
        $('#monthlyProgressForm').trigger("reset");
        $('#modelHeading').html("Create New Progress");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editMonthlyProgress', function () {
      
      var monthlyProgress_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/monthlyProgress') }}" +'/' + monthlyProgress_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Activity");
          $('#saveBtn').val("edit-Activity");
          $('#ajaxModel').modal('show');
          $('#monthlyProgress_id').val(data.id);
          $('#pr_progress_activity_id').val(data.pr_progress_activity_id);
          $('#pr_progress_activity_id').trigger('change');
          var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var d = new Date(data.date);
          var  dateformat = months[d.getMonth()]+"-"+d.getFullYear();
          $('#date').val(dateformat);
          $('#scheduled').val(data.scheduled);
          $('#actual').val(data.actual);
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
          data: $('#monthlyProgressForm').serialize(),
          url: "{{ route('monthlyProgress.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#monthlyProgressForm').trigger("reset");
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
    
    $('body').on('click', '.deleteMonthlyProgress', function () {
     
        var monthlyProgress_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('monthlyProgress.store') }}"+'/'+monthlyProgress_id,
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
