<div class="modal fade" id="fileModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="uploadFileForm" name="uploadFileForm" class="form-horizontal form-prevent-multiple-submits">
                    <label for="formFileMultiple" class="form-label">Upload Excel File</label>
                    <input class="form-control" type="file" name="excel_file" id="formFileMultiple" multiple />

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" id="submitBtn" style="font-size:18px"></i>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#uploadFileForm').on('submit', function(event) {
            $(this).attr('disabled', 'ture');
            setTimeout(function() {
                $('.btn-prevent-multiple-submits').removeAttr('disabled');
            }, 3000);
            var url = "{{route('project.importExpense')}}"
            event.preventDefault();
            //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
            $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
                var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
                }
            });
            var data = new FormData(this);
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                //dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.error) {
                        $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                        $('#uploadFileForm').trigger("reset");
                    } else {
                        $('#uploadFileForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        $('#json_message_modal').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.success + '</strong></div>');
                        $('.reload').click();
                    }
                },
                error: function(data) {

                    var errorMassage = '';
                    $.each(data.responseJSON.errors, function(key, value) {
                        errorMassage += value + '<br>';
                    });
                    $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');

                    $('#saveBtn').html('Save Changes');
                }
            });
        });

    });
</script>