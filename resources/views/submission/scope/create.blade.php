
    <div class="card-body">
        <form id= "scope" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('submissionScope.update',session('submission_id'))}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Submission Scope</h3>  
                <hr class="m-t-0 m-b-40"> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Scope of Work</label>
                                    <textarea  rows=3 cols=5 id="scope_of_work" name="scope_of_work" class="form-control " >{{ old('scope_of_work',  $data->scope_of_work??'') }}</textarea>       
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Scope of Services</label>
                                    <textarea  rows=3 cols=5 id="scope_of_services" name="scope_of_services" class="form-control " >{{ old('scope_of_services',  $data->scope_of_services??'') }}</textarea>       
                                
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

               
            </div> <!--/End Form Boday-->
            <hr>
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
        </form>
	</div> <!-- end card body --> 
    
  

<script>
$(document).ready(function(){
    //submit function
     $("#scope").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url);
    });

});

</script>



