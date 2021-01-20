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
	                                    <div class="form-group row"> 
	                                        <div class="col-md-12">
	                                        	<label class="control-label text-right">Status<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="is_lock"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    <option value="0">Open</option>
                                                    <option value="1">Lock</option>  
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
		            		<table id="inputTable" class="table table-bordered">
								<h2 align="center" id="heading"></h2>
						        <tr>
						            <th>Month & Year</th>
						            <th>Status</th>
						            <th class="text-center">Edit</th>
						            <th class="text-center">Delete</th>
						        </tr>
						        <tbody>
						        	@foreach($monthYears as $year)
						        	<tr id="tr_{{$year->id}}">
						        		<td>{{$year->month}}-{{$year->year}}</td>
						        		<td>{{$year->is_lock}}</td>
						        		<td class="text-center">
								 			<a class="btn btn-info btn-sm" id="editMonthInput{{$year->id}}" url= "{{route('inputMonth.edit',$year->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								 		</td>
						        	<td class="text-center">
								 			<form id="deleteInputMonth{{$year->id}}" action="{{route('inputMonth.destroy',$year->id)}}" method="POST">
								 			@method('DELETE')
								 			@csrf
								 			<button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash"></i></button>
								 			</form> 
								 		</td>
						        	</tr>
						        	@endforeach
						        </tbody>   
					    	</table>


		        		</div>       
		        	</div>
		        </div>
            </div>
        </div>
    </div>
</div>
@include('input.inputMonth.editModal')

<script>
 $(document).ready(function() {


 	selectTwo();
  	
    //submit function
     $("#addMonth").submit(function(e) { 
         e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 

        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });
        var data = new FormData(this)
        $.ajax({
           url:url,
           method:"POST",
           data:data,
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(res){
                if(res.status == 'OK'){
                $('#json_message').html('<div id="j_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+res.message+'</strong></div>');
                resetForm();
                	
               		
               	$("#inputTable tbody:last-child").append(
               		'<tr><td>'+res.data['month']+'-'+res.data['year']+
                        '</td><td>'+res.data['is_lock']+
                        '</td><td class="text-center"><a class="btn btn-info btn-sm" id=editMonthInput'+res.data['id']+'url= data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>'+
                        '</td><td class="text-center"><form id="deleteInputMonth+" action="+" method="POST">@method("DELETE")@csrf<button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm(\'Are you Sure to Delete\')" href= data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash"></i></button></form></td></tr>'); 

                }
                else{
                $('#json_message').html('<div id="j_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+res.message+'</strong></div>');    
                }

            $('html,body').scrollTop(0);
            $('.fa-spinner').hide();
            clearMessage();

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
                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
                 $('html,body').scrollTop(0);
                $('.fa-spinner').hide();                   
                    
            }//end error
   		 }); //end ajax

    }); //end submit

   

    //delete function
    $(document).on('submit','form[id^=deleteInputMonth]',function(event){
    event.preventDefault();
    var url = $(this).attr('action');
    var tr = $(this).closest('tr');
      $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Objectl
            }
        });
      $.ajax({
             method:"DELETE",
             url: url,
             success:function(res)
             {       
                  if(res)
                  {
                    tr.remove();
                  }     
             }

          });//end ajax
    }); //end submit



    
});

</script>

@stop