@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Project Rights</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Add Project Rights</h4>
		<hr>
		<form id="auditSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('project rights.store')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

				<div class="row Search" >
					<div class="col-md-4">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Employee Name</label><br>
		                   		<select  name="employee" id="employee" class="form-control" data-validation="required">
		                            <option value=""></option>
		                            @foreach($employees as $employee)
									<option value="{{$employee->id}}" {{(old("employee")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employee_no}} -  {{$employee->hrDesignation->last()->name??''}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>     
		            <div class="col-md-5">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Project Name</label><br>
		                   		<select  name="user"  class="form-control" data-validation="required">
		                            <option value=""></option>
		                            @foreach($projects as $project)
									<option value="{{$project->id}}" {{(old("project")==$project->id? "selected" : "")}}>{{$project->name}}</option>
		                            @endforeach
		                        </select>    
		                    </div>
		                </div>
		            </div>     
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Rights</label><br>
		                   		<select  name="user"  class="form-control" data-validation="required">
		                            <option value=""></option>
		                            @foreach($rights as $key => $value)
									<option value="{{$key}}" {{(old("rights")==$key? "selected" : "")}}>{{$value}}</option>
		                            @endforeach
		                        </select>    
		                   		
		                    </div>
		                </div>
		            </div>                  
		        </div>
		    </div>

	        <div class="form-actions">
	            <div class="row">
	                <div class="col-md-6">
	                    <div class="row"> 
	                       <div class="col-md-offset-3 col-md-9">
	                            <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Search</button>
	                            
	                        </div>
	                     
	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>
		                           
		<hr>
		<div class="row">
            <div class="col-md-12 table-container">
           
            
            </div>
        </div>
		
	</div>
</div>


<script>
$(document).ready(function(){
	$('.fa-spinner').hide();
	$('select').select2();
	formFunctions();

	$('#employee').change(function(){
      var cid = $(this).val();
      var url = "{{route('project rights.show',':id')}}";
      url = url.replace(':id', cid);
    
        if(cid){
          $.ajax({
             type:"get",
             url: url,

             success:function(res)
             {       
                  if(res)
                  {
                    $('div.table-container').html(res);

                  }
             }

          });//end ajax
        }
    }); 

	
	


});

</script>



@stop