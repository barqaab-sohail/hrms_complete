<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Maintenance</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formMaintenance" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Maintenance</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
              <input type="hidden" name="as_maintenance_id" id="as_maintenance_id"/>
                <div class="col-md-6">
                  <div class="form-group row">
                    <div class="col-md-12">
                        <label class="control-label text-right">Maintenance Detail<span class="text_requried">*</span></label>
                        <input type="text" name="maintenance_detail" id="maintenance_detail" value="{{ old('maintenance_detail') }}" data-validation="required" class="form-control" placeholder="Enter Maintenance Detail">
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                      <div class="col-md-12">
                          <label class="control-label text-right">Maintenance Cost<span class="text_requried">*</span></label>
                          <input type="text" name="maintenance_cost" id="maintenance_cost" value="{{ old('maintenance_cost') }}" data-validation="required" class="form-control" placeholder="Enter Maintenance Cost">
                          
                      </div>
                    </div>
                </div>
                                
                <div class="col-md-3">
                  <div class="form-group row">
                    <div class="col-md-12">
                        <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                        
                        <input type="text" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date') }}" class="form-control date_input" data-validation="required" readonly>
    
                        <br>
                        @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                    </div>
                  </div>
                </div>
            </div>
                                               
          </div> <!-- end form-body -->
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
          <th>Maintenance Detail</th>
          <th>Cost</th>
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

       $('#formMaintenance').hide();   

       $('#maintenance_cost').keyup(function(event) {

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
        ajax: "{{ route('asMaintenance.create') }}",
        columns: [
            
            {data: "maintenance_detail", name: 'maintenance_detail'},
            {data: "maintenance_cost", name: 'maintenance_cost'},
            {data: "maintenance_date", name: 'maintenance_date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#formMaintenance').toggle();
            $('#formMaintenance').trigger("reset");
          
    });

    $('body').unbind().on('click', '.editMaintenance', function () {


      var as_maintenance_id = $(this).data('id');

      $.get("{{ url('hrms/asMaintenance') }}" +'/' + as_maintenance_id +'/edit', function (data) {
          $('#formMaintenance').show(); 
          $('#formHeading').html("Edit Maintenance");
          $('#as_maintenance_id').val(data.id);
          $('#maintenance_detail').val(data.maintenance_detail);
          $('#maintenance_cost').val((data.maintenance_cost).toLocaleString());
          $('#maintenance_date').val(data.maintenance_date);
      })
   });
    
      $("#formMaintenance").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
          data: formData,
          url: "{{ route('asMaintenance.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{

                $('#formMaintenance').trigger("reset");
                $('#formMaintenance').toggle();
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.success+'</strong></div>');  

                table.draw();
              }
        
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
    
    $('body').on('click', '.deleteMaintenance', function () {
     
        var as_maintenance_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('asMaintenance.store') }}"+'/'+as_maintenance_id,
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