<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Promotion</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formPromotion" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Employee Promotion</h3>
            <hr class="m-t-0 m-b-40">
            <input type="hidden" name="promotion_id" id="promotion_id"/>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Designation</label>
                            <select id="hr_designation_id" name="hr_designation_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($designations as $designation)
                                <option value="{{$designation->id}}" {{(old("hr_designation_id")==$designation->id? "selected" : "")}}>{{$designation->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Effective Date<span class="text_requried">*</span></label>

                            <input type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" data-validation="required" readonly>

                            <br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Salary</label>

                            <select id="hr_salary_id" name="hr_salary_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($salaries as $salary)
                                <option value="{{$salary->id}}" {{(old("hr_salary_id")==$salary->id? "selected" : "")}}>{{$salary->total_salary}}</option>
                                @endforeach

                            </select>

                            @can('hr edit record')
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#salModal"><i class="fas fa-plus"></i>
                            </button>
                            @endcan

                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Grade</label>
                            <select name="hr_grade_id" id="hr_grade_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($hrGrades as $hrGrade)
                                <option value="{{$hrGrade->id}}" {{(old("hr_grade_id")==$hrGrade->id? "selected" : "")}}>{{$hrGrade->name}}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </div>
            </div><!--/End Row-->

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">HOD</label>
                            <select id="hr_manager_id" name="hr_manager_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($managers as $manager)
                                <option value="{{$manager->id}}" {{(old("hr_manager_id")==$manager->id? "selected" : "")}}>{{$manager->first_name. ' '.$manager->last_name .' - '}}{{$manager->employeeCurrentDesignation->name??''}}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Department</label>
                            <select id="hr_department_id" name="hr_department_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($departments as $department)
                                <option value="{{$department->id}}" {{(old("hr_department_id")==$department->id? "selected" : "")}}>{{$department->name}}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Category</label>
                            <select name="hr_category_id" id="hr_category_id" class="form-control selectTwo">
                                <option value=""></option>
                                @foreach($hrCategories as $hrCategory)
                                <option value="{{$hrCategory->id}}" {{(old("hr_category_id")==$hrCategory->id? "selected" : "")}}>{{$hrCategory->name}}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                </div>
            </div><!--/End Row-->

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Remarks<span class="text_requried">*</span></label>
                            <input type="text" name="remarks" id="forward_slash" value="{{ old('remarks') }}" class="form-control exempted" data-validation="required length" data-validation-length="max190" placeholder="Enter Remarks if any">


                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <!--/empty-->
                </div>
                <div class="col-md-2">
                    <div class="form-group row">
                        <center>
                            <img src="{{asset('Massets/images/document.png')}}" class="img-round picture-container picture-src" id="wizardPicturePreview" title="" width="50">

                            </input>
                            <input type="file" name="document" id="view" data-validation="required" class="" hidden>


                            <h6 id="h6" class="card-title m-t-10">Click On Image to Add Pdf Document<span class="text_requried">*</span></h6>

                        </center>
                    </div>
                </div>
            </div><!--/End Row-->
            <!--/row-->
            <div class="row">
                <div class="col-md-8 pdfView">
                    <iframe id="pdf" src="" type="application/pdf" height="200" width="100%" />
                </div>
            </div>  
              
               
            </div> <!--/End Form Boday-->

            <hr>
            @can('hr edit promotion')
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save Promotion</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
    </form>
    @include('hr.appointment.salModal')
    <br>
        <table class="table table-bordered data-table" width=100%>
            <thead>
            <tr>
                <th>Description / Remarks</th>
				<th>Effective Date</th>
				<th>Letter / Order</th>
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
    $('#formPromotion').hide();   
    $("#pdf").hide();

        $("#view").change(function() {
            var fileName = this.files[0].name;
            var fileType = this.files[0].type;
            var fileSize = this.files[0].size;
            //var fileType = fileName.split('.').pop();

            //Restrict File Size Less Than 2MB
            if (fileSize > 2048000) {
                alert('File Size is bigger than 2MB');
                $(this).val('');
            } else {
                //Restrict File Type
                if (fileType == 'application/pdf') {
                    readURL(this); // for Default Image

                    document.getElementById("pdf").src = "{{asset('Massets/images/document.png')}}";
                    $("#pdf").show();
                } else {
                    alert('Only PDF File is Allowed');
                    $(this).val('');
                }
            }

        });

        function readURL(input) {
            var fileName = input.files[0].name;
            var fileType = input.files[0].type;
            //var fileType = fileName.split('.').pop();

            if (fileType != 'application/pdf') {
                //Read URL if image
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow').attr('width', '100%');
                    }
                    reader.readAsDataURL(input.files[0]);
                }

            } else {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('iframe').attr('src', e.target.result.concat('#toolbar=0&navpanes=0&scrollbar=0'));
                    }
                    reader.readAsDataURL(input.files[0]);
                }
                document.getElementById("wizardPicturePreview").src = "{{asset('Massets/images/document.png')}}";
                document.getElementById("h6").innerHTML = "PDF File is Attached";
                $('#wizardPicturePreview').attr('width', '50');
            }
        }

        $("#wizardPicturePreview").click(function() {
            $("input[id='view']").click();
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
        ajax:{url:"{{ route('promotion.create') }}", data: {
            hrEmployeeId: $("#hr_employee_id").val()
        }},
        columns: [
            {data: "remarks", name: 'remarks'},
            {data: "effective_date", name: 'effective_date'},
            {data: "document", name: 'document'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        "drawCallback": function(settings) {
            if(this.api().rows().data().length>0){
					$("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
            }

        },
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#pdf').attr('src','').hide();
            $('#h6').html("Click On Image to Add Pdf Document<span class='text_requried'>*</span>");
            $('#formPromotion').toggle();
            $('#formPromotion').trigger("reset");
            $('#promotion_id').val('');
            $('#view').attr('data-validation','required');
            $('#hr_designation_id').trigger('change');
            $('#hr_salary_id').trigger('change');
            $('#hr_grade_id').trigger('change');
            $('#hr_manager_id').trigger('change');
            $('#hr_department_id').trigger('change');
            $('#hr_category_id').trigger('change');
        
    });

    $('body').unbind().on('click', '.editPromotion', function () {
      var promotion_id = $(this).data('id');
        
      $.get("{{ url('hrms/promotion') }}" +'/' + promotion_id +'/edit', function (data) {
      //  console.log(JSON.stringify(data));
          $('#formPromotion').show(); 
          $('#formHeading').html("Edit Promotion");
          $('#promotion_id').val(data.id);
          $('#hr_designation_id').val(data.hr_designation?.hr_designation_id).trigger('change');
          $('#effective_date').val(data.effective_date);
          $('#hr_salary_id').val(data.hr_salary?.hr_salary_id).trigger('change');
          $('#hr_grade_id').val(data.hr_grade?.hr_grade_id).trigger('change');
          $('#hr_manager_id').val(data.hr_manager?.hr_manager_id).trigger('change');
          $('#hr_department_id').val(data.hr_department?.hr_department_id).trigger('change');
          $('#hr_category_id').val(data.hr_category?.hr_category_id).trigger('change');
          $('#forward_slash').val(data.remarks);
          if(data.hr_documentation.extension =='pdf'){
            $("#pdf").show();
            $('#pdf').attr('src',data.hr_documentation.full_path);
          }else{
            $("#pdf").hide();
            $('#pdf').attr('src','');
          }
          $('#view').removeAttr('data-validation');
      })
   });
    
      $("#formPromotion").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("hr_employee_id", $("#hr_employee_id").val());
        $.ajax({
          data: formData,
          url: "{{ route('promotion.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{
              
                $('#formPromotion').trigger("reset");
                $('#formPromotion').toggle();
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
    
    $('body').on('click', '.deletePromotion', function () {
     
        var promotion_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('promotion.store') }}"+'/'+promotion_id,
            success: function (data) {
                table.draw();
               
                if($('#formPromotion:visible').length != 0)
                    {
                        $('#formPromotion').toggle();
                    }
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