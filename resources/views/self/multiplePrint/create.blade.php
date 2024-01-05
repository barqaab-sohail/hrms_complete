@extends('layouts.master.master')
@section('title', 'CNIC Multiple Prints')
@section('Heading')
<h3 class="text-themecolor">CNIC Multiple Prints</h3>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.css">
@stop
@section('content')


<div class="card">
    <div class="card-body">
        <h4 class="card-title">CNIC Multiple Prints</h4>
        <hr>
        <form method="get" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

                <div class="row">
                    <div class="col-md-12">
                        <!--/span 5-1 -->
                        <div class="form-group row">
                            <div class="col-md-6 required">
                                <label class="control-label text-right">First Image</label><br>
                                <input type=file name="image_1" id="1" class="image" />
                            </div>
                            <div class="col-md-6 required">
                                <label class="control-label text-right">Second Image</label><br>
                                <input type=file name="image_2" id="2" class="image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div> -->
        </form>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <img src='' id="image_1" width="50%" />
            </div>
            <div class="col-md-6">
                <img src='' id="image_2" width="50%" />
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 pdfView">

            </div>
        </div>
        <button type="submit" id="submit" hidden class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Submit</button>
    </div>
</div>

<!-- Modal -->
@include('self.multiplePrint.image1')
@include('self.multiplePrint.image2')




<script>
    $(document).ready(function() {

        $('iframe').remove();
        $('.fa-spinner').hide();
        var imageData1;
        var imageData2;
        //image_1
        var $modal = $('#modal_1');
        var image = document.getElementById('modal_image_1');
        var cropper_1;



        function readURL_1(input) {
            if (input.files && input.files[0]) {
                var fileType = input.files[0].type;
                if ((fileType == 'image/jpeg') || (fileType == 'image/png')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#modal_image_1').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
                } else {

                    alert('Only jpg and png file allowed');
                    $("#1").val('');
                    return false;
                }
            }
        }

        $("#1").change(function() {

            if (readURL_1(this)) {
                $modal.modal('show');
            }
        });

        var options = {
            "container": {
                "width": "80%",
                "height": 600
            },
            "viewport": {
                "width": 563,
                "height": 360,
                "type": "square",
                "border": {
                    "width": 2,
                    "enable": true,
                    "color": "#fff"
                }
            },
            "zoom": {
                "enable": true,
                "mouseWheel": true,
                "slider": true
            },
            "rotation": {
                "slider": true,
                "enable": true,
                "position": "left"
            },
            "transformOrigin": "viewport"
        };

        $modal.on('shown.bs.modal', function() {

            cropper_1 = new Cropme(image, options);
        }).on('hidden.bs.modal', function() {

            cropper_1.destroy();
            cropper_1 = null;

        });

        $("#cancel_1").click(function() {
            $("#1").val('');
        });

        $("#crop_1").click(function() {
            $("#1").attr('hidden', true);
            cropper_1.crop()
                .then(function(output) {
                    $('#image_1').attr("src", output);

                    imageData1 = output;


                    $('#submit').removeAttr('hidden');

                });
            $modal.modal('hide');

        });

        //Image_2
        var $modal_2 = $('#modal_2');
        var image_2 = document.getElementById('modal_image_2');
        var cropper_2;



        function readURL_2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var fileType = input.files[0].type;
                if ((fileType == 'image/jpeg') || (fileType == 'image/png')) {
                    reader.onload = function(e) {
                        $('#modal_image_2').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                    return true;
                } else {

                    alert('Only jpg and png file allowed');
                    $("#2").val('');
                    return false;
                }
            }
        }

        $("#2").change(function() {

            if (readURL_2(this)) {
                $modal_2.modal('show');
            }
        });

        var options_2 = {
            "container": {
                "width": "80%",
                "height": 600
            },
            "viewport": {
                "width": 563,
                "height": 360,
                "type": "square",
                "border": {
                    "width": 2,
                    "enable": true,
                    "color": "#fff"
                }
            },
            "zoom": {
                "enable": true,
                "mouseWheel": true,
                "slider": true
            },
            "rotation": {
                "slider": true,
                "enable": true,
                "position": "left"
            },
            "transformOrigin": "viewport"
        };

        $modal_2.on('shown.bs.modal', function() {

            cropper_2 = new Cropme(image_2, options_2);
        }).on('hidden.bs.modal', function() {

            cropper_2.destroy();
            cropper_2 = null;

        });

        $("#cancel_2").click(function() {
            $("#2").val('');
        });

        $("#crop_2").click(function() {
            $("#2").attr('hidden', true);
            cropper_2.crop()
                .then(function(output) {
                    $('#image_2').attr("src", output);

                    imageData2 = output;


                    $('#submit').removeAttr('hidden');

                });
            $modal_2.modal('hide');

        });




        $("#submit").click(function() {
            $(this).attr('disabled', 'ture');
            $('.fa-spinner').show();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{route('multiplePrint.output')}}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'image1': imageData1,
                    'image2': imageData2,
                },
                success: function(data) {
                    $(this).attr('disabled', 'false');
                    $('.fa-spinner').hide();
                    $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>File Create Sucessfully</strong></div>');
                    $(".pdfView").append("<iframe src=\"{{asset('prints_output.pdf')}}\" height='700' width='100%'/>");
                    $('#submit').attr('hidden', true);


                },
                error: function(data) {
                    console.log(data);
                    $(this).attr('disabled', 'false');
                    $('.fa-spinner').hide();
                    $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>File Not Created</strong></div>');
                }
            });
        });

    });
</script>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.js"></script>
@endpush


@stop
