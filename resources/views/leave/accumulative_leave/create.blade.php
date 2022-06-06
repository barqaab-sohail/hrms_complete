@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
  <h3 class="text-themecolor"></h3>
@stop
@section('content')
<div class="card">
  <div class="card-body">
    @can('Super Admin')
 <!-- upload accumulative earned leave data
    <div class="container" id='hideDiv'>
        <h3 align="center">Import Excel File</h3>

      <form method="post" enctype="multipart/form-data" action="{{route('leave.import')}}">
      {{ csrf_field() }}
          <div class="form-group">
            <table class="table">
              <tr>
                <td width="40%" align="right"><label>Select File for Upload</label></td>
                  <td width="30">
                  <input type="file" name="select_file" />
                  </td>
                  <td width="30%" align="left">
                  <input type="submit" name="upload" class="btn btn-success" value="Upload">
                  </td>
              </tr>
              <tr>
                  <td width="40%" align="right"></td>
                  <td width="30"><span class="text-muted">.xls, .xslx Files Only</span></td>
                  <td width="30%" align="left"></td>
              </tr>
            </table>
          </div>
      </form>
    </div>


    <hr> -->
    @endcan
    <button type="button" class="btn btn-success float-right"  id ="createAddAccumulativeLeave" data-toggle="modal" >Add Accumulative Leave</button>
    <br>
    <table class="table table-striped data-table">
      <thead>
        <tr>
            <th>Employee Name</th>
            <th>Leave Type</th>
            <th>Accumulative Leaves</th>
            <th>Date</th>
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
                <form id="leaveForm" name="leaveForm" action="{{route('accumulativesLeave.store')}}"class="form-horizontal">
                   <input type="hidden" name="le_accumulative_id" id="le_accumulative_id">
                    <div class="form-group">
                        <label class="control-label text-right">Employee Name<span class="text_requried">*</span></label><br>.
                        <select  name="hr_employee_id"  id="hr_employee_id" class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach ($hrEmployees as $employee)
                              <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}} -  {{$employee->hrDesignation->last()->name??''}}</option>
                              @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Leave Type<span class="text_requried">*</span></label>
                        <select  name="le_type_id"  id="le_type_id" class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach ($leTypes as $leaveType)
                              <option value="{{$leaveType->id}}">{{$leaveType->name}}</option>
                              @endforeach
                        </select>
                                
      
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Accumulatives Leave<span class="text_requried">*</span></label>
                        <input type="text" name="accumulative_total" id="accumulative_total" value="{{old('accumulative_total')}}" class="form-control" data-validation="required">  
                        
                    </div>


                    <div class="form-group">
                        <label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>
                                
                        <input type="text" name="date" id="date" value="{{ old('date') }}" class="form-control date_input" data-validation="required" readonly >

                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
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
  formFunctions();
  //only number value entered
    $('#accumulative_total').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
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
        ajax: "{{ route('accumulativesLeave.create') }}",
        columns: [
            {data: "fullName", name: 'fullName'},
            {data: "leave_type", name: 'leave_type'},
            {data: "accumulative_total", name: 'accumulative_total'},
            {data: 'date', name: 'date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#createAddAccumulativeLeave').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-Accumulative_Leave");
        $('#le_accumulative_id').val('');
        $('#leaveForm').trigger("reset");
        $('#hr_employee_id').val('');
        $('#hr_employee_id').trigger('change');
        $('#le_type_id').val('');
        $('#le_type_id').trigger('change');
        $('#accumulative_total').val('');
        $('#date').val('');

        $('#modelHeading').html("Create Accumulative Leave");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editAccumulativeLeave', function () {
      var le_accumulative_id = $(this).data('id');


      $('#json_message_modal').html('');
      $.get("{{ url('hrms/accumulativesLeave') }}" +'/' + le_accumulative_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Accumulative Leave");
          $('#saveBtn').val("edit-Accumulative-Leave");
          $('#ajaxModel').modal('show');
          $('#le_accumulative_id').val(data.id);
          $('#hr_employee_id').val(data.hr_employee_id);
          $('#hr_employee_id').trigger('change');
          $('#le_type_id').val(data.le_type_id);
          $('#le_type_id').trigger('change');
          $('#accumulative_total').val(data.accumulative_total);
          $('#date').val(data.date);
      })
   });
    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#leaveForm').serialize(),
          url: "{{ route('accumulativesLeave.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#leaveForm').trigger("reset");
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
    
    $('body').on('click', '.deleteAccumulativeLeave', function () {
     
        var le_accumulative_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('accumulativesLeave.store') }}"+'/'+le_accumulative_id,
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