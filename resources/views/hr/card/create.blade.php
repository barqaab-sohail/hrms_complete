@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
<h3 class="text-themecolor">Create Employee Card</h3>
@stop
@section('content')
<style type="text/css">
    .image {
        display: block;
        max-width: 100%;
    }

    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
</style>
<div class="container">
    <h5>Upload Images</h5>
    <form method="post">
        <input type="file" name="image" class="image">
    </form>
</div>
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Create Employee Card</h4>
        <hr>
        <form id="employeePermissionSearch" method="get" class="form-horizontal form-prevent-multiple-submits" action="{{route('permission.result')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-body">

                <div class="row">
                    <div class="col-md-6">
                        <!--/span 5-1 -->
                        <div class="form-group row">
                            <div class="col-md-12 required">
                                <label class="control-label text-right">Name of Employee</label><br>
                                <select name="employee" id="employee" class="form-control searchSelect">
                                    <option value=""></option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}" {{(old("employee")==$employee->id? "selected" : "")}}>{{$employee->first_name}} {{$employee->last_name}} - {{$employee->employeeDesignation->last()->name??''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" id="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Create Card</button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

        <hr>
        <div class="row">
            <div class="col-md-12 table-container">


            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <!--  default image where we will set the src via jquery-->
                            <img id="image">
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
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

        $('.fa-spinner').hide();
        $('select').select2();
        var bs_modal = $('#modal');
        var image = document.getElementById('image');
        var cropper, reader, file;


        $("#employee").change(function() {
            var employeeId = $(this).val();
            if (employeeId) {
                $.ajax({
                    type: "get",
                    url: "{{url('hrms/employee/getEmployeePicture')}}" + "/" + employeeId,
                    success: function(data) {
                        console.log(data);
                        bs_modal.modal('show');
                    },
                    error: function(data) {
                        $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>No Data Found</strong></div>');
                        console.log('no data found')
                    }
                }); //end ajax
            }


        });



        $("body").on("change", ".image", function(e) {
            var files = e.target.files;
            var done = function(url) {
                console.log(url)
                image.src = "http://localhost/hrms/public/storage/hr/documentation/4-jamshaid_siddiqui/4-Picture-1587361007.jpg";
                bs_modal.modal('show');
            };


            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        bs_modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    //alert(base64data);
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "crop_image_upload.php",
                        data: {
                            image: base64data
                        },
                        success: function(data) {
                            bs_modal.modal('hide');
                            alert("success upload image");
                        }
                    });
                };
            });
        });


    });
</script>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
@endpush


@stop