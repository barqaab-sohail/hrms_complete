<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Permission</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formPermission" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">      
            <h3 class="box-title" id="formHeading">Employee Permission</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Email<span class="text_requried">*</span></label><br>
                            <input type="email" name="email" value="{{ old('email', $data->user->email??'') }}" class="form-control" data-validation="required" placeholder="Enter Email Address Name">
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Permissions<span class="text_requried">*</span></label>
                            <select name="permission" id=permission class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($permissions as $permission)
                                <option value="{{$permission->name}}" {{(old("permission")==$permission->id? "selected" : "")}}>{{$permission->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-5">
                    <div class="form-group row">
                        <div class="col-md-12">

                        </div>
                    </div>
                </div>
                <!--/span-->

            </div>
            <!--/End Row-->
            </div> <!--/End Form Boday-->
            <hr>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Permission</button>        
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
                <th>Permission Name</th>
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
    $('#formPermission').hide();   
       
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
        ajax:{url:"{{ route('userLogin.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "name", name: 'name'},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#formPermission').toggle();
            $('#permission').val('').trigger('change');
           
    });

       
    $("#formPermission").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('userLogin.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{

                $('#formPermission').trigger("reset");
                $('#formPermission').toggle();
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
    
    $('body').unbind().on('click', '.deletePermission', function () {
           
        var permission_name = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{route('userLogin.store') }}"+'/'+permission_name,
            data: {hrEmployeeId: $("#hr_employee_id").val()},
            success: function (data) {
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }else{
                table.draw();
                $('#formPermission').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                }
                
            },
            error: function (data) {
                
            }
          });
        }
    });
  });// end function

      
            
</script>