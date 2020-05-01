@extends('layouts.master.master')
@section('title', 'BARQAAB CV Record')
@section('Heading')

	<h3 class="text-themecolor">Edit CV Detail</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
		
		
	</ol>
	
@stop
@section('content')

    <div class="row">
        <div class="col-lg-12">

            <div class="card card-outline-info">
			
				<div class="row">
					<div class="col-lg-2">
					@include('layouts.vButton.cvButton')
					</div>
      	
		        	<div class="col-lg-10 addAjax">						 
		        		@include('cv.detail.ajax')
		        	</div>
		        </div>
            </div>
        </div>
    </div>
 @push('scripts')

<script>
$(document).ready(function(){

// $(document).on('click','#addDegree',function(){
// 	$('.hideDiv').toggle();
// 	$('#add_degree').val('');
//     $('#add_level').val('').select2('val', 'All');
// });

// $(document).on('click','#store_degree',function(){
// 	var url = $(this).attr('href');
// 	var degree_name = $('#add_degree').val();
//     var level = $('#add_level').val();
//     var result = [];
//     result.push({degree_name: degree_name, level: level});
//     if((degree_name != '')&&(level != '')){
//             submitFormWithoutDataForm(result, url);
//     }else{
//     	alert('Name of Degree and Level of Degree Required');
//     }

// });



$('.fa-spinner').hide();
formFunctions();
$(document).on('submit','#formCv',function(event){
 	event.preventDefault();
	url="{{route('cv.update',$data->id)}}";
	$('.fa-spinner').show();
   	submitForm(this, url);	 
});

$('a[id^=add]').click(function(e){
		var url = $(this).attr('href');
		var id = $(this).attr('id');
		e.preventDefault();
		$.ajax({
           url:url,
           method:"GET",
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
        		$(".addAjax").html(data);
        		$('a[id^=add]').css('background-color','');
        		$('#'+id).css('background-color','#737373');
        		formFunctions();
        		
               },
            error: function (jqXHR, textStatus, errorThrown){
            	if (jqXHR.status == 401){
            		location.href = "{{route ('login')}}"
            		}      
                }//end error
    	}); //end ajax	
});

//edit form load through ajax;
	$(document).on('click','a[id^=edit]',function(e){
		e.preventDefault();
		var url = $(this).attr('href');
		$.ajax({
           url:url,
           method:"GET",
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
        		$(".addAjax").html(data);
        		
        		formFunctions();
        		
               },
            error: function (jqXHR, textStatus, errorThrown){
            	if (jqXHR.status == 401){
            		location.href = "{{route ('login')}}"
            		}      
                          

                    }//end error
    	}); //end ajax	
	});




	$('select:not(.selectTwo)').chosen();


	$(document).on('change','#country',function(){
        var cid = $(this).val();
        if(cid){
	        $.ajax({
	           type:"get",
	           url: "{{url('country/states')}}"+"/"+cid,

	           //url:" url('CV/uploadCv/getStates') /"+cid, **//Please see the note at the end of the post**
	           success:function(res)
	           {       
	               
	                if(res)
	                {
	                   $("#state").empty();
	                    $("#state").append('<option value="">Select State</option>');
	                    $.each(res,function(key,value){
	                        $("#state").append('<option value="'+key+'">'+value+'</option>');
	                        
	                    });
	                     $('#state').chosen('destroy');
	                     $('#state').chosen();

	                    $("#city").empty();
	                    $("#city").append('<option>Select City</option>');
	                    $('#city').chosen('destroy');
	                    $('#city').chosen();

	                }
	           }

	        });
        }
    });

   $(document).on('change','#state',function(){
        var sid = $(this).val();
        if(sid){
	        $.ajax({
	           type:"get",
	            url: "{{url('country/cities')}}"+"/"+sid,
	           success:function(res)
	           {       
	                if(res)
	                {
	                    $("#city").empty();
	                    $("#city").append('<option value="">Select City</option>');
	                    $.each(res,function(key,value){
	                        $("#city").append('<option value="'+key+'">'+value+'</option>');
	                    });
	                    $('#city').chosen('destroy');
	                    $('#city').chosen();
	                }
	           }

	        });
        }
    }); 	


	$(document).on('change','#cv',function(){
	 	var fileType = this.files[0].type;
	 	var fileSize = this.files[0].size;

	 	if (fileSize> 4096000){
		        	alert('File Size is bigger than 4MB');
		        	$(this).val('');
		}else{

		    if((fileType!='application/vnd.openxmlformats-officedocument.wordprocessingml.document')&&(fileType!='application/msword')&&(fileType!='application/pdf')){

	 		alert('Only pdf, doc and docx attachment allowed');
	 		$(this).val('');
	 		}
	 	}
	});
			
		  //Dynamic add phone
		 // Add new element
		 $(document).on('click','#add_phone',function(){
		  // Finding total number of elements added
		  var total_element = $(".phone").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".phone:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   	var $clone = $("#phone_1").clone();
		  	$clone.prop('id','phone'+nextindex).find('input:text').val('');
		   	$clone.find("#add_phone").html('X').prop("class", "btn btn-danger remove_phone").attr('id','remove_phone');
		   	$clone.find('.remove_phone_div').remove();
		   	$clone.insertAfter("div.phone:last");
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_phone', function(){
		 $(this).closest(".phone").remove();
 		}); 




		//Dynamic add education
		
		// Add new element
		 $(document).on('click','#add',function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".education").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".education:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone education div and copy
		   $('.education').find('select').chosen('destroy');
		   	var clone = $(".education:last").clone();
		  	clone.prop('id','edu_'+nextindex).find('#degree_name, #institute, #passing_year').val('');
		   	clone.find("#add").html('X').prop("class", "btn btn-danger remove_edu").attr('id','remove_education');
		   	clone.insertAfter("div.education:last");
		   	$('.education').find('select').chosen();

		  }
		 
		});
		 // Remove element
		 $(document).on("click", '.remove_edu', function(){
		 $(this).closest(".education").remove();
 		}); 

		//Dynamic add specialization
		 // Add new element
		 $(document).on('click','#add_spe',function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".specialization").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".specialization:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 10;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   $('.specialization').find('select').chosen('destroy');
		   	var $clone = $("#spe_1").clone();
		  	$clone.prop('id','spe_'+nextindex).find('#speciality_name, #field_name, #field_year').val('');
		   	$clone.find("#add_spe").html('X').prop("class", "btn btn-danger remove_spe").attr('id','remove_specification');
		   	$clone.insertAfter("div.specialization:last");
		   	$('.specialization').find('select').chosen();
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_spe', function(){
		 $(this).closest(".specialization").remove();
		  
 		}); 


		 //Dynamic add Skill
		 // Add new element
		 $("#add_skill").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".skill").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".skill:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   	var $clone = $("#skill_1").clone();
		  	$clone.prop('id','skill_'+nextindex).find('input:text').val('');
		   	$clone.find("#add_skill").html('X').prop("class", "btn btn-danger remove_skill");
		   	$clone.insertAfter("div.skill:last");
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_skill', function(){
		 $(this).closest(".skill").remove();
		  
 		}); 

		 //Dynamic add membership
		 // Add new element
		 $("#add_mem").click(function(){
		 	
		  // Finding total number of elements added
		  var total_element = $(".membership").length;
		 	
		  // last <div> with element class id
		  var lastid = $(".membership:last").attr("id");
		  var split_id = lastid.split("_");
		  var nextindex = Number(split_id[1]) + 1;
		  var max = 5;
		  // Check total number elements
		  if(total_element < max ){
		   //Clone specialization div and copy
		   $('.membership').find('select').chosen('destroy');
		   	var $clone = $("#membership_1").clone();
		  	$clone.prop('id','membership_'+nextindex).find('input:text').val('');
		   	$clone.find("#add_mem").html('X').prop("class", "btn btn-danger remove_membership");
		   	$clone.find('.remove_div').remove();
		   	$clone.insertAfter("div.membership:last");
		   	$('.membership').find('select').chosen();
		  }
		 
		 });
		 // Remove element
		 $(document).on("click", '.remove_membership', function(){
		 $(this).closest(".membership").remove();
		  
 		}); 


	});	

	
</script>

@endpush

@stop