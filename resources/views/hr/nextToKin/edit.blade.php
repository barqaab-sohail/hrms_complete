
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "formContact" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('nextToKin.update',session('hr_employee_id'))}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Next To Kin</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Name<span class="text_requried">*</span></label><br>

                                <input type="text"  name="name" value="{{ old('name',$data->name??'') }}"  class="form-control" data-validation="required length"  data-validation-length="max190">
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Relation<span class="text_requried">*</span></label>
                                
                                <input type="text" name="relation" value="{{ old('relation',$data->relation??'') }}" class="form-control" data-validation="required length"  data-validation-length="max190">

                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <div class="col-md-12">
                               <label class="control-label text-right">Contact No<span class="text_requried">*</span></label>
                                
                               <input type="text" name="mobile" id="mobile" value="{{ old('mobile',$data->mobile??'') }}" class="form-control" data-validation=" required length"  data-validation-length="max15" placeholder="0345-0000000">

                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Address<span class="text_requried">*</span></label>
                                
                               <input type="text" name="address" value="{{ old('address',$data->address??'') }}" class="form-control" data-validation="required length"  data-validation-length="max190" >
     
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

              
               
            </div> <!--/End Form Boday-->

            <hr>
            @can('hr edit contact')
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </form>

        <div class="row">
             <div class="col-md-12 table-container">
           
            
            </div>
        </div>
	</div> <!-- end card body -->    

<script>
$(document).ready(function(){
  
  // $("form").submit(function (e) {
  //    e.preventDefault();
  // });

    isUserData(window.location.href, "{{URL::to('/hrms/employee/user/data')}}");

      //submit function
      $("#formContact").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
        $('.fa-spinner').show(); 
      submitForm(this, url);
    });


   
});
</script>


