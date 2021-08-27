
<div style="margin-top:10px; margin-right: 10px;">
    <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Emploees</button>
</div>
     
<div class="card-body">
    <form id= "formEditUserLogin" method="post" action="{{route('userLogin.store')}}"  class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
    @csrf
        <div class="form-body">
                
            <h3 class="box-title">User Login Detail</h3>
            
            <hr class="m-t-0 m-b-40">

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Email<span class="text_requried">*</span></label><br>

                           	<input type="email"  name="email" value="{{ old('email', $data->user->email??'') }}"  class="form-control" data-validation="required" placeholder="Enter Email Address Name">
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Permissions<span class="text_requried">*</span></label>
                            <select  name="permission"  class="form-control selectTwo" data-validation="required">
                                <option value=""></option>
                                @foreach($permissions as $permission)
                                <option value="{{$permission->id}}" {{(old("permission")==$permission->id? "selected" : "")}}>{{$permission->name}}</option>
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
                
            </div><!--/End Row-->

        </div> <!--/End Form Boday-->

        <hr>

        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" id="submitEditUserLogin"  class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12 table-container">
       
        
        </div>
    </div>
</div> <!-- end card body -->    

<script>
$(document).ready(function (){
    refreshTable("{{route('userLogin.table')}}");
     //submit function
      $("#formEditUserLogin").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url);
      refreshTable("{{route('userLogin.table')}}");
    });


});

</script>
