@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Add Month</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>	
	</ol>
@stop
@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card card-outline-info">
        <div class="row">
		      <div class="col-lg-12">
            <div class="card-body">
              <a class="btn btn-success" href="javascript:void(0)" id="createNewMonth">Add New Month</a>
              <br>
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                      <th class="text-center">Month</th>
                      <th class="text-center">Year</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
		      </div>
        </div>
    </div>
  </div>
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
                <form id="monthForm" name="monthForm" class="form-horizontal">
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
            {data: 'month', name: 'month'},
            {data: 'year', name: 'year'},
            {data: 'is_lock', name: 'is_lock'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            
        ],
        columnDefs: [
          {
            "targets": [0,1,2,3], // your case first column
            "className": "text-center",
          }
        ],
    });
    $('#createNewMonth').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-month");
        $('#month_id').val('');
        $('#monthForm').trigger("reset");
        $('#modelHeading').html("Create New Month");
        $('#ajaxModel').modal('show');
    });
    $('body').on('click', '.editMonth', function () {
      var month_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('input/inputMonth') }}" +'/' + month_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Month");
          $('#saveBtn').val("edit-month");
          $('#ajaxModel').modal('show');
          $('#month_id').val(data.id);
          $('#month').val(data.month);
          $('#year').val(data.year);
          $('#is_lock').val(data.is_lock);
      })
   });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
    
        $.ajax({
          data: $('#monthForm').serialize(),
          url: "{{ route('inputMonth.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#monthForm').trigger("reset");
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
    
    $('body').on('click', '.deleteMonth', function () {
     
        var month_id = $(this).data("id");
        confirm("Are You sure want to delete !");
      
        $.ajax({
            type: "DELETE",
            url: "{{ route('inputMonth.store') }}"+'/'+month_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                
            }
        });
    });
     
  });
});
</script>

@stop