@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
  <!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createNewProject" data-toggle="modal" >Add New Project</button>
  
  <button type="button" class="btn btn-success float-left"  id ="copyProject" data-toggle="modal" >Copy Projects</button>
  <br>
  <table class="table table-striped data-table">
    <thead>
      <tr>
          <th>Month & Year</th>
          <th>Project</th>
          <th>Status</th>
          <th>Edit</th>
          <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
  </table>
</div>

<div class="modal fade" id="inputModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
              <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="inputProjectForm" name="inputProjectForm" class="form-horizontal">
                   <input type="hidden" name="input_project_id" id="input_project_id">
                    <div class="form-group">
                        <label class="control-label text-right">Month & Year<span class="text_requried">*</span></label><br>
                          <select  name="input_month_id"  id="input_month_id" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach ($monthYears as $month)
                              <option value="{{$month->id}}" {{(old("input_month_id")==$month->id? "selected" : "")}}>{{$month->month}} {{$month->year}}</option>
                              @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Project<span class="text_requried">*</span></label><br>
                        <select  name="pr_detail_id" id="pr_detail_id" class="form-control selectTwo" data-validation="required">
                          
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
@include('input.inputProject.copy')

<script type="text/javascript">
$(document).ready(function() {
  $('#input_month_id, #pr_detail_id, #is_lock').select2({       
        width: "100%",
        theme: "classic"
    });

  $('#input_month_id').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('input/inputProject')}}"+"/"+cid,
             success:function(res)
             {       
                  if(res)
                  {
                  
                     $("#pr_detail_id").empty(); 
                     $("#pr_detail_id").append('<option value="">Select Project</option>');
                      $.each(res,function(key,value){
                          $("#pr_detail_id").append('<option value="'+key+'">'+value+'</option>'); 
                      });
                      

                  }
                 
             }

          });//end ajax
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
        ajax: "{{ route('inputProject.create') }}",
        columns: [
            {data: "month_year", name: 'month_year'},
            {data: 'pr_detail_id', name: 'pr_detail_id'},
            {data: 'is_lock', name: 'is_lock'},
            {data: 'edit', name: 'edit', orderable: false, searchable: false},
            {data: 'delete', name: 'delete', orderable: false, searchable: false}

        ],
    
    });

    $('#createNewProject').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("create-Project");
        $('#input_project_id').val('');
        $('#inputProjectForm').trigger("reset");
        $('#input_month_id').trigger('change');
        $('#pr_detail_id').trigger('change');
        $('#is_lock').trigger('change');
        $('#modelHeading').html("Create New Project");
        $('#inputModal').modal('show');
    });
    $('#copyProject').click(function () {
        $('#json_message_modal2').html('');
        $('#copyProjectForm').trigger("reset");
        $('#copyFrom').trigger('change');
        $('#copyTo').trigger('change');
        $('#copyModal').modal('show');
    });
    $('#copyBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#copyProjectForm').serialize(),
          url: "{{ route('copyProject.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#copyProjectForm').trigger("reset");
              $('#copyModal').modal('hide');
              table.draw();
        
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message_modal2').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });

    $('body').unbind().on('click', '.editProject', function () {
      var input_project_id = $(this).data('id');

      $('#json_message_modal').html('');
      $.get("{{ url('input/inputProject') }}" +'/' + input_project_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Project");
          $('#saveBtn').val("edit-Project");
          $('#inputModal').modal('show');
          $('#input_project_id').val(data.id);
          $('#input_month_id').val(data.input_month_id);
          $('#input_month_id').trigger('change');
          $('#pr_detail_id').val(data.pr_detail_id);
          $('#pr_detail_id').trigger('change');
          $('#is_lock').val(data.is_lock);
          $('#is_lock').trigger('change');
         
      })
    });
    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#inputProjectForm').serialize(),
          url: "{{ route('inputProject.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#inputProjectForm').trigger("reset");
              $('#inputModal').modal('hide');
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
    $('body').on('click', '.deleteProject', function () {
     
        var input_project_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('inputProject.store') }}"+'/'+input_project_id,
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
