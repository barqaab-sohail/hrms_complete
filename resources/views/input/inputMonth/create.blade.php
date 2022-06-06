@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
  <!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createNewMonth" data-toggle="modal" >Add New Month</button>
  <br>
  <table class="table table-striped data-table">
    <thead>
      <tr>
          <th>Month</th>
          <th>Year</th>
          <th>Status</th>
          <th>Edit</th>
          <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>
</div>

<div class="modal fade" id="monthModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
              <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="inputMonthForm" name="inputMonthForm" class="form-horizontal">
                   <input type="hidden" name="month_id" id="month_id">
                    <div class="form-group">
                        <label class="control-label text-right">Month<span class="text_requried">*</span></label><br>
                          <select  name="month"  id="month" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach ($months as $month)
                              <option value="{{$month}}">{{$month}}</option>
                              @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Year<span class="text_requried">*</span></label><br>
                          <select  name="year"  id="year" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach ($years as $year)
                              <option value="{{$year}}">{{$year}}</option>
                              @endforeach   
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">status<span class="text_requried">*</span></label><br>
                          <select  name="is_lock"  id="is_lock" class="form-control" data-validation="required">
                              <option value=""></option>
                              <option value="0">Open</option>
                              <option value="1">Lock</option>
                              
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
  $('#month, #year, #is_lock').select2({
        dropdownParent: $('#monthModal'),
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
        ajax: "{{ route('inputMonth.create') }}",
        columns: [
            {data: "month", name: 'month'},
            {data: 'year', name: 'year'},
            {data: 'is_lock', name: 'is_lock'},
            {data: 'edit', name: 'edit', orderable: false, searchable: false},
            {data: 'delete', name: 'delete', orderable: false, searchable: false}

        ],
        order: [[ 2, "desc" ]]
    });

    $('#createNewMonth').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-Month");
        $('#month_id').val('');

        $('#inputMonthForm').trigger("reset");
        $('#month').trigger('change');
        $('#year').trigger('change');
        $('#is_lock').trigger('change');
        $('#modelHeading').html("Create New Month");
        $('#monthModal').modal('show');
    });
    $('body').unbind().on('click', '.editMonth', function () {
      var manager_id = $(this).data('id');

      $('#json_message_modal').html('');
      $.get("{{ url('input/inputMonth') }}" +'/' + manager_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Month");
          $('#saveBtn').val("edit-Month");
          $('#monthModal').modal('show');
          $('#month_id').val(data.id);
          $('#month').val(data.month);
          $('#month').trigger('change');
          $('#year').val(data.year);
          $('#year').trigger('change');
          $('#is_lock').val(data.is_lock);
          $('#is_lock').trigger('change');
         
      })
   });
    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#inputMonthForm').serialize(),
          url: "{{ route('inputMonth.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#managerForm').trigger("reset");
              $('#monthModal').modal('hide');
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
    
    $('body').on('click', '.deleteMonth', function () {
     
        var month_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('inputMonth.store') }}"+'/'+month_id,
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
@stop
