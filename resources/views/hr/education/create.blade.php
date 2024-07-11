<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Education</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formEducation" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Employee Education</h3>
            <hr class="m-t-0 m-b-40">
              <input type="hidden" name="hr_education_id" id="hr_education_id"/>
              
              <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Name of Degree<span class="text_requried">*</span></label><br>
                                <select id="education_id" name="education_id" data-validation="required" class="form-control selectTwo">
                                   <option></option>
                                    @foreach($degrees as $degree)
                                    <option value="{{$degree->id}}" {{(old("education_id")==$degree->id? "selected" : "")}}>{{$degree->degree_name}}</option>
                                    @endforeach   
                                </select>
                                <br>
                                @can('hr edit record')
                                <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#eduModal"><i class="fas fa-plus"></i>
                                </button>
                                @endcan 
 
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Institute</label>
                                <input type="text" name="institute" id="institute" value="{{ old('institute') }}" class="form-control exempted" data-validation="length" data-validation-length="max190" >
                            </div>
                        </div>
                    </div>
                     
                </div><!--/End Row-->
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Major</label>
                                
                                <input type="text" name="major" id="major" value="{{ old('major') }}" class="form-control" data-validation="length" data-validation-length="max190" placeholder="Enter major subjects with comma saperated" >
                            </div>
                        </div>
                    </div>
                     
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">From</label>
                                
                                
								<select  name="from"  id="from" class="form-control selectTwo" >

                                <option value=""></option>
                                @for ($i = (date('Y')-75); $i < (date('Y')+1); $i++)
                                <option value="{{$i}}">{{ $i }}</option>
                                @endfor
                                </select>
								
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">To<span class="text_requried">*</span></label>

                                <select  name="to"  id="to" class="form-control selectTwo" data-validation="required" >
                                    <option value=""></option>
                                    @for ($i = (date('Y')-75); $i < (date('Y')+1); $i++)
                                    <option value="{{$i}}">{{ $i }}</option>
                                    @endfor
                                </select>
                                
                               
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Total Marks</label>       
                                <input type="number" step="0.01" id="total_marks" name="total_marks" value="{{ old('total_marks') }}" data-validation-allowing="range[1.00;6000.00],float" class="form-control"  >
                            </div>
                        </div>
                    </div>
                      <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Marks Obtain</label>       
                                <input type="number" step="0.01" id="marks_obtain" name="marks_obtain" value="{{ old('marks_obtain') }}"  data-validation-allowing="range[1.00;6000.00],float" class="form-control"  >
                            </div>
                        </div>
                    </div>
                      <!--/span-->
                    <div class="col-md-1">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Grade</label>       
                                <input type="text" id="grade" name="grade" value="{{ old('grade') }}" class="form-control" data-validation="length" data-validation-length="max10" >
                            </div>
                        </div>
                    </div>
                      <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Country<span class="text_requried">*</span></label>       
                                <select  name="country_id" id="country_id" data-validation="required" class="form-control selectTwo">
                                    <option value=""></option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{(old("country_id")==$country->id? "selected" : "")}}>{{$country->name}}</option>
                                @endforeach     
                                </select> 
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
               
            </div> <!--/End Form Boday-->

            <hr>
            @can('hr edit education')
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Education</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
    </form>
    @include('cv.detail.eduModal')
    <br>
        <table class="table table-bordered data-table" width=100%>
            <thead>
            <tr>
                <th>Degree Name</th>
				<th>Institute</th>
				<th>Passing Year</th>
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

    $('#formEducation').hide();   
    
    $('#marks_obtain, #total_marks').on('change',function(){
        if($(this).val() !=''){
        $(this).attr('data-validation','number');
        }else {
            $(this).removeAttr('data-validation');
        }
    });    
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
        ajax:{url:"{{ route('education.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "education_id", name: 'education_id'},
            {data: "institute", name: 'institute'},
            {data: "to", name: 'to'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
          
            $('#formEducation').toggle();
            $('#formEducation').trigger("reset");
            $('#hr_education_id').val('');

            $('#education_id').trigger('change');
            $('#from').trigger('change');
            $('#to').trigger('change');
            $('#country_id').trigger('change');
    });

    $('body').unbind().on('click', '.editEducation', function () {
      var hr_education_id = $(this).data('id');
        
      $.get("{{ url('hrms/education') }}" +'/' + hr_education_id +'/edit', function (data) {

          $('#formEducation').show(); 
          $('#formHeading').html("Edit Education");
          $('#hr_education_id').val(data.id);
          $('#education_id').val(data.education_id).trigger('change');
          $('#institute').val(data.institute);
          $('#major').val(data.major);
          $('#from').val(data.from).trigger('change');
          $('#to').val(data.to).trigger('change');
          $('#total_marks').val(data.total_marks);
          $('#marks_obtain').val(data.marks_obtain);
          $('#grade').val(data.grade);
          $('#country_id').val(data.country_id).trigger('change');;
         
      })
   });
    
      $("#formEducation").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('education.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{
              
                $('#formEducation').trigger("reset");
                $('#formEducation').toggle();
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
    
    $('body').on('click', '.deleteEducation', function () {
     
        var hr_education_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('education.store') }}"+'/'+hr_education_id,
            success: function (data) {
                table.draw();
                $('#formEducation').toggle();
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