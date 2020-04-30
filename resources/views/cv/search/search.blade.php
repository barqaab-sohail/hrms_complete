@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Search CVs</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title">Search CVs</h4>
		<hr>
		<form id="formCvDetail" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('cv.find')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

				<div class="row Search" >
					<div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Degree<span class="text_requried">*</span></label><br>

		                   		<select  name="degree"  data-validation="required" class="form-control" >
		                            <option value=""></option>
		                            
		                            @foreach($degrees as $degree)
									
									<option value="{{$degree->id}}" {{(old("degree")==$degree->id? "selected" : "")}}>{{$degree->degree_name}}</option>

		                            @endforeach
		                          
		                        </select>
		                        
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Speciality<span class="text_requried">*</span></label><br>

		                   		<select  name="speciality_id"  id=speciality_id data-validation="required" class="form-control" >
		                            <option value=""></option>
		                            
		                            @foreach($specializations as $specialization)
									
									<option value="{{$specialization->id}}" {{(old("speciality_id")==$specialization->id? "selected" : "")}}>{{$specialization->name}}</option>

		                            @endforeach
		                          
		                        </select>
		                        
		                    </div>
		                </div>
		            </div>
		            <!--/span 5-2 -->
		            <div class="col-md-2">
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                    	<label class="control-label">Discipline<span class="text_requried">*</span></label>

		                    	<select  name="discipline_id"   data-validation="required" class="form-control" >
		                            <option value=""></option>
		                            
		                            @foreach($disciplines as $discipline)
									
									<option value="{{$discipline->id}}" {{(old("discipline_id")==$discipline->id? "selected" : "")}}>{{$discipline->name}}</option>

		                            @endforeach
		                          
		                        </select>
		                    
		                        
		                    </div>
		                </div>
		            </div>
		              <!--/span 5-2 -->
		            <div class="col-md-2">
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                    	<label class="control-label">Stage<span class="text_requried">*</span></label>

		                    	<select  name="stage_id"  id=stage_id data-validation="required" class="form-control" >
		                            <option value=""></option>
		                            
		                            @foreach($stages as $stage)
									
									<option value="{{$stage->id}}" {{(old("stage_id")==$stage->id? "selected" : "")}}>{{$stage->name}}</option>

		                            @endforeach
		                          
		                        </select>
		                    
		                        
		                    </div>
		                </div>
		            </div>
		            <!--/span 5-3 -->
		            <div class="col-md-2">
		                <div class="form-group row">
		                    <div class="col-md-8 required">
		                    	<label class="control-label text-right">Experience<span class="text_requried">*</span></label>
		                    
		                        <select data-validation="required" name="year"  class="form-control">

								<option value=""></option>
								@for ($i = 1; $i <= 50; $i++)
								<option value="{{$i}}" {{(old("year")==$i? "selected" : "")}}>{{ $i }}</option>
								@endfor

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
	                            <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Searcdh</button>
	                            
	                        </div>
	                     


	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>
		                            

		<hr>
		@include('cv.search.list')
	</div>
</div>
@push('scripts')

<script>
$(document).ready(function(){
	$('.fa-spinner').hide();
	$('select').select2();

});

</script>

@endpush

@stop