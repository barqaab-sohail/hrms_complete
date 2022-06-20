
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubContact" data-toggle="modal" >Add Contact</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:15%">Name</th>
          <th style="width:15%">Designation</th>
          <th style="width:20%">Address</th>
          <th style="width:10%">Phone</th>
          <th style="width:10%">Fax</th>
          <th style="width:10%">mobile</th>
          <th style="width:10%">email</th>
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
              <form id="subContactForm" name="subContactForm" class="form-horizontal">
                   
                  <input type="hidden" name="sub_contact_id" id="sub_contact_id">
                  <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" name="name"  id="name" value="{{old('name')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="control-label">Designation<span class="text_requried">*</span></label>
                          <input type="text" name="designation"  id="designation" value="{{old('designation')}}" class="form-control" data-validation="required" >
                        </div>
                    </div>
                    <div class="col-md-6" id="hideShare">
                      <div class="form-group">
                          <label class="control-label">Address<span class="text_requried">*</span></label>
                          <input type="text" name="address"  id="address" value="{{old('address')}}" class="form-control" data-validation="required">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Phone</label>
                          <input type="text" name="phone"  id="phone" value="{{old('phone')}}" class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Fax</label>
                          <input type="text" name="fax"  id="fax" value="{{old('fax')}}" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Mobile</label>
                          <input type="text" name="mobile"  id="mobile" value="{{old('mobile')}}" class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Email</label>
                          <input type="email" name="email"  id="email" value="{{old('email')}}" class="form-control" >
                      </div>
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
    max-width: 80%;
    display: flex;
}
</style>

<script type="text/javascript">


$(document).ready(function() {

 
  $('#phone, #fax, #mobile').keyup(function(){
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
        ajax: "{{ route('submissionContact.create') }}",
        columns: [
            {data: "name", name: 'name'},
            {data: "designation", name: 'designation'},
            {data: "address", name: 'address'},
            {data: "phone", name: 'phone'},
            {data: "fax", name: 'fax'},
            {data: "mobile", name: 'mobile'},
            {data: "email", name: 'email'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createSubContact').click(function () {
        $('#json_message_modal').html('');
        $('#subContactForm').trigger("reset");
        $('#sub_contact_id').val('');
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editSubmissionContact', function () {
      var sub_contact_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionContact') }}" +'/' + sub_contact_id +'/edit', function (data) {
    
          $('#modelHeading').html("Edit Contact");
          $('#saveBtn').val("edit-Contact");
          $('#sub_contact_id').val(data.id);
          $('#name').val(data.name);
          $('#designation').val(data.designation);
          $('#address').val(data.address);
          $('#phone').val(data.phone);
          $('#fax').val(data.fax);
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
          data: $('#subContactForm').serialize(),
          url: "{{ route('submissionContact.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#subContactForm').trigger("reset");
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
    
    $('body').on('click', '.deleteSubmissionContact', function () {
     
        var sub_contact_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('submissionContact.store') }}"+'/'+sub_contact_id,
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
