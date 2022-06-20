@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
  <!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
  <div class="card-body">
    <button type="button" class="btn btn-success float-right"  id ="createRights" data-toggle="modal" >Add Rights</button>
    <br>
    <table class="table table-bordered data-table">
      <thead>
        <tr>
            <th>Employee Name</th>
            <th>Project Name</th>
            <th>Invoice Rights</th>
            <th>Payment Rights</th>
            <th>Progress Rights</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
      </thead>
      <tbody>
          
      </tbody>
    </table>
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
                <form id="projectRightsForm" name="projectRightsForm" action="{{route('projectRights.store')}}"class="form-horizontal">
                   
                   <input type="hidden" name="right_id" id="right_id">
                    <div class="form-group">
                        <label class="control-label text-right">Employee Name<span class="text_requried">*</span></label>
                        <select  id="hr_employee_id"   name="hr_employee_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($employees as $employee)
                            <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{employeeFullName($employee->id)}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Project Name<span class="text_requried">*</span></label>
                        <select  id="pr_detail_id"   name="pr_detail_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($projects as $project)
                            <option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Progress Rights<span class="text_requried">*</span></label>
                        <select  id="progress"   name="progress"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($rights as $right)
                            <option value="{{$right->id}}" {{(old("progress")==$right->id? "selected" : "")}}>{{$right->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Invoice Rights<span class="text_requried">*</span></label>
                        <select  id="invoice"   name="invoice"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($rights as $right)
                            <option value="{{$right->id}}" {{(old("invoice")==$right->id? "selected" : "")}}>{{$right->name}}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="form-group">
                        <label class="control-label text-right">Payment Rights<span class="text_requried">*</span></label>
                        <select  id="payment"   name="payment"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($rights as $right)
                            <option value="{{$right->id}}" {{(old("payment")==$right->id? "selected" : "")}}>{{$right->name}}</option>
                            @endforeach
                        </select>
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

formFunctions();

  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('projectRights.create') }}",
        columns: [
            {data: "hr_employee_id", name: 'hr_employee_id'},
            {data: "pr_detail_id", name: 'pr_detail_id'},
            {data: "invoice", name: 'invoice'},
            {data: "payment", name: 'payment'},
            {data: "progress", name: 'progress'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createRights').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Rights");
        $('#right_id').val('');
        $('#hr_employee_id').val('');
        $('#pr_detail_id').val('');
        $('#progress').val('');
        $('#invoice').val('');
        $('#payment').val('');
        $('#hr_employee_id').trigger('change');
        $('#pr_detail_id').trigger('change');
        $('#progress').trigger('change');
        $('#invoice').trigger('change');
        $('#payment').trigger('change');
        $('#projectRightsForm').trigger("reset");
        $('#modelHeading').html("Create New Right");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editProjectRight', function () {
      
      var right_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectRights') }}" +'/' + right_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Right");
          $('#saveBtn').val("edit-right");
          $('#ajaxModel').modal('show');
          $('#right_id').val(data.id);
          $('#hr_employee_id').val(data.hr_employee_id);
          $('#hr_employee_id').trigger('change');
          $('#pr_detail_id').val(data.pr_detail_id);
          $('#pr_detail_id').trigger('change');
          $('#progress').val(data.progress);
          $('#progress').trigger('change');
          $('#invoice').val(data.invoice);
          $('#invoice').trigger('change');
          $('#payment').val(data.payment);
          $('#payment').trigger('change');
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
          data: $('#projectRightsForm').serialize(),
          url: "{{ route('projectRights.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#projectRightsForm').trigger("reset");
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
    
    $('body').on('click', '.deleteProjectRight', function () {
     
        var right_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectRights.store') }}"+'/'+right_id,
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