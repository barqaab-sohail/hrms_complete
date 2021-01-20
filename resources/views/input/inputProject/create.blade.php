@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Add Month and Projects</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>	
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-12">
		            <div style="margin-top:10px; margin-right: 10px;">                   
		            </div>
		            <div class="card-body">
		                <form id= "monthlyInputForm"  action="{{route('inputProject.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                   
	                            <div class="row">
	                            	<div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Month & Year<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="hr_input_month_id" id="hr_input_month_id" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($monthYears as $monthYear)
													<option value="{{$monthYear->id}}" {{(old("hr_input_month_id")==$monthYear? "selected" : "")}}>{{$monthYear->month}}-{{$monthYear->year}}</option>
                                                    @endforeach     
                                                </select>
		                                    </div>
		                                </div>
		                            </div> 
		                            <div class="col-md-8">
	                                    <div class="form-group row"> 
	                                        <div class="col-md-12">
	                                        <label class="control-label text-right">Projects</label>
	                                            <select  name="pr_detail_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($projects as $project)
													<option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->project_no}} - {{$project->name}}</option>
                                                    @endforeach  
                                                </select>
	                                        </div>
	                                    </div>
	                            	</div>   
		                            <div class="col-md-2">
		                                <div class="form-actions">
	                                		<div class="col-md-12"> <br>
                                            	<button type="submit" class="btn btn-success btn-prevent-multiple-submits">Save</button>
	                                		</div>
		                        		</div>
		                            </div>
		                        </div>
		                </div>
		                <hr> 
		            	</form>
		            		<table id="inputTable" class="table table-bordered">
      								<h2 align="center" id="heading"></h2>
      						        <tr>
      						            <th>Month & Year</th>
      						            <th>Project</th>
      						            <th width="250px">Action</th>
      						        </tr>
      					    </table>



		        		</div>       
		        	</div>
		        </div>
            </div>
        </div>
    </div>
</div>


<script>
 $(document).ready(function() {


 	selectTwo();
  	
    //submit function
     $("#monthlyInputForm").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url,1);
       
    });

    $('#hr_input_month_id').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('input/inputProject')}}"+"/"+cid,

             //url:"http://localhost/hrms4/public/country/"+cid, **//Please see the note at the end of the post**
             success:function(res)
             {       
                   $.each(res['pr_detail'],function(key,val){ 
                      var editUrl='{{route("input.edit",":id")}}';
                          editUrl= editUrl.replace(':id', res['id'][key]['id']);

                      $("#inputTable tbody").append('<tr><td>'+ (key+1) +
                        '</td><td>'+val['name']+
                        '</td><td><form id=deleteForm'+key+' action='+"{{route('input.destroy','')}}"+
                        '/'+res['id'][key]['id']+' method="POST"><a class="btn btn-primary"href='
                          +editUrl+'>Edit</a>@csrf @method("DELETE")<button type="submit" class="btn btn-danger">Delete</button></form></td></tr>');  
                            //console.log(val['first_name']);
                    }); 
                 console.log(res);
             }

          });//end ajax
        }
    }); 


    
});

</script>

@stop