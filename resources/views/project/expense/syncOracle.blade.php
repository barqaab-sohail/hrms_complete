<script type="text/javascript">
    $(document).ready(function() {

        $('#syncExpense').on('click', function(event) {
            $('.fa-spinner').show();
            $('#syncExpense').attr('disabled', 'disabled');
            var url = "{{route('project.importExpense')}}"
            event.preventDefault();
            //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
            $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
                var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
                }
            });
            var data = '';
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                //dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.fa-spinner').hide();
                    $('#syncExpense').removeAttr("disabled");
                    if (data.error) {
                        $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');

                    } else {


                        $('#json_message').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
                        $('.reload').click();
                    }
                },
                error: function(data) {
                    $('.fa-spinner').hide();
                    $('#syncExpense').removeAttr("disabled");
                    var errorMassage = '';
                    $.each(data.responseJSON.errors, function(key, value) {
                        errorMassage += value + '<br>';
                    });
                    $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                    $('#saveBtn').html('Save Changes');
                }
            });
        });

    });
</script>