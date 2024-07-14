<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Exit</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formExit" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Employee Exit</h3>
            <hr class="m-t-0 m-b-40">
              <input type="hidden" name="exit_id" id="exit_id"/>
              
              <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Current Status<span class="text_requried">*</span></label>
                            <select id="hr_status_id" name="hr_status_id" class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($hrStatuses as $hrStatus)
                                <option value="{{$hrStatus->id}}" {{(old("hr_status_id", $employee->hr_status_id)==$hrStatus->id? "selected" : "")}}>{{$hrStatus->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>

                            <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" readonly placeholder="Enter Document Detail">
                            <br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Reason</label>

                            <input type="text" name="reason" id="reason" value="{{ old('reason') }}" class="form-control" data-validation="length" data-validation-length="max190">

                        </div>
                    </div>
                </div>

            </div>
            <!--/End Row-->

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Remarks</span></label>

                            <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}" class="form-control" data-validation="length" data-validation-length="max190">


                        </div>
                    </div>
                </div>

            </div>
            <!--/End Row-->
               
            </div> <!--/End Form Boday-->

            <hr>
            
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Exit</button>        
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
                <th>Employee Status</th>
				<th>Effective Date</th>
				<th>Reason</th>
                <th>Edit</th>
                <th>Delete</th> 
                <!-- <th colspan="2" class="text-center"style="width:10%"> Actions </th>  -->
            </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>


   

        
<script type="text/javascript">
$(document).ready(function(){
    // formFunctions();
    $('#formExit').hide();   
    
       
}); 
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
        ajax:{url:"{{ route('exit.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "hr_status_id", name: 'hr_status_id'},
            {data: "effective_date", name: 'effective_date'},
            {data: "reason", name: 'reason'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
          
            $('#formExit').toggle();
            $('#formExit').trigger("reset");
            $('#exit_id').val('');
            $('#hr_status_id').trigger('change');
    });

    $('body').unbind().on('click', '.editExit', function () {


      var exit_id = $(this).data('id');
        
      $.get("{{ url('hrms/exit') }}" +'/' + exit_id +'/edit', function (data) {
          $('#formExit').show(); 
          $('#formHeading').html("Edit Contact");
          $('#exit_id').val(data.id);
          $('#hr_status_id').val(data.hr_status_id).trigger('change');
          $('#effective_date').val(data.effective_date);
          $('#reasons').val(data.reasons);
          $('#remarks').val(data.remarks);
      })
   });
    
      $("#formExit").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('exit.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{

                $('#formExit').trigger("reset");
                $('#formExit').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');  

                table.draw();
              }
        
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
    
    $('body').on('click', '.deleteExit', function () {
     
        var exit_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('exit.store') }}"+'/'+exit_id,
            success: function (data) {
                table.draw();
                $('#formExit').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.mesage+'</strong></div>');
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