<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Condition</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formCondition" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            <h3 class="box-title" id="formHeading">Condition</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
              <input type="hidden" name="as_condition_id" id="as_condition_id"/>
              <div class="col-md-8">
                <div class="form-group row">
                  <div class="col-md-12">
                    <label class="control-label text-right">Condition<span class="text_requried">*</span></label>         
                    <select  name="as_condition_type_id" id="as_condition_type_id" class="form-control selectTwo" data-validation="required">
                      <option value=""></option>
                      @foreach($asConditionTypes as $asConditionType)
                      <option value="{{$asConditionType->id}}" {{(old("client_id")==$asConditionType->id? "selected" : "")}}>{{$asConditionType->name}}</option>
                      @endforeach     
                    </select>
                  </div>
                </div>
              </div>
                 
              <div class="col-md-3">
                <div class="form-group row">
                  <div class="col-md-12">
                      <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                      
                      <input type="text" id="condition_date" name="condition_date" value="{{ old('condition_date') }}" class="form-control date_input" data-validation="required" readonly>
  
                      <br>
                      @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                  </div>
                </div>
              </div>
            </div> <!-- End Row -->

          </div>  <!-- End form-body -->
         <hr>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                       
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" id="saveBtn" style="font-size:18px"></i>Save</button>
                                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
<br>
<table class="table table-bordered data-table" width=100%>
    <thead>
      <tr>
          <th>Asset Condition</th>
          <th>Date</th>
          <th>Edit</th>
          <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>

   
</div>
        
<script type="text/javascript">
$(document).ready(function(){

       // formFunctions();

       $('#formCondition').hide();       

     

    });//end document ready

      //start function
$(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('asCondition.create') }}",
        columns: [
            
            {data: "as_condition_type_id", name: 'as_condition_type_id'},
            {data: "condition_date", name: 'condition_date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });
    
    $('#hideButton').click(function(){
            $('#formCondition').toggle();
            $('#formCondition').trigger("reset");
            $('.selectTwo').val('').select2('val', 'All');
            
    });

    $('body').unbind().on('click', '.editCondition', function () {
      var as_condition_id = $(this).data('id');

      $.get("{{ url('hrms/asCondition') }}" +'/' + as_condition_id +'/edit', function (data) {
          $('#formCondition').show(); 
        
          $('#formHeading').html("Edit Condition");
          $('#as_condition_type_id').val(data.as_condition_type_id);
          $('#as_condition_type_id').trigger('change');
          $('#condition_date').val(data.condition_date);
          $('#as_condition_id').val(data.id);

      
      })
   });
    
      $("#formCondition").submit(function(e) {
        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          data: formData,
          url: "{{ route('asCondition.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
     
              $('#formCondition').trigger("reset");
              $('.selectTwo').val('').select2('val', 'All');
              $('#formCondition').toggle();
              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');  

              table.draw();
        
          },
          error: function (data) {
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteCondition', function () {
     
        var as_condition_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('asCondition.store') }}"+'/'+as_condition_id,
            success: function (data) {
                table.draw();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
            }
          });
        }
    });
     
  });// end function

      
            
</script>