


function fromPreventDefault(){
    $("form").submit(function (e) {
      e.preventDefault();
    });
}


function selectTwo(){
    $('.selectTwo').select2({
        width: "100%",
        theme: "classic",
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) { 
                error.insertAfter(element.parent());      // radio/checkbox?
            } else if (element.hasClass('select2')) {     
                error.insertAfter(element.next('span'));  // select2
            } else {                                      
                error.insertAfter(element);               // default
            }
        }

    });
}





function formFunctions(){

    $('.hideDiv').hide();


     //get Date from Database and set as "Saturday, 24-August-2019"
    var weekday = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
    
     var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    
    //if Date not empty than enter date with format 'Wednesday, 10-August-2010'

    $(".date_input").each(function(){
        if ($(this).val()!=''){
        var Date1 = new Date($(this).val());
        $(this).val(
        weekday[Date1.getDay()]+", "+
        Date1.getDate()+"-"+months[Date1.getMonth()]
        +"-"+Date1.getFullYear());
        }else{
            $(this).siblings('i').hide();
        }

    });




    //If Click icon than clear date
    $(".date_input").siblings('i').click(function (){
        if(confirm("Are you sure to clear date")){
        $(this).siblings('input').val("");
        $(this).hide();
        }
    });


    // DatePicker
   $(".date_input").datepicker({
    dateFormat: 'D, d-M-yy',
    yearRange: '1940:'+ (new Date().getFullYear()+15),
    changeMonth: true,
    changeYear: true,

    }).on('change',function(){
         $(this).siblings('i').show();
    });

    //Lower Case of all inputs type text and email
    $('input[type=text],input[type=email]').keyup(function() {
    $(this).val($(this).val().toLowerCase());
    });

    selectTwo();

    $.validate({
    validateHiddenInputs: true,
    });

    $('.fa-spinner').hide();
} //;end formFunctions

                
     //Make sure that the event fires on input change
    $("#cnic").on('input', function(ev){
        
        //Prevent default
        ev.preventDefault();
        
        //Remove hyphens
        let input = ev.target.value.split("-").join("");
        if(ev.target.value.length>15){
            input =  input.substring(0,input.length-1)
        }
        
        //Make a new string with the hyphens
        // Note that we make it into an array, and then join it at the end
        // This is so that we can use .map() 
        input = input.split('').map(function(cur, index){
            
            //If the size of input is 6 or 8, insert dash before it
            //else, just insert input
            if(index == 5 || index == 12)
                return "-" + cur;
            else
                return cur;
        }).join('');
        
        //Return the new string
        $(this).val(input);
    });


 //function to prevent double click on submit button
(function(){
    $('.form-prevent-multiple-submits').on('submit', function(){

        $('.btn-prevent-multiple-submits').attr('disabled','ture');
        $('.spinner').show();

        //submit enalbe after 5 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
            }, 5000);

    })

})();

function resetForm(){
    $(':input','form')
                     .not(':button, :submit, :reset')
                     .val('')
                     .removeAttr('checked')
                     .removeAttr('selected');
    
    $('select').val('').trigger('chosen:updated');
    $('.selectTwo').val('').select2('val', 'All');
    $('.remove').click();
    $(".date_input").siblings('i').hide();
    $('input').removeClass('valid');
    $('input').removeClass('error');
    $("input[style='border-color: rgb(185, 74, 72);']").css('border-color','').siblings("span").attr('class','help-block').remove();

}

function submitFormAjax(form, url){
        //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });

        console.log(form);
        var data = new FormData(form)

        for (var value of data.values()) {
        console.log(value); 
        }

        if($(form).attr('action')){
             var data = new FormData(form)
             for (var value of data.values()) {
        console.log(value); 
        }
        }else{
            var formData = new FormData();
            //formData.append('total_salary', 'form');
        }
        //var data = new FormData(form)
       console.log(formData);

        //ajax request
    //     $.ajax({
    //        url:url,
    //        method:"POST",
    //        data:data,
    //        //dataType:'JSON',
    //        contentType: false,
    //        cache: false,
    //        processData: false,
    //        success:function(data)
    //            {
               
    //            	if("url" in data){
    //            		location.href = data.url;
    //            	}else if ('reset' in data){
    //            		$('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Data Successfully Entered</strong></div>');
    //             	$('html,body').scrollTop(0);
    //             	$('.fa-spinner').hide();
    //            		resetForm();
    //            	}else{
    //            		$('#json_message').attr('class','alert alert-success').removeAttr('hidden').find('strong').text(data.message).siblings('i').removeAttr('hidden');
    //                 $('#json_message').find('i').click(function(){$('#json_message').attr('hidden','hidden');});
    //             	$('html,body').scrollTop(0);
    //             	$('.fa-spinner').hide();
                  
    //            	}
        
    //            },
    //         error: function (request, status, error) {
    //                     var test = request.responseJSON // this object have two more objects one is errors and other is message.
                        
    //                     var errorMassage = '';

    //                     //now saperate only errors object values from test object and store in variable errorMassage;
    //                     $.each(test.errors, function (key, value){
    //                       errorMassage += value ;
    //                     });
                         
    //                     $('#json_message').attr('class','alert alert-danger').removeAttr('hidden').find('strong').text(errorMassage).siblings('i').removeAttr('hidden');
    //                     $('#json_message').find('i').click(function(){$('#json_message').attr('hidden','hidden');});
    //                     $('html,body').scrollTop(0);
    //                     $('.fa-spinner').hide();
                        
                            
    //                 }//end error
    // }); //end ajax
}


