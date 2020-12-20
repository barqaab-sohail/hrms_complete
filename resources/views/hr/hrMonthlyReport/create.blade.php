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
		                <form action="{{route('hrMonthlyReport.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                    <h3 class="box-title">Create Monthly Report</h3>
		                    <hr class="m-t-0 m-b-40">
	                            <div class="row">
	                                <div class="col-md-9">
	                                    <div class="form-group row">
	                                        <label class="control-label text-right col-md-3">Month and Year</label>
	                                        <div class="col-md-9">
	                                            <input type="text" id="month" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control date_input" data-validation="required" readonly>
	                                        </div>
	                                    </div>
	                            	</div>   
		                            <!--/span-->
		                            <div class="col-md-3">
		                                <div class="form-actions">
		                            		<div class="row">
		                                		<div class="col-md-6">
		                                    		<div class="row">
		                                        		<div class="col-md-offset-3 col-md-9">
		                                            	<button type="submit" class="btn btn-success btn-prevent-multiple-submits">Save</button>
		                                        		</div>
		                                    		</div>
		                                		</div>
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
  	$(function() {
            $('#month').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
            });
    });
});

</script>

@stop