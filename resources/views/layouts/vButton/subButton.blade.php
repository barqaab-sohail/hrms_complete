<div class="dropdownSubMenue">
	<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span id="dropdownSubMenueText" style="font-size:20px"></span>
    </button>

	<div class="dropdown-menu" role="group"  aria-labelledby="dropdownMenuButton" style="width: 100%;">   
	        <a type="submit" role="button" id="addSubmission" href="{{route('submission.edit',session('submission_id'))}}" style="color:white" class="dropdown-item btn btn-success " {{Request::is('hrms/submission/*/edit')?'style=background-color:#737373':''}}>Submission Detail</a>
	        <a type="submit" role="button" id="addPartner" href="{{route('submissionPartner.index')}}" style="color:white" class="dropdown-item btn btn-success " {{Request::is('hrms/submission/partner')?'style=background-color:#737373':''}}>Partner Detail</a>

	        <a type="submit" role="button" id="addDocument" href="{{route('submissionDocument.create')}}" style="color:white" class="dropdown-item btn btn-success " {{Request::is('hrms/submissionDocument/create')?'style=background-color:#737373':''}}>Documents</a>
	</div>
             
</div>

<script >
$(document).ready(function() {
    $('.dropdown-item').on('click', function(){
    $('#dropdownSubMenueText').text($(this).text());
    });
    
    var showText = $('.dropdownSubMenue').find('a:first').text();
    $('#dropdownSubMenueText').text(showText);
    $(".dropdownSubMenue").hover(function(){
          $('.reducedCol').removeClass('col-lg-12').addClass('col-lg-10');
          $(this).addClass('dropdown');
          var dropdownMenu = $(this).children(".dropdown-menu");
          if(dropdownMenu.is(":visible")){
                dropdownMenu.parent().toggleClass("open");
                $('.dropdown-item').click(function(){
                	$(".dropdown").removeClass("dropdown");
                });
          }else{
            $('.reducedCol').removeClass('col-lg-10').addClass('col-lg-12');
          }
    });

    // Vertical Button function to load page through ajax 
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
        	$(".addAjax").empty();
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


});
</script>