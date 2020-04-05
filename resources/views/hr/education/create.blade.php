@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Human Resource</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">Add Education</a></li>
		
		
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-2">
					@include('layouts.vButton.hrButton')
				</div>
      	
		        <div class="col-lg-10">
		            <div style="margin-top:10px; margin-right: 10px;">
		                <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
		            </div>
		                 
		            <div class="card-body">
		                <form id= "addEducation" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Add Education</h3>
		                        
		                        <hr class="m-t-0 m-b-40">

		                        <div class="row">
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">First Name<span class="text_requried">*</span></label><br>

		                                       	<input type="text"  name="first_name" value="{{ old('first_name') }}"  class="form-control" data-validation="required" placeholder="Enter First Name">
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Last Name</label>
		                                        
		                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control " data-validation="required" placeholder="Enter Last Name" >
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Father Name</label>
		                                        
		                                        <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-control " data-validation="required" placeholder="Enter Father Name" >
		                                    </div>
		                                </div>
		                            </div>
		                        </div><!--/End Row-->

		                        <div class="row">
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Date of Birth<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control date_input" data-validation="required" readonly>
												
												<br>
		                                        @can('hr_edit_record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">CNIC<span class="text_requried">*</span></label>
		                                        
		                                        <input type="text" name="cnic" id="cnic" pattern="[0-9.-]{15}" title= "13 digit Number without dash" value="{{ old('cnic') }}" class="form-control" data-validation="required" placeholder="Enter CNIC without dash" >
		                                    </div>
		                                </div>
		                            </div>
		                             <!--/span-->
		                            <div class="col-md-4">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">CNIC Expiry<span class="text_requried">*</span></label>
		                                            
		                                        <input type="text" id="cnic_expiry" name="cnic_expiry" value="{{ old('cnic_expiry') }}" class="form-control date_input"  data-validation="required" readonly >
												
												<br>
		                                        @can('hr_edit_record')<i class="fas fa-trash-alt text_requried"></i>@endcan
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
		                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits">Add Education</button>        
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </form>
		        	</div> <!-- end card body -->    
		        </div> <!-- end col-lg-12 -->
		    </div> <!-- end row -->
        </div> <!-- end card card-outline-info -->
    </div> <!-- end col-lg-12 -->
</div> <!-- end row -->



@push('scripts')
<script>
$(document).ready(function() {

	$("form").submit(function (e) {
      e.preventDefault();
	});

	// $.validate({
	// validateHiddenInputs: true,
	// });


	$('#addEducation').on('submit', function(event){
	 	event.preventDefault();
		url="{{route('education.store')}}";
		$('.fa-spinner').show();	
	   	submitFormAjax(this, url);
	}); //end submit
	 

});
</script>



@endpush

@stop