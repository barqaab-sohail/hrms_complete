@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Edit Leave</h3>
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
		            <div class="card-body">
		                <form id= "formLeave" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
                    @method('PATCH')
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Edit Leave</h3>
		                        
		                        <hr class="m-t-0 m-b-40">
                     
		                        <div class="row">
		                            <div class="col-md-5">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Employee Name<span class="text_requried">*</span></label>
		                                        
	                                           	<select  name="hr_employee_id"  id= "hr_employee_id" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($employees as $employee)
													                         <option value="{{$employee->id}}" {{(old("hr_employee_id", $data->hr_employee_id)==$employee->id? "selected" : "")}}>{{$employee->full_name}} - {{$employee->designation}}</option>
                                                    @endforeach     
                                              </select>
												
		                                    </div>
		                                </div>
		                            </div>
		                           
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Leave From</label>
                                            <input type="text" id="from" name="from" value="{{ old('from', $data->from) }}" class="form-control date_input1" data-validation="required" readonly>

                                           
                                              <div class="form-check form-switch float-right divHalfFrom" >
                                                <input class="form-check-input" type="checkbox" name="halfFrom" id="halfFrom"  @if($leHalfDayFrom) checked @endif>
                                                <label class="form-check-label" for="halfFrom">Half Day</label>
                                              </div>
                                            
                                              <i class="fas fa-trash-alt text_requried"></i>
                                              
                                              <br>
                                              
                                             
		                                    </div>
		                                </div>
		                            </div>
                                 <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Leave To</label>
                                            <input type="text" id="to" name="to" value="{{ old('to', $data->to) }}" class="form-control date_input1" data-validation="required" readonly>

                                           
                                              <div class="form-check form-switch float-right divHalfTo" >
                                                <input class="form-check-input" type="checkbox" name="halfTo" id="halfTo"  @if($leHalfDayTo) checked @endif>
                                                <label class="form-check-label" for="halfTo">Half Day</label>
                                              </div>

                                              <i class="fas fa-trash-alt text_requried"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                  <div class="form-group row">
                                      <div class="col-md-12">
                                          <label class="control-label text-right">Days</label>
                                          <input type="text" id="days" name="days" value="{{ old('days', $data->days) }}" class="form-control" readonly>
                      
                                      </div>
                                  </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Leave Type<span class="text_requried">*</span></label> 
                                              <select  name="le_type_id"  id="le_type_id"class="form-control selectTwo" data-validation="required" data-placeholder="First Select Employee">  
                                              <option value=""></option>
                                              @foreach($leaveTypes as $leaveType)
                                                  <option value="{{$leaveType->id}}" {{(old("le_type_id",$data->le_type_id??'')==$leaveType->id? "selected" : "")}}>{{$leaveType->name}}</option>
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
                                            <label class="control-label text-right">Reason<span class="text_requried">*</span></label>
                                            <input type="text" name="reason" id="reason" value="{{old('reason', $data->reason)}}" class="form-control" data-validation="required">  
                                        </div>
                                    </div>
                                </div>
                            </div><!--/End Row-->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Perform Duties During Leave</label>
                                            
                                              <select  name="perform_duty_id"  id= "perform_duty_id" class="form-control selectTwo">
                                                  <option value=""></option>
                                                  @foreach($employees as $employee)
                                                   <option value="{{$employee->id}}" {{(old("perform_duty_id",$data->lePerformDuty->hr_employee_id??'')==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                                                    @endforeach     
                                              </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Contact Number</label>
                                            <input type="text" name="contact_no" id="contact_no" value="{{old('contact_no', $data->contact_no??'')}}" class="form-control">  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Address</label>
                                            <input type="text" name="address" id="address" value="{{old('address', $data->address??'')}}" class="form-control">  
                                             
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
		                                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Apply Leave</button> 
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



<script>
$(document).ready(function() {

	 
	//All Basic Form Implementatin i.e validation etc.
	formFunctions();

   $(".date_input1").each(function(){
        if (!$(this).val()!=''){
            $(this).siblings('i').hide();
        }

    });

    //If Click icon than clear date
    $(".date_input1").siblings('i').click(function (){
        if(confirm("Are you sure to clear date")){
        $(this).siblings('input').val("");
        $(this).hide();
        $('.divHalfFrom, .divHalfTo').hide();
        $('input[name="halfTo"]').prop('checked', false);
        $('input[name="halfFrom"]').prop('checked', false);
        $(this).siblings('span').text("");
        }
    });
    // DatePicker
   $(".date_input1").datepicker({
     onSelect: function(value, ui) {
        $(this).siblings('i').show();
          $('input[name="halfTo"]').prop('checked', false);
          $('input[name="halfFrom"]').prop('checked', false);

        var start = new Date($('#from').val());
        var end = new Date($('#to').val());
        if($('#to').val()!='' && $('#from').val()!=''){
        $('.divHalfFrom, .divHalfTo').show();
        var diff = new Date(end - start);
        // get days 
        var days = diff/1000/60/60/24 + 1;
          if (days>-1){
             $('#days').val(days);
          }
        }
    },
    dateFormat: 'D, d-M-yy',
    yearRange: '1935:'+ (new Date().getFullYear()+15),
    changeMonth: true,
    changeYear: true,

    });

   $("#halfFrom, #halfTo").change(function() {  
      if(this.checked) {
        var days = $('#days').val();
        days = parseFloat(days) - 0.5;
        $('#days').val(days);
      }
      else{
       var days = $('#days').val();
        days = parseFloat(days) + 0.5;
        $('#days').val(days);
      }
    });




	$('#formLeave').on('submit', function(event){
	 	//preventDefault work through formFunctions;
		url="{{route('leave.update', $data->id)}}";
		$('.fa-spinner').show();

	  submitForm(this, url);
    
    
    
	}); //end submit

	

	//Add Leave Type

	$('#hr_employee_id').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{ route('leaveType') }}"+'/'+cid,

             success:function(res)
             {       
                  
                  if(res)
                  {
                    $("#le_type_id").empty();
                      $("#le_type_id").append('<option value="">Select Leave Type</option>');
                      $.each(res,function(key,value){
                          $("#le_type_id").append('<option value="'+key+'">'+value+'</option>');
                          
                      });
                  }
             }

          });//end ajax
        }
    });

    

  
	 

});
</script>


@stop