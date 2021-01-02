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
		                                       
	                                           	<select  name="hr_monthly_input_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($monthYears as $monthYear)
													<option value="{{$monthYear->id}}" {{(old("hr_monthly_input_id")==$monthYear? "selected" : "")}}>{{$monthYear->month}}-{{$monthYear->year}}</option>
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

    
});

</script>

@stop