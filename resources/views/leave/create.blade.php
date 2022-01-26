@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Apply Leave</h3>
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
		                @csrf
		                    <div class="form-body">
		                            
		                        <h3 class="box-title">Apply Leave</h3>
		                        
		                        <hr class="m-t-0 m-b-40">
                     
		                        <div class="row">
		                            <div class="col-md-5">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Employee Name<span class="text_requried">*</span></label>
		                                        
	                                           	<select  name="hr_employee_id"  id= "hr_employee_id" class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($employees as $employee)
													                         <option value="{{$employee->id}}" {{(old("hr_employee_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                                                    @endforeach     
                                              </select>
												
		                                    </div>
		                                </div>
		                            </div>
		                           
		                            <div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Leave From</label>
                                            <input type="text" id="from" name="from" value="{{ old('from') }}" class="form-control date_input1" data-validation="required" readonly>

                                           
                                              <div class="form-check form-switch float-right divHalfFrom" >
                                                <input class="form-check-input" type="checkbox" name="halfFrom" id="halfFrom">
                                                <label class="form-check-label" for="halfFrom">Half Day</label>
                                              </div>

                                              <i class="fas fa-trash-alt text_requried"></i>
                                              
                                              <br>
                                              
                                              <div class="form-check form-switch float-right" id="radioFromHalf">

                                                <div class="form-check form-switch form-check-inline" >
                                                  <input class="form-check-input" type="radio" name="from_day_half_leave" id="1stHalf" value="From Day 1st Half Leave">
                                                  <label class="form-check-label" for="1stHalf">1st Half</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="from_day_half_leave" id="2ndHalf" value="From Day 2nd Half Leave">
                                                  <label class="form-check-label" for="2ndHalf">2nd Half</label>
                                                </div>
                                              </div>
		                                    </div>
		                                </div>
		                            </div>
                                 <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Leave To</label>
                                            <input type="text" id="to" name="to" value="{{ old('to') }}" class="form-control date_input1" data-validation="required" readonly>

                                           
                                              <div class="form-check form-switch float-right divHalfTo" >
                                                <input class="form-check-input" type="checkbox" name="halfTo" id="halfTo">
                                                <label class="form-check-label" for="halfTo">Half Day</label>
                                              </div>

                                              <i class="fas fa-trash-alt text_requried"></i>
                                              
                                              <br>
                                              
                                              <div class="form-check form-switch float-right" id="radioToHalf">

                                                <div class="form-check form-switch form-check-inline" >
                                                  <input class="form-check-input" type="radio" name="to_day_half_leave" id="1stHalfTo" value="To Day 1st Half Leave">
                                                  <label class="form-check-label" for="1stHalfTo">1st Half</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="to_day_half_leave" id="2ndHalfTo" value="To Day 2nd Half Leave">
                                                  <label class="form-check-label" for="2ndHalfTo">2nd Half</label>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                  <div class="form-group row">
                                      <div class="col-md-12">
                                          <label class="control-label text-right">Days</label>
                                          <input type="text" id="days" name="days" value="{{ old('days') }}" class="form-control" readonly>
                      
                                      </div>
                                  </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Leave Type<span class="text_requried">*</span></label> 
                                              <select  name="le_type_id"  id="le_type_id"class="form-control selectTwo" data-validation="required" data-placeholder="First Select Employee">  
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
                                            <input type="text" name="reason" id="reason" value="{{old('reason')}}" class="form-control" data-validation="required">  
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
                                                   <option value="{{$employee->id}}" {{(old("perform_duty_id")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                                                    @endforeach     
                                              </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Contact Number</label>
                                            <input type="text" name="contact_no" id="contact_no" value="{{old('contact_no')}}" class="form-control">  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="control-label text-right">Address</label>
                                            <input type="text" name="address" id="address" value="{{old('address')}}" class="form-control">  
                                             
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

	$('.divHalfFrom, #radioFromHalf, .divHalfTo, #radioToHalf').hide();
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
        $('.divHalfFrom, #radioFromHalf, .divHalfTo, #radioToHalf').hide();
        $('input[name="halfTo"]').prop('checked', false);
        $('input[name="halfFrom"]').prop('checked', false);
        $(this).siblings('span').text("");
        }
    });
    // DatePicker
   $(".date_input1").datepicker({
     onSelect: function(value, ui) {
        $(this).siblings('i').show();
      
        $(this).siblings('#radioFromHalf').hide();
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

   $("#halfFrom").change(function() {  
            if(this.checked) {
            var days = $('#days').val();
                  days = parseFloat(days) - 0.5;
              $('#days').val(days);
              $('#radioFromHalf').show();
            }else{
              var days = $('#days').val();
                  days = parseFloat(days) + 0.5;
              $('#days').val(days);
              $('#radioFromHalf').hide();
              $('input[name="from_day_half_leave"]').prop('checked', false);
            }
    });

   $("#halfTo").change(function() {  
            if(this.checked) {
            var days = $('#days').val();
                  days = parseFloat(days) - 0.5;
              $('#days').val(days);
              $('#radioToHalf').show();
            }else{
              var days = $('#days').val();
                  days = parseFloat(days) + 0.5;
              $('#days').val(days);
              $('#radioToHalf').hide();
              $('input[name="to_day_half_leave"]').prop('checked', false);
            }
    });


	$('#formLeave').on('submit', function(event){
	 	//preventDefault work through formFunctions;
		url="{{route('leave.store')}}";
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