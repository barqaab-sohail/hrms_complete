
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "formEditExit" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('exit.update',$data->id??'')}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Exit Detail</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Status<span class="text_requried">*</span></label>
                                 <select  id="hr_status_id"   name="hr_status_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($hrStatuses as $hrStatus)
                                    <option value="{{$hrStatus->id}}" {{(old("hr_status_id",$data->hr_status_id??'')==$hrStatus->id? "selected" : "")}}>{{$hrStatus->name}}</option>
                                    @endforeach 
                                </select>
                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                 <label class="control-label text-right">Effective Date</span></label>
                        
                                  <input type="text" name="effective_date"  value="{{ old('effective_date',$data->effective_date??'') }}" class="form-control date_input" readonly placeholder="Enter Document Detail">
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
                                
                                <input type="text" name="reason" value="{{ old('reason',$data->reason??'') }}" class="form-control" data-validation="length"  data-validation-length="max190" >

                            </div>
                        </div>
                    </div>
                    
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Remarks</span></label>
                                
                               <input type="text" name="remarks" value="{{ old('remarks',$data->remarks??'') }}" class="form-control" data-validation="length"  data-validation-length="max190" >
                                
                               
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

        <div class="row">
             <div class="col-md-12 table-container">
           
            
            </div>
        </div>
	</div> <!-- end card body -->    

<script>
$(document).ready(function(){
    
  refreshTable("{{route('exit.table')}}",500);

    $('#formEditExit').on('submit', function(event){  
      var url = $(this).attr('action');
     $('.fa-spinner').show();     
     event.preventDefault();
      submitForm(this, url);
      refreshTable("{{route('exit.table')}}",5000);
        
    }); //end submit
  

       
});
</script>

