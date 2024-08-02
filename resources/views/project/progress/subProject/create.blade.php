<div class="card-body">
    <button type="button" class="btn btn-success float-right" id="createSubProject" data-toggle="modal">Add Sub Project</button>
    <br>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>Sub Project Name</th>
                <th>Weightage</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="json_message_modal" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="subProjectForm" name="subProjectForm" class="form-horizontal">

                    <input type="hidden" name="pr_sub_project_id" id="pr_sub_project_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Sub Project Name</label>
                                <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control notCapital" data-validation="required">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="control-label">Weightage</label>
                                <input type="text" name="weightage" id="weightage" data-validation="required" value="{{old('weightage')}}" class="form-control notCapital">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-dialog {
        max-width: 90%;
        display: flex;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {


        //only number value entered
        $('#weightage').on('change, keyup', function() {
            var currentInput = $(this).val();
            var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
            $(this).val(fixedInput);
        });

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('subProject.create') }}",
                columns: [{
                        data: "name",
                        name: 'name'
                    },
                    {
                        data: "weightage",
                        name: 'weightage'
                    },
                    {
                        data: 'Edit',
                        name: 'Edit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'Delete',
                        name: 'Delete',
                        orderable: false,
                        searchable: false
                    },

                ],

                order: [
                    [0, "desc"]
                ]
            });

            $('#createSubProject').click(function() {
                $('#json_message_modal').html('');
                $('#saveBtn').val("Create Sub Project");
                $('#pr_sub_project_id').val('');
                $('#subProjectForm').trigger("reset");
                $('#modelHeading').html("Create New Sub Project");
                $('#ajaxModel').modal('show');

            });
            $('body').unbind().on('click', '.editSubProject', function() {

                var pr_sub_project_id = $(this).data('id');
                $('#json_message_modal').html('');
                $.get("{{ url('hrms/project/subProject') }}" + '/' + pr_sub_project_id + '/edit', function(data) {

                    $('#modelHeading').html("Edit Sub Project");
                    $('#saveBtn').val("edit-Sub Project");
                    $('#pr_sub_project_id').val(data.id);
                    $('#name').val(data.name);
                    $('#weightage').val(data.weightage);
                    $('#ajaxModel').modal('show');

                })
            });
            $('#saveBtn').unbind().click(function(e) {
                $(this).attr('disabled', 'ture');
                //submit enalbe after 3 second
                setTimeout(function() {
                    $('.btn-prevent-multiple-submits').removeAttr('disabled');
                }, 3000);

                $.ajax({
                    data: $('#subProjectForm').serialize(),
                    url: "{{ route('subProject.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        $('#subProjectForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();

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

            $('body').on('click', '.deleteSubProject', function() {

                var pr_sub_project_id = $(this).data("id");
                var con = confirm("Are You sure want to delete !");
                if (con) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('subProject.store') }}" + '/' + pr_sub_project_id,
                        success: function(data) {
                            table.draw();
                            if (data.error) {
                                $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' + data.error + '</strong></div>');
                            }

                        },
                        error: function(data) {

                        }
                    });
                }
            });

        });

    });
</script>