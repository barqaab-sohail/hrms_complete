@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Add Month</h3>
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
		                <form id= "addMonth"  action="{{route('inputMonth.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                   
	                            <div class="row">
	                            	<div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Month<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="month"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($months as $month)
													<option value="{{$month}}" {{(old("month")==$month? "selected" : "")}}>{{$month}}</option>
                                                    @endforeach     
                                                </select>
		                                    </div>
		                                </div>
		                            </div> 
		                            <div class="col-md-2">
	                                    <div class="form-group row"> 
	                                        <div class="col-md-12">
	                                        	<label class="control-label text-right">Year<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="year"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($years as $year)
													<option value="{{$year}}" {{(old("year")==$year? "selected" : "")}}>{{$year}}</option>
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
     $("#addMonth").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url,1);
       
    });

    
});

</script>

@stop