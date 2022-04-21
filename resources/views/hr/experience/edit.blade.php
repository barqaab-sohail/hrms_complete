
  
    <div class="card-body">
        <form id= "experience" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('experience.update',$data->id)}}" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Edit Experience</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Organization<span class="text_requried">*</span></label><br>
                                <input type="text" name="organization" id="organization" value="{{ old('organization',$data->organization??'') }}" class="form-control exempted" data-validation="required length" data-validation-length="max90" >
 
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Job Title<span class="text_requried">*</span></label>
                                <input type="text" name="job_title" id="job_title" value="{{ old('job_title',$data->job_title??'') }}" class="form-control exempted" data-validation="required length" data-validation-length="max70" >
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
                                <option value="{{$i}}" {{(old("from",$data->from??'')==$i? "selected" : "")}}>{{ $i }}</option>
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
                                    @for ($i = (date('Y')-65); $i < (date('Y')+1); $i++)
                                    <option value="{{$i}}"{{(old("to",$data->to??'')==$i? "selected" : "")}}>{{ $i }}</option>
                                    @endfor
                                </select>
                                
                               
                            </div>
                        </div>
                    </div>
           
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Country</label>       
                                <select  name="country_id" id="country" class="form-control selectTwo">
                                    <option value=""></option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{(old("country_id",$data->country_id??'')==$country->id? "selected" : "")}}>{{$country->name}}</option>
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
                                    <textarea  rows=3 cols=5 id="activities" name="activities" class="form-control " >{{ old('activities',$data->activities??'')}}</textarea>       
                                
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
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Edit Experience</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </form>
	</div> <!-- end card body --> 
        <div class="row">
             <div class="col-md-12 table-container">
           
            
            </div>
        </div>   
  

<script>
$(document).ready(function(){
    refreshTable("{{route('experience.table')}}");
  
 
    //submit function
     $("#experience").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url);
       refreshTable("{{route('experience.table')}}",1000);
    });



    $('.fa-trash-alt').hide();
    // $('#from, #to').datepicker( {
    // changeMonth: true,
    // changeYear: true,
    // showButtonPanel: true,
    // dateFormat: 'MM yy',
    // onClose: function(dateText, inst) { 
    //     $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    //     $(this).siblings('i').show();
    // }
    // });

    // $(".fa-trash-alt").click(function (){
    //     if(confirm("Are you sure to clear date")){
    //     $(this).siblings('input').val("");
    //     $(this).hide();
    //     $(this).siblings('span').text("");
    //     }
    // });
});

</script>



