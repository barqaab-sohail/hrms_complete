
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createPartner" data-toggle="modal" >Add Partner</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:15%">Partner Name</th>
          <th style="width:15%">Partner Role</th>
          <th style="width:10%">Sahre</th>
          <th style="width:10%">Authorize Person</th>
          <th style="width:10%">Designation</th>
          <th style="width:10%">Phone</th>
          <th style="width:10%">Mobile</th>
          <th style="width:10%">Email</th>
          <th style="width:5%">Edit</th>
          <th style="width:5%">Delete</th>
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
              <form id="prPartnerForm" name="prPartnerForm" class="form-horizontal">
                   
                  <input type="hidden" name="pr_partner_id" id="pr_partner_id">
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label text-right">Name of Partner<span class="text_requried">*</span></label>
                          <select  id="partner_id"   name="partner_id"  class="form-control selectTwo" data-validation="required">
                              <option value=""></option>
                              @foreach($partners as $partner)
                              <option value="{{$partner->id}}" {{(old("partner_id")==$partner->id? "selected" : "")}}>{{$partner->name}}</option>
                              @endforeach 
                          </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label text-right">Partner Role<span class="text_requried">*</span></label>
                          <select  id="pr_role_id"   name="pr_role_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($prRoles as $prRole)
                            <option value="{{$prRole->id}}" {{(old("pr_role_id")==$prRole->id? "selected" : "")}}>{{$prRole->name}}</option>
                            @endforeach 
                          </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="hideShare">
                      <div class="form-group">
                        <label class="control-label text-right">Partner Share<span class="text_requried">*</span></label>       
                        <input type="text" name="share" id="share"  value="{{ old('share') }}" class="form-control" data-validation="required length"  data-validation-length="max50">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="control-label text-right">Authorize Person<span class="text_requried">*</span></label>        
                        <input type="text" name="authorize_person"  id="authorize_person" value="{{ old('authorize_person') }}" class="form-control" data-validation="required length"  data-validation-length="max50" placeholder="Enter Authorize Person Name">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label text-right">Designation<span class="text_requried">*</span></label>
                          <input type="text" name="designation"  id="designation" value="{{ old('designation') }}" class="form-control" data-validation="required length"  data-validation-length="max50" placeholder="Enter Authorize Person Designation">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                          <label class="control-label text-right">Mobile No.<span class="text_requried">*</span></label>    
                          <input type="text" name="mobile" id="mobile" pattern="[0-9.-]{12}" title= "11 digit without dash" value="{{ old('mobile') }}" data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0345-0000000">
                      </div>
                    </div>
                    <div class="col-md-2">
                     <label class="control-label text-right">Landline No.</label>
                                
                       <input type="text" name="phone"  id="phone"  value="{{ old('phone') }}"  data-validation="length"  data-validation-length="max15" class="form-control" placeholder="0092-42-00000000">
                    </div>
                    <div class="col-md-2">
                      <label class="control-label text-right">Email</label>  
                      <input type="email" name="email" id="email" value="{{ old('emal') }}" data-validation="length"  data-validation-length="max50" class="form-control" placeholder="Enter Email Address">
                    </div>
                  </div>
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-success  btn-prevent-multiple-submits" id="saveBtn" value="create">Save changes
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

 
  $('#phone,  #mobile').keyup(function(){
      // skip for arrow keys
      if(event.which >= 37 && event.which <= 40) return;
      // format number
      $(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
      //.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
      });
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
        ajax: "{{ route('projectPartner.create') }}",
        columns: [
            {data: "partner_id", name: 'partner_id'},
            {data: "pr_role_id", name: 'pr_role_id'},
            {data: "share", name: 'share'},
            {data: "authorize_person", name: 'authorize_person'},
            {data: "designation", name: 'designation'},
            {data: "phone", name: 'phone'},
            {data: "mobile", name: 'mobile'},
            {data: "email", name: 'email'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createPartner').click(function () {
        $('#json_message_modal').html('');
        $('#prPartnerForm').trigger("reset");
        $('#pr_partner_id').val('');
        $('#partner_id').val('').trigger('change');
        $('#pr_role_id').val('').trigger('change');
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editPrPartner', function () {
      var pr_partner_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/project/projectPartner') }}" +'/' + pr_partner_id +'/edit', function (data) {
    
          $('#modelHeading').html("Edit Partner");
          $('#saveBtn').val("edit-Partner");
          $('#pr_partner_id').val(data.id);
          $('#partner_id').val(data.partner_id).trigger('change');
          $('#pr_role_id').val(data.pr_role_id).trigger('change');
          $('#authorize_person').val(data.authorize_person);
          $('#designation').val(data.designation);
          $('#share').val(data.share);
          $('#phone').val(data.phone);
          $('#mobile').val(data.mobile);
          $('#email').val(data.email);
          $('#ajaxModel').modal('show');
      })
    });

    $('#saveBtn').unbind().click(function (e) {
        $(this).attr('disabled','ture');
        //submit enalbe after 3 second
        setTimeout(function(){
            $('.btn-prevent-multiple-submits').removeAttr('disabled');
        }, 3000);

        e.preventDefault();
        $(this).html('Save'); 
        $.ajax({
          data: $('#prPartnerForm').serialize(),
          url: "{{ route('projectPartner.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#prPartnerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
               $('#json_message').html('<div id="message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
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
    
    $('body').on('click', '.deletePrPartner', function () {
     
        var pr_partner_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectPartner.store') }}"+'/'+pr_partner_id,
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
