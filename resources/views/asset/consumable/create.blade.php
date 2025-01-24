<div style="margin-top:10px; margin-right: 10px;">
    <button type="button"  id ="hideButton"  class="btn btn-success float-right">Add Consumable</button>
</div>


<div class="card-body">

    <form method="post" class="form-horizontal form-prevent-multiple-submits" id="formConsumable" enctype="multipart/form-data">
        {{csrf_field()}}
          <div class="form-body">
            
            <h3 class="box-title" id="formHeading">Consumable</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
              <input type="hidden" name="as_consumable_id" id="as_consumable_id"/>
              
                <div class="col-md-3">
                  <div class="form-group row">
                    <div class="col-md-12">
                        <label class="control-label text-right">Consumable Item<span class="text_requried">*</span></label>
                        <select name="consumable_id" id="consumable_id" class="form-control selectTwo" data-validation="required">
                        <option value=""></option>
                        @foreach($consumableItems as $item)
                        <option value="{{$item->id}}" {{(old("consumable_id")==$item->id? "selected" : "")}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group row">
                    <div class="col-md-12">
                        <label class="control-label text-right">Quantity</label>
                        <input type="text" name="consumable_qty" id="consumable_qty" value="{{ old('consumable_qty') }}"  class="form-control" placeholder="Enter Quantity">
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group row">
                    <div class="col-md-12">
                        <label class="control-label text-right">Unit</label>
                        <select name="unit_id" id="unit_id" class="form-control selectTwo">
                        <option value=""></option>
                        @foreach($units as $unit)
                        <option value="{{$unit->id}}" {{(old("unit_id")==$unit->id? "selected" : "")}}>{{$unit->name}}</option>
                        @endforeach
                    </select>
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group row">
                      <div class="col-md-12">
                          <label class="control-label text-right">Cost<span class="text_requried">*</span></label>
                          <input type="text" name="consumable_cost" id="consumable_cost" value="{{ old('consumable_cost') }}" data-validation="required" class="form-control" placeholder="Enter Consumable Cost">
                          
                      </div>
                    </div>
                </div>
                                
                <div class="col-md-3">
                  <div class="form-group row">
                    <div class="col-md-12">
                        <label class="control-label text-right">Date<span class="text_requried">*</span></label>
                        
                        <input type="text" id="consumable_date" name="consumable_date" value="{{ old('consumable_date') }}" class="form-control date_input" data-validation="required" readonly>
    
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
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
          <th>Consumable Detail</th>
          <th>Cost</th>
          <th>Quantity</th>
          <th>Unit</th>
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

       $('#formConsumable').hide();   

       $('#consumable_cost').keyup(function(event) {

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
        ajax:{url:"{{ route('asConsumable.create') }}", data: {
            assetId: $("#id").val()
        }},
        columns: [
            
            {data: "consumable_id", name: 'consumable_id'},
            {data: "consumable_cost", name: 'consumable_cost'},
            {data: "consumable_qty", name: 'consumable_qty'},
            {data: "unit_id", name: 'unit_id'},
            {data: "consumable_date", name: 'consumable_date'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
        order: [[ 1, "desc" ]]
    });

    $('#hideButton').click(function(){
            $('#formConsumable').toggle();
            $('#formConsumable').trigger("reset");
            $('#consumable_id').trigger('change');
            $('#unit_id').trigger('change');
    });

    $('body').unbind().on('click', '.editConsumable', function () {


      var as_consumable_id = $(this).data('id');

      $.get("{{ url('hrms/asConsumable') }}" +'/' + as_consumable_id +'/edit', function (data) {
          $('#formConsumable').show(); 
          $('#formHeading').html("Edit Consumable");
          $('#as_consumable_id').val(data.id);
          $('#consumable_id').val(data.consumable_id).trigger('change');
          $('#unit_id').val(data.unit_id).trigger('change');
          $('#consumable_qty').val(data.consumable_qty);
          $('#consumable_cost').val((data.consumable_cost).toLocaleString());
          $('#consumable_date').val(data.consumable_date);
      })
   });
    
      $("#formConsumable").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("asset_id", $("#id").val());
        $.ajax({
          data: formData,
          url: "{{ route('asConsumable.store') }}",
          type: "POST",
          //dataType: 'json',
           contentType: false,
           cache: false,
           processData: false,
          success: function (data) {
              if(data.error){
                $('#json_message').html('<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.error+'</strong></div>');
              }else{

                $('#formConsumable').trigger("reset");
                $('#formConsumable').toggle();
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
    
    $('body').on('click', '.deleteConsumable', function () {
     
        var as_consumable_id = $(this).data("id");

        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('asConsumable.store') }}"+'/'+as_consumable_id,
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