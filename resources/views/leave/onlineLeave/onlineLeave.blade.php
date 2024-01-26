@extends('leave.onlineLeave.layouts.master')
@section('title', 'Online Leave')
@section('Heading')
<h3 class="text-themecolor"></h3>

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
                        <form id="jsonForm" method="post" action= "{{route('leave.employeeData')}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            <div class="form-body">

                                <h3 class="box-title">Leave Application</h3>

                                <hr class="m-t-0 m-b-40">

                                <div class="row">

                                    <div class="col-md-5">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                            <label class="control-label text-right">CNIC<span class="text_requried">*</span></label>

                                            <input type="text" name="cnic" id="cnic" pattern="[0-9.-]{15}" title="13 digit without dash" value="{{ old('cnic') }}" class="form-control" data-validation="required" placeholder="Enter CNIC without dash">
                                            <span id="cnicCheck" class="float-right" style="color:red"></span>

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
                                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Submit</button>
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

        $('.fa-spinner').hide();
        //formFunctions();
        // $('#jsonForm').on('submit', function(event) {
        //     event.preventDefault();
        //     //preventDefault work through formFunctions;
        //     url = "{{route('leave.employeeData')}}";
        //     $('.fa-spinner').show();
        //     submitFormAjax(this, url);
        // }); //end submit

        //ajax function
        function submitFormAjax(form, url, ) {
            //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
            $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
                var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
                }
            });

            var data = new FormData(form)

            // ajax request
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                //dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                   if(data == 'No Data Found'){
                        $('.fa-spinner').hide();
                        $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data + '</strong></div>');
                        $("#jsonForm")[0].reset();
                    }else{
                        console.log(data);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    var test = jqXHR.responseJSON // this object have two more objects one is errors and other is message.
                    var errorMassage = '';
                    //now saperate only errors object values from test object and store in variable errorMassage;
                    $.each(test.errors, function(key, value) {
                        errorMassage += value + '<br>';
                    });

                    $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + errorMassage + '</strong></div>');
                    $('html,body').scrollTop(0);
                    $('.fa-spinner').hide();

                } //end error
            }); //end ajax
        }

    });
</script>

@stop