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
            <a class="btn btn-success" href="javascript:void(0)" id="createNewMonth">Add New Month</a>
            <br>
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                    <th>Month & Year</th>
                    <th>Status</th>
                    <th>Action</th>
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

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="monthForm" name="monthForm" class="form-horizontal">
                   <input type="hidden" name="month_id" id="month_id">
                    <div class="form-group">
                        <label class="control-label text-right">Month<span class="text_requried">*</span></label><br>
                          <select  name="month"  id="month" class="form-control" data-validation="required">
                            <option value=""></option>
                              
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Year<span class="text_requried">*</span></label><br>
                          <select  name="year"  id="year" class="form-control" data-validation="required">
                            <option value=""></option>
                              
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">status<span class="text_requried">*</span></label><br>
                          <select  name="is_lock"  id="is_lock" class="form-control" data-validation="required">
                            <option value=""></option>
                              
                            </select>
                    </div>

      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>  
<script type="text/javascript">
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
        ]
    });
    $('#createNewMonth').click(function () {
        $('#saveBtn').val("create-month");
        $('#month_id').val('');
        $('#monthForm').trigger("reset");
        $('#modelHeading').html("Create New MOnth");
        $('#ajaxModel').modal('show');
    });
    $('body').on('click', '.editMonth', function () {
      var month_id = $(this).data('id');
      $.get("{{ route('inputMonth.create') }}" +'/' + inputMonth +'/edit', function (data) {
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
          data: $('#bookMonth').serialize(),
          url: "{{ route('inputMonth.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#bookMonth').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
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
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>

@stop