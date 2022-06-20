<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Location</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formLocation" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Location</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
             <input type="hidden" name="as_location_id" id="as_location_id"/>
                 <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Asset Location<span class="text_requried">*</span></label>
                                            
                                              <select  name="asset_location" id="asset_location" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    <option value="1">Placed at</option>
                                                     <option value="2">Handover to</option>  
                                                </select>
                        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 blank">
                                </div>
                                <div class="col-md-3 hide office">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Office<span class="text_requried">*</span></label>
                                            
                                              <select  name="office_id" id="office_id" class="form-control selectTwo">
                                                    <option value=""></option>
                                                    @foreach($offices as $office)
                          <option value="{{$office->id}}" {{(old("office_id")==$office->id? "selected" : "")}}>{{$office->name}}</option>
                                                    @endforeach     
                                                </select>
                        
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 hide employee">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Employee<span class="text_requried">*</span></label>
                                            
                                              <select  name="hr_employee_id" id="hr_employee_id" class="form-control selectTwo">
                                                    <option value=""></option>
                                                    @foreach($employees as $employee)
                          <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employeeDesignation->last()->name??''}} - {{$employee->employee_no}}</option>
                                                    @endforeach     
                                                </select>
                        
                                        </div>
                                    </div>
                             </div>
                   <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                                            
                                            <input type="text" id="date" name="date" value="{{ old('date') }}" class="form-control date_input" data-validation="required" readonly>
                        
                        <br>
                                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                                        </div>
                                  </div>
                                </div>
              </div>
                                               
        </div>
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
          <th>Location / Allocation</th>
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

       $('#formLocation').hide();       

      $('#asset_location').change(function(){
         var option = $(this).val();
        if(option==1){
          $(".blank").addClass('hide');
          $(".office").removeClass('hide');
          $("#office_id").attr("data-validation", "required");
          $("#hr_employee_id").removeAttr("data-validation");
          $("#hr_employee_id").val('');
          $(".employee").addClass('hide');
          $('#hr_employee_id').val('').select2('val', 'All');

        } else if(option==2){
          $(".blank").addClass('hide');
          $(".employee").removeClass('hide');
          $("#hr_employee_id").attr("data-validation", "required");
          $("#office_id").removeAttr("data-validation");
          $("#office_id").val('');
          $(".office").addClass('hide');
        } else{
          $(".blank").removeClass('hide');
          $(".employee").addClass('hide');
          $(".office").addClass('hide');
          $("#office_id").removeAttr("data-validation");
          $("#hr_employee_id").removeAttr("data-validation");
        }
      });

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
        ajax: "{{ route('asLocation.create') }}",
        columns: [
            
            {data: "location", name: 'location'},
            {data: "date", name: 'date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#formLocation').toggle();
            $('#formLocation').trigger("reset");
             $('.selectTwo').val('').select2('val', 'All');
            $('#as_location_id').val('');
          
    });

    $('body').unbind().on('click', '.editLocation', function () {


      var as_location_id = $(this).data('id');

      $.get("{{ url('hrms/asLocation') }}" +'/' + as_location_id +'/edit', function (data) {
          $('#formLocation').show(); 
          $('#formHeading').html("Edit Location");
           $('#as_location_id').val(data.id);
          if(data.office_id){ 
            $('#asset_location').val('1');
            $('#office_id').val(data.office_id);
            $('#office_id').trigger('change');
            $('#asset_location').trigger('change');
            $('#date').val(data.date);
          }else if(data.hr_employee_id){
            $('#asset_location').val('2');
            $('#hr_employee_id').val(data.hr_employee_id);
            $('#hr_employee_id').trigger('change');
            $('#asset_location').trigger('change');
            $('#date').val(data.date);
          }

      })
   });
    
      $("#formLocation").submit(function(e) {
        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          data: formData,
          url: "{{ route('asLocation.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{

                $('#formLocation').trigger("reset");
                $('#formLocation').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');  

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
    
    $('body').on('click', '.deleteLocation', function () {
     
        var as_location_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('asLocation.store') }}"+'/'+as_location_id,
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