<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Experience</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formExperience" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Employee Experience</h3>
            <hr class="m-t-0 m-b-40">
              <input type="hidden" name="experience_id" id="experience_id"/>
              
              <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Organization<span class="text_requried">*</span></label><br>
                                <input type="text" name="organization" id="organization" value="{{ old('organization') }}" class="form-control exempted" data-validation="required length" data-validation-length="max90" >
 
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Job Title<span class="text_requried">*</span></label>
                                <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" class="form-control exempted" data-validation="required length" data-validation-length="max70" >
                            </div>
                        </div>
                    </div>
                     
                </div><!--/End Row-->
                 
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">From<span class="text_requried">*</span></label>
                                
                                
								<select  name="from"  id="from" class="form-control selectTwo" data-validation="required" >

                                <option value=""></option>
                                @for ($i = (date('Y')-65); $i < (date('Y')+1); $i++)
                                <option value="{{$i}}">{{ $i }}</option>
                                @endfor
                                </select>
								
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">To<span class="text_requried">*</span></label>

                                <select  name="to"  id="to" class="form-control selectTwo" data-validation="required" >
                                    <option value=""></option>
                                    <option value="To Date">To Date</option>
                                    @for ($i = (date('Y')-65); $i < (date('Y')+1); $i++)
                                    <option value="{{$i}}">{{ $i }}</option>
                                    @endfor
                                </select>
                                
                               
                            </div>
                        </div>
                    </div>
           
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Country</label>       
                                <select  name="country_id" id="country_id" class="form-control selectTwo">
                                    <option value=""></option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{(old("country_id")==$country->id? "selected" : "")}}>{{$country->name}}</option>
                                @endforeach     
                                </select> 
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
                <div class="row">
     
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Activities</label>
                                    <textarea  rows=3 cols=5 id="activities" name="activities" class="form-control " >{{ old('activities') }}</textarea>       
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
               
            </div> <!--/End Form Boday-->

            <hr>
            @can('hr edit experience')
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Experience</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
    </form>
   
    <br>
        <table class="table table-bordered data-table" width=100%>
            <thead>
            <tr>
                <th>Organization</th>
                <th>Job Title</th>
                <th>From</th>
                <th>To</th>
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

    $('#formExperience').hide();   
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
        ajax:{url:"{{ route('experience.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "organization", name: 'organization'},
            {data: "job_title", name: 'job_title'},
            {data: "from", name: 'from'},
            {data: "to", name: 'to'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
          
            $('#formExperience').toggle();
            $('#formExperience').trigger("reset");
            $('#experience_id').val('');

            $('#education_id').trigger('change');
            $('#from').trigger('change');
            $('#to').trigger('change');
            $('#country_id').trigger('change');
    });

    $('body').unbind().on('click', '.editExperience', function () {
      var experience_id = $(this).data('id');
        
      $.get("{{ url('hrms/experience') }}" +'/' + experience_id +'/edit', function (data) {

          $('#formExperience').show(); 
          $('#formHeading').html("Edit Experience");
          $('#experience_id').val(data.id);
          $('#organization').val(data.organization);
          $('#job_title').val(data.job_title);
          $('#activities').val(data.activities);
          $('#from').val(data.from).trigger('change');
          $('#to').val(data.to).trigger('change');
          $('#country_id').val(data.country_id).trigger('change');;
         
      })
   });
    
      $("#formExperience").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('experience.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{
              
                $('#formExperience').trigger("reset");
                $('#formExperience').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');  

                table.draw();
                clearMessage();
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
    
    $('body').on('click', '.deleteExperience', function () {
     
        var experience_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('experience.store') }}"+'/'+experience_id,
            success: function (data) {
                table.draw();
                clearMessage();
                $('#formExperience').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
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