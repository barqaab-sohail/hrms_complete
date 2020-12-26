@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Create Monthly Report</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>	
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
				<div class="col-lg-1">
				</div>

		        <div class="col-lg-10">
		            <div style="margin-top:10px; margin-right: 10px;">                   
		            </div>
		            <div class="card-body">
		                <form id= "hrMonthlyReport"  action="{{route('hrMonthlyReport.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                    <h3 class="box-title">Create Monthly Report</h3>
		                    <hr class="m-t-0 m-b-40">
	                            <div class="row">
	                            	<div class="col-md-3">
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
		                            <!--/span-->
		                            <div class="col-md-2">
	                                    <div class="form-group row"> 
	                                        <div class="col-md-12">
	                                        <label class="control-label text-right">Year</label>
	                                            <select  name="year"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @for ($i=2020; $i<2022; $i++)
													<option value="{{$i}}">{{$i}}</option>
													@endfor 
                                                </select>
	                                        </div>
	                                    </div>
	                            	</div>   
		                            <!--/span-->
		                            <div class="col-md-2">
	                                    <div class="form-group row">
	                                    	<div class="col-md-12">
	                                        <label class="control-label text-right">Status</label>
	                                        
	                                        	<select  name="is_lock"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    <option value="0">UnLock</option>
													<option value="1">Lock</option>  
                                                </select>
	                                        </div>
	                                    </div>
	                            	</div>   
		                            <!--/span-->
		                            <div class="col-md-3">
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
    @include('hr.hrMonthlyReport.hrMonthlyProject.create')
</div>


<script>
 $(document).ready(function() {


 	selectTwo();
  	$(function() {
            $('#month').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            // onClose: function(dateText, inst) { 
            //     $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            // }
            });
    });

    //submit function
     $("#hrMonthlyReport").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url,1);
       
    });

    
});

</script>

@stop