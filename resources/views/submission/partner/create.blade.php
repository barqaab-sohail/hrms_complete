
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubmissionPartner" data-toggle="modal" >Add Partner</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:40%">Name of Firm</th>
          <th style="width:10%">Role</th>
          <th style="width:10%">% Share</th>
          <th style="width:10%">MM Cost</th>
          <th style="width:10%">Direct Cost</th>
          <th style="width:10%">Total Cost</th>
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
              <form id="submissionPartnerForm" name="submissionPartnerForm" class="form-horizontal">
                   
                  <input type="hidden" name="sub_participate_role_id" id="sub_participate_role_id">
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Name of Partner Firm<span class="text_requried">*</span></label>
                            <select  id="partner_id"   name="partner_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                              @foreach ($partners as $partner)
                              <option value="{{$partner->id}}">{{$partner->name}}</option>
                              @endforeach
                            </select>
                            <button type="button" class="btn btn-sm btn-success"  data-toggle="modal" data-target="#partnerModal"><i class="fas fa-plus"></i>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                          <label class="control-label">Firm Role<span class="text_requried">*</span></label>
                          <select  id="pr_role_id"   name="pr_role_id"  class="form-control selectTwo" data-validation="required">
                           <option value=""></option>
                              @foreach ($prRoles as $role)
                              <option value="{{$role->id}}">{{$role->name}}</option>
                              @endforeach
                          </select>  
                        </div>
                    </div>
                    <div class="col-md-2" id="hideShare">
                      <div class="form-group">
                          <label class="control-label">% Share</label>
                          <input type="text" name="share"  id="share" value="{{old('share')}}" class="form-control prc_1" data-validation="required" value="">
                      </div>
                    </div>
                  </div>
                  @if($data->sub_type_id=='RFP')
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                          <label class="control-label">Manmonth Cost</label>
                          <input type="text" name="mm_cost"  id="mm_cost" value="{{old('mm_cost')}}" class="form-control prc_1" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                          <label class="control-label">Direct Cost</label>
                          <input type="text" name="direct_cost"  id="direct_cost" value="{{old('direct_cost')}}" class="form-control prc_1">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                          <label class="control-label">Total Cost<span class="text_requried">*</span></label>
                          <input type="text" name="total_cost"  id="total_cost" value="{{old('total_cost')}}" class="form-control" data-validation="required">
                      </div>
                    </div>

                  </div>
                  @endif
                  <div class="col-sm-offset-2 col-sm-10">
                   <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save changes
                   </button>
                  </div>
              </form>
            </div>
        </div>
    </div>
</div>
 @include('submission.partner.partnerModal')
<style>
  .modal-dialog {
    max-width: 80%;
    display: flex;
}
</style>

<script type="text/javascript">


$(document).ready(function() {

 
  $('.prc_1, #total_cost').keyup(function(){
         // skip for arrow keys
      if(event.which >= 37 && event.which <= 40) return;

      // format number
      $(this).val(function(index, value) {
        return value
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        ;
      });

    });

    //automatic total
    $(".form-group").on("input", ".prc_1", function() {
        var sum = 0;
        $(".form-group .prc_1").each(function(){
            var inputVal = $(this).val();
            inputVal=inputVal.replace(/\,/g,'') // remove comma
            if ($.isNumeric(inputVal)){
            sum += parseFloat(inputVal);
            }
        });
        sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //add comma
        $("#total_cost").val(sum);
    });



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
        ajax: "{{ route('submissionPartner.create') }}",
        columns: [
            {data: "partner_name", name: 'partner_name'},
            {data: "role_name", name: 'role_name'},
            {data: "share", name: 'share'},
            {data: "mm_cost", name: 'mm_cost'},
            {data: "direct_cost", name: 'direct_cost'},
            {data: "total_cost", name: 'total_cost'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createSubmissionPartner').click(function () {
        $('#json_message_modal').html('');
        $('#submissionPartnerForm').trigger("reset");
        $('#sub_participate_role_id').val('');
        $('#partner_id').trigger('change');
        $('#pr_role_id').trigger('change');
        $('#hideShare').hide();
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editSubmissionPartner', function () {
      var sub_participate_role_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionPartner') }}" +'/' + sub_participate_role_id +'/edit', function (data) {
    
          $('#modelHeading').html("Edit Partner");
          $('#saveBtn').val("edit-Partner");
          $('#sub_participate_role_id').val(data.id);
          $('#partner_id').val(data.partner_id);
          $('#partner_id').trigger('change');
          $('#pr_role_id').val(data.pr_role_id);
          $('#pr_role_id').trigger('change');
          $('#share').val(data.share);
          if(data.sub_cost != null){
            var mm_cost = (data.sub_cost.mm_cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('#mm_cost').val(mm_cost);         
            var direct_cost = (data.sub_cost.direct_cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('#direct_cost').val(direct_cost);

            var total_cost = (data.sub_cost.total_cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('#total_cost').val(total_cost);
          }
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
          data: $('#submissionPartnerForm').serialize(),
          url: "{{ route('submissionPartner.store') }}",
          type: "POST",
          dataType: 'json',
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
