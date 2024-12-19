@extends('layouts.master.master')
@section('title', 'Temp Upload File')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card-body">
    <div class="video_upload mt-5" id="video_upload ">

        <div id="upload-container" class="text-center">

            <button id="browseFile" class="btn btn-primary">Upload File</button> <br> <br>
        </div>


        <div style="display: none" class="progress mt-3 w-25 mx-auto" style="height: 25px">

            <div class="progress-bar progress-bar-striped progress-bar-animated text-center" role="progressbar"
                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">

                75%</div>

        </div>

    </div>

    <div class="d-none text-center" id="final_success">

        <h2 class="text-success">File Upload Successfull </h2>

    </div>



    <table class="table table-striped data-table">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Size</th>
                <th>Delete</th>
            </tr>
        </thead>
    </table>
</div>



<script type="text/javascript">
$(document).ready(function() {

    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tempfileupload.create') }}",
            columns: [{
                    data: "file_name",
                    name: 'file_name'
                },
                {
                    data: "size",
                    name: 'size'
                },
                {
                    data: 'Delete',
                    name: 'Delete',
                    orderable: false,
                    searchable: false
                },

            ],
        });


        $('body').on('click', '.deleteAllowanceName', function() {

            var allowance_name_id = $(this).data("id");
            var con = confirm("Are You sure want to delete !");
            if (con) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('tempfileupload.store') }}" + '/' +
                        allowance_name_id,
                    success: function(data) {
                        table.draw();
                        clearMessage();
                        if (data.error) {
                            $('#json_message').html(
                                '<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                                data.error + '</strong></div>');
                        }

                    },
                    error: function(data) {

                    }
                });
            }
        });

        let resumable = new Resumable({

            target: "{{ route('tempfileupload.store') }}",

            query: {
                _token: '{{ csrf_token() }}',
            },

            chunkSize: 10 * 1024 * 1024, // 10MB chunks

            testChunks: false,

            throttleProgressCallbacks: 1,

        });

        resumable.assignBrowse(document.getElementById('browseFile'));

        resumable.on('fileAdded', function(file) { // trigger when file picked

            showProgress();

            resumable.upload() // to actually start uploading.

        });

        resumable.on('fileProgress', function(file) { // trigger when file progress update

            updateProgress(Math.floor(file.progress() * 100));

        });

        resumable.on('fileSuccess', function(file, response) { // trigger when file upload complete

            $('#video_upload').addClass('d-none');

            $('#video_upload').hide();

            $('#final_success').removeClass('d-none');

            $('#final_success').show();
            table.draw();
            hideProgress();

        });

        resumable.on('fileError', function(file, response) { // trigger when there is any error

            hideProgress();

            alert('file uploading error.')

        });

        let progress = $('.progress');

        function showProgress() {

            progress.find('.progress-bar').css('width', '0%');

            progress.find('.progress-bar').html('0%');

            progress.find('.progress-bar').removeClass('bg-success');

            progress.show();

        }

        function updateProgress(value) {

            progress.find('.progress-bar').css('width', `${value}%`)

            progress.find('.progress-bar').html(`${value}%`)

        }

        function hideProgress() {

            progress.hide();

        }



    });
});
</script>
<script>

</script>
@stop