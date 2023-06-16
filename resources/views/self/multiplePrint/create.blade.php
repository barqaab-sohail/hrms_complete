@extends('layouts.master.master')
@section('title', 'Multiple Prints')
@section('Heading')
<h3 class="text-themecolor">Multiple Prints</h3>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.css">
@stop
@section('content')
<style type="text/css">
    .image {
        display: block;
        max-width: 100%;
    }
</style>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Multiple Prints</h4>
        <hr>
        <form method="get" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

                <div class="row">
                    <div class="col-md-9">
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
                            <img id="image">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        var image = document.getElementById('image');
        var cropper;
        var imageId;
        var imageData1;
        var imageData2;

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#1, #2").change(function() {
            imageId = $(this).attr('id');
            readURL(this);
            $modal.modal('show');
        });


        // $("body").on("change", ".image", function(e) {
        //     imageId = $(this).attr('id');
        //     console.log(imageId);
        //     var files = e.target.files;
        //     var done = function(url) {
        //         image.src = url;
        //         $modal.modal('show');
        //     };

        //     var reader;
        //     var file;
        //     var url;

        //     if (files && files.length > 0) {
        //         file = files[0];

        //         if (URL) {
        //             done(URL.createObjectURL(file));
        //         } else if (FileReader) {
        //             reader = new FileReader();
        //             reader.onload = function(e) {
        //                 done(reader.result);
        //             };
        //             reader.readAsDataURL(file);
        //         }
        //     }
        // });

        $modal.on('shown.bs.modal', function() {


            cropper = new Cropme(image, {
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
            });
            // cropper = new Cropper(image, {
            //     aspectRatio: 1,
            //     viewMode: 3,
            //     rotatable: true,
            //     preview: '.preview'
            // });
        }).on('hidden.bs.modal', function() {
            // cropper.destroy();
            // cropper = null;

        });

        $("#crop").click(function() {

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
            // canvas.toBlob(function(blob) {
            //     url = URL.createObjectURL(blob);
            //     var reader = new FileReader();
            //     reader.readAsDataURL(blob);
            //     reader.onloadend = function() {
            //         var base64data = reader.result;
            //         $.ajax({
            //             type: "POST",
            //             dataType: "json",
            //             url: "crop-image-upload-ajax",
            //             data: {
            //                 '_token': $('meta[name="_token"]').attr('content'),
            //                 'image': base64data
            //             },
            //             success: function(data) {
            //                 console.log(data);
            //                 $modal.modal('hide');
            //                 alert("Crop image successfully uploaded");
            //             }
            //         });
            //     }
            // });
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
                    console.log(data);

                }
            });
        });

    });
</script>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropme@latest/dist/cropme.min.js"></script>
@endpush


@stop