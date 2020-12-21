@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">HR Monthly Reports</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
			<form id= "formPosting" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('posting.store')}}" enctype="multipart/form-data">
        	@csrf
	            <div class="form-body">  
			        <h3 class="box-title">Create Monthly Report</h3>
			        <hr class="m-t-0 m-b-40">
			        <div class="row">
	                    <div class="col-md-9">
	                        <div class="form-group row">
	                            <label class="control-label text-right col-md-3">Report Month</label>
	                            <div class="col-md-9">
	                                 <input type="text" name="effective_date" value="{{ old('effective_date') }}" class="form-control date_input" data-validation="required" readonly >
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


<script>
	
</script>

@stop