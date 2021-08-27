<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Ownership</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formOwnership" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Ownership Detail</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
              <input type="hidden" name="as_ownership_id" id="as_ownership_id"/>
              <div class="col-md-8">
                <div class="form-group row">
                  <div class="col-md-12">
                    <label class="control-label text-right">Ownership<span class="text_requried">*</span></label>         
                    <select  name="client_id" id="ownership" class="form-control selectTwo" data-validation="required">
                      <option value=""></option>
                      @foreach($asOwnerships as $asOwnership)
                      <option value="{{$asOwnership->id}}" {{(old("client_id")==$asOwnership->id? "selected" : "")}}>{{$asOwnership->name}}</option>
                      @endforeach     
                    </select>
                  </div>
                </div>
              </div>
                 
              <div class="col-md-3">
                <div class="form-group row">
                  <div class="col-md-12">
                      <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                      
                      <input type="text" id="date" name="date" value="{{ old('date') }}" class="form-control date_input" data-validation="required" readonly>
  
                      <br>
                      @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                  </div>
                </div>
              </div>
            </div> <!-- End Row -->

            <div class="row">
              
              <div class="col-md-11">
                <div class="form-group row">
                  <div class="col-md-12">
                    <label class="control-label text-right">Project</label>         
                    <select  name="pr_detail_id" id="pr_detail_id" class="form-control selectTwo">
                      <option value=""></option>
                      @foreach($projects as $project)
                      <option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->name}}</option>
                      @endforeach     
                    </select>
                  </div>
                </div>
              </div>
                 
            </div> <!-- End Row -->

          </div>  <!-- End form-body -->
         <hr>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                       
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" id="saveBtn" style="font-size:18px"></i>Save</button>
                                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
<br>
<table class="table table-bordered data-table" width=100%>
    <thead>
      <tr>
          <th>Ownership</th>
          <th>Date</th>
          <th>Edit</th>
          <th>Delete</th>
      </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>

   
</div>
        
<script type="text/javascript">
$(document).ready(function(){

       // formFunctions();

       $('#formOwnership').hide();       

     

    });//end document ready

      //start function
$(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('asOwnership.create') }}",
        columns: [
            
            {data: "ownership", name: 'ownership'},
            {data: "date", name: 'date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });
    
    $('#hideButton').click(function(){
            $('#formOwnership').toggle();
            $('#formOwnership').trigger("reset");
            $('.selectTwo').val('').select2('val', 'All');
            
    });

    $('body').unbind().on('click', '.editOwnership', function () {
      var as_ownership_id = $(this).data('id');

      $.get("{{ url('hrms/asOwnership') }}" +'/' + as_ownership_id +'/edit', function (data) {
          $('#formOwnership').show(); 
        
          $('#formHeading').html("Edit Ownership");
          $('#ownership').val(data.client_id);
          $('#pr_detail_id').val(data.pr_detail_id);
          $('#pr_detail_id').trigger('change');
          $('#ownership').trigger('change');
          $('#date').val(data.date);
          $('#as_ownership_id').val(data.id);
          

      
      })
   });
    
      $("#formOwnership").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          data: formData,
          url: "{{ route('asOwnership.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
     
              $('#formOwnership').trigger("reset");
              $('.selectTwo').val('').select2('val', 'All');
              $('#formOwnership').toggle();
              $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');  

              table.draw();
        
          },
          error: function (data) {
              var errorMassage = '';
              $.each(data.responseJSON.errors, function (key, value){
                errorMassage += value + '<br>';  
                });
                 $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');

              $('#saveBtn').html('Save Changes');
          }
      });
    });
    
    $('body').on('click', '.deleteOwnership', function () {
     
        var as_ownership_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('asOwnership.store') }}"+'/'+as_ownership_id,
            success: function (data) {
                table.draw();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');
                if(data.error){
                  $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');    
                }
  
            },
            error: function (data) {
                
            }
          });
        }
    });
     
  });// end function

      
            
</script>