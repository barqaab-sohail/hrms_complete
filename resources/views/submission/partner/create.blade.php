
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubmissionPartner" data-toggle="modal" >Add Partner</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th>Name of Firm</th>
          <th>Role</th>
          <th>% Share</th>
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
              <form id="submissionPartnerForm" name="submissionPartnerForm" action="{{route('submissionPartner.store')}}"class="form-horizontal">
                   
                  <input type="hidden" name="sub_participate_role_id" id="sub_participate_role_id">
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Name of Firm<span class="text_requried">*</span></label>
                            <select  id="partner_id"   name="partner_id"  class="form-control selectTwo" data-validation="required">
                            </select>
                            
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                          <label class="control-label">Firm Role<span class="text_requried">*</span></label>
                          <select  id="pr_role_id"   name="pr_role_id"  class="form-control selectTwo" data-validation="required">
                          </select>  
                        </div>
                    </div>
                    <div class="col-md-2" id="hideShare">
                      <div class="form-group">
                          <label class="control-label">% Share</label>
                          <input type="text" name="share"  id="share" value="{{old('share')}}" class="form-control prc_1" data-validation="required">
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-success" id="saveBtn" value="create">Save changes
                   </button>
                  </div>
              </form>
            </div>
        </div>
    </div>
</div>
<style>
  .modal-dialog {
    max-width: 80%;
    display: flex;
}
</style>

<script type="text/javascript">


$(document).ready(function() {
  $('#hideShare').hide();
  $("#pr_role_id").change(function (){
      
      if($(this).val()!=1){
        $('#hideShare').show();
      }
      else{
        $('#hideShare').hide();
        $('#share').val('');
      }
  });

  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: "{{ route('submissionPartner.index') }}",
        columns: [
            {data: "partner_name", name: 'partner_name'},
            {data: "role_name", name: 'role_name'},
            {data: "share", name: 'share'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createSubmissionPartner').click(function () {
        $('#json_message_modal').html('');
        $('#submissionForm').trigger("reset");
        $.get("{{ url('hrms/submissionPartner/create') }}" , function (data) {

              $("#partner_id").empty();
              $("#partner_id").append('<option value="">Select Partner</option>');
              $.each(data.partners, function (key, value){
                $("#partner_id").append('<option value="'+value.id+'">'+value.name+'</option>');
              });
              $("#pr_role_id").empty();
              $("#pr_role_id").append('<option value="">Select Role</option>');
              $.each(data.prRoles, function (key, value){
                $("#pr_role_id").append('<option value="'+value.id+'">'+value.name+'</option>');
              });
              $('#ajaxModel').modal('show');
        });
    });



    $('body').unbind().on('click', '.editSubmissionPartner', function () {
      var sub_participate_role_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionPartner') }}" +'/' + sub_participate_role_id +'/edit', function (data) {
          $("#partner_id").empty();
          $("#partner_id").append('<option value="">Select Partner</option>');
          $.each(data.partners, function (key, value){
          $("#partner_id").append('<option value="'+value.id+'">'+value.name+'</option>');
          });
          $("#pr_role_id").empty();
          $("#pr_role_id").append('<option value="">Select Role</option>');
          $.each(data.prRoles, function (key, value){
            $("#pr_role_id").append('<option value="'+value.id+'">'+value.name+'</option>');
          });

          $('#modelHeading').html("Edit Partner");
          $('#saveBtn').val("edit-Partner");
          $('#sub_participate_role_id').val(data.data.id);
          $('#partner_id').val(data.data.partner_id);
          $('#partner_id').trigger('change');
          $('#pr_role_id').val(data.data.pr_role_id);
          $('#pr_role_id').trigger('change');
          $('#share').val(data.data.share);
          $('#ajaxModel').modal('show');
      })
    });
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');
        var data = new FormData($("#submissionPartnerForm")[0]); 
        console.log(data);
        $.ajax({
          data: data,
          url: "{{ route('submissionPartner.store') }}",
          type: "POST",
          async: false, 
          cache: false, 
          contentType: false, 
          processData: false, 
          //dataType: 'json',
          success: function (data) {
              $('#submissionPartnerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
                table.draw(); 
          },
          error: function (data) {
              
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message_modal').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteSubmissionPartner', function () {
     
        var sub_participate_role_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('submissionPartner.store') }}"+'/'+sub_participate_role_id,
            success: function (data) {
                table.draw();
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                
            }
          });
        }
    });
     
  });
  
 
});
</script>
