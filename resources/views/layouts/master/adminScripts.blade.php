<!-- All Jquery -->
<!-- ============================================================== -->

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>

<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('Massets/plugins/popper/popper.min.js') }}"></script>
<script src="{{asset('Massets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('Massets/js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{asset('Massets/js/waves.js') }}"></script>
<!--Menu sidebar Dropdown Effect-->
<script src="{{asset('Massets/js/sidebarmenu.js') }}"></script>
<!--stickey kit -->
<script src="{{asset('Massets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{asset('Massets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!--Custom JavaScript in HRMS -->
<script src="{{asset('Massets/js/custom.js') }}"></script>

<!-- JS Form Validations -->
<script   src="{{asset('Massets/js/js-validation/jquery.form-validator.min.js') }}"></script>

<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
<!--c3 JavaScript -->
<script src="{{asset('Massets/plugins/d3/d3.min.js') }}"></script>
<script src="{{asset('Massets/plugins/c3-master/c3.min.js') }}"></script>

<!--CDN ChartJS 
 -->
 <script src="http://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>
  <script src="http://www.chartjs.org/samples/latest/utils.js"></script>

<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{asset('Massets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

<script src="{{asset('Massets/select2/select2.full.min.js')}}"></script>
<!-- This is data table -->
    <script src="{{asset('Massets/plugins/datatables/datatables.min.js')}}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
   
    <!-- end - This is for export functionality only -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>



@yield('scripts')
@stack('scripts')
<script src="{{asset('Massets/plugins/html5-editor/wysihtml5-0.3.0.js')}}"></script>
<script src="{{asset('Massets/plugins/html5-editor/bootstrap-wysihtml5.js')}}"></script>

<!-- Jquery UI Datepicker "Redmond theme" JS -->
<script src="{{asset('Massets/js/jquery-ui/jquery-ui.js')}}"></script>

<script src="{{asset('Massets/js/full-image/EZView.js') }}"></script>

<script src="{{asset('Massets/js/crop/jquery.imgareaselect.min.js') }}"></script>
<script src="{{asset('Massets/js/crop/croppie.min.js') }}"></script>

 <!-- Duplicate rows Merge -->
<script src="{{asset('Massets/js/rowsmerge/jquery.rowspanizer.min.js') }}"></script>
 

 <!-- chosen plugin -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>




<script src="https://cdn.tiny.cloud/1/6k6kj2mbbmwv1jqeh7sqe7jf29uemxfwvq4kzdpz5a4j9gm1/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script> tinymce.init({
    selector: 'textarea',
    forced_root_block:"",
   
  });</script>


<script>

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

function submitFormAjax(form, url,reset=0){

        //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });

        //ajax request
        $.ajax({
           url:url,
           method:"POST",
           data:new FormData(form),
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Data Successfully Entered</strong></div>');
                $('html,body').scrollTop(0);
                $('.fa-spinner').hide();
                console.log(data);
                    if(reset===0){resetForm();}
               },
            error: function (request, status, error) {
                        var test = request.responseJSON // this object have two more objects one is errors and other is message.
                        
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
}


   
$(document).ready(function() {
    $('input[type=text]').keyup (function () {
        $(this).val($(this).val().toLowerCase());
    });

    //email type input in lower case
    $('input[type=email]').keyup(function() {
    $(this).val($(this).val().toLowerCase());
    });

    
    
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

//Date input   
    $(".date_input").each(function(){
        if ($(this).val()!=''){
        $(this).siblings('i').show();
        }else{

            $(this).siblings('i').hide();
        }

    });

    //If Click icon than clear date
    $(".fa-trash-alt").click(function (){
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




}); // end document.ready

  //Email validation
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }           


</script>
