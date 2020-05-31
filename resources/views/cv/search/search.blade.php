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
		<form id="cvSearch" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('cv.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

				<div class="row Search" >
					<div class="col-md-3">
		            	<!--/span 5-1 -->
		                <div class="form-group row">
		                    <div class="col-md-12 required">
		                   		<label class="control-label text-right">Degree</label><br>

		                   		<select  name="degree"  class="form-control" >
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
		                   		<label class="control-label text-right">Speciality</label><br>

		                   		<select  name="speciality_id"  id=speciality_id  class="form-control" >
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
		                    	<label class="control-label">Discipline</label>

		                    	<select  name="discipline_id" class="form-control" >
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
		                    	<label class="control-label">Stage</label>

		                    	<select  name="stage_id"  id=stage_id class="form-control" >
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
		                    	<label class="control-label text-right">Experience</label>
		                    
		                        <select name="year"  class="form-control">

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

	//submit function
      $("#cvSearch").submit(function(e) { 
      e.preventDefault();
      $('.fa-spinner').show();
      var url = $(this).attr('action');
      
	      //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
	        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
	            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

	            if (token) {
	                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
	            }
	        });
	         var data = new FormData(this)
	       // ajax request
	        $.ajax({
	           url:url,
	           method:"POST",
	           data:data,
	           //dataType:'JSON',
	           contentType: false,
	           cache: false,
	           processData: false,
	           success:function(data){
	                
      			$('div.table-container').html(data);
	            $('.fa-spinner').hide();

	           },
	            error: function (jqXHR, textStatus, errorThrown){
	                if (jqXHR.status == 401){
	                    location.href = "{{route ('login')}}"
	                    }      
	                var test = jqXHR.responseJSON // this object have two more objects one is errors and other is message.
	                
	                var errorMassage = '';

	                //now saperate only errors object values from test object and store in variable errorMassage;
	                $.each(test.errors, function (key, value){
	                errorMassage += value + '<br>';  
	                });
	                 $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
	                 $('html,body').scrollTop(0);
	                $('.fa-spinner').hide();                   
	                    
	            }//end error
	    }); //end ajax
      
    });

});

</script>



@stop