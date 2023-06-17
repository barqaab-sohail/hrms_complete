@extends('layouts.master.master')
@section('title', 'Multiple Prints')
@section('Heading')
<h3 class="text-themecolor">Multiple Prints</h3>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.css">
@stop
@section('content')


<div class="card">
    <div class="card-body">
        <h4 class="card-title">Multiple Prints</h4>
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop and Rotate image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-12">
                            <!--  default image where we will set the src via jquery-->
                            <img id="modal_image">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {

        $('iframe').remove();

        $('.fa-spinner').hide();
        $('select').select2();

        var $modal = $('#modal');
        var image = document.getElementById('modal_image');
        var cropper;
        var imageId;
        var imageData1;
        var imageData2;

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#modal_image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#1, #2").change(function() {
            imageId = $(this).attr('id');
            readURL(this);
            $modal.modal('show');
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

            cropper = new Cropme(image, options);
        }).on('hidden.bs.modal', function() {
            // image.src = '';
            // cropper.destroy();
            // cropper = null;

        });

        $("#cancel").click(function() {
            $("#" + imageId).val('');
        });

        $("#crop").click(function() {
            $("#" + imageId).attr('hidden', true);
            cropper.crop()
                .then(function(output) {
                    $('#image_' + imageId).attr("src", output);
                    if (imageId == 1) {
                        imageData1 = output;
                    } else {
                        imageData2 = output;
                    }

                    $('#submit').removeAttr('hidden');

                });
            $modal.modal('hide');

        });

        $("#submit").click(function() {
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
                    $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>File Create Sucessfully</strong></div>');
                    $(".pdfView").append("<iframe src=\"{{asset('prints_output.pdf')}}\" height='700' width='100%'/>");
                    $('#submit').attr('hidden', true);


                },
                error: function($data) {
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