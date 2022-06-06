
<div class="card-body">
  <button type="button" class="btn btn-success float-right"  id ="createConsultancyCost" data-toggle="modal" >Add Consultancy Cost</button>
  <br>
  <table class="table table-bordered data-table">
    <thead>
      <tr>
          <th>Cost Type</th>
          <th>Total Cost</th>
          <th>Man-Month Cost</th>
          <th>Direct Cost</th>
          <th>Contingency</th>
          <th>Sales Tax</th>
          <th>Remarks</th>
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
                <form id="consultancyCostForm" name="consultancyCostForm" action="{{route('projectConsultancyCost.store')}}"class="form-horizontal">
                   
                   <input type="hidden" name="cost_id" id="cost_id">
                 
                    <div class="form-group">
                        <label class="control-label text-right">Cost Type<span class="text_requried">*</span></label>
                        <select  id="pr_cost_type_id"   name="pr_cost_type_id"  class="form-control selectTwo" data-validation="required">
                            <option value=""></option>
                            @foreach($prCostTypes as $prCostType)
                            <option value="{{$prCostType->id}}" {{(old("pr_cost_type_id")==$prCostType->id? "selected" : "")}}>{{$prCostType->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Total Cost including Tax</label>
                        <input type="text" name="total_cost" id="total_cost"  value="{{old('total_cost')}}" class="form-control" data-validation="required" >
                    </div>

                    <div class="form-group">
                      <label class="control-label">Man-Month Cost</label>
                      <input type="text" name="man_month_cost" id="man_month_cost" value="{{old('man_month_cost')}}" class="form-control" >
                    </div>

                    <div class="form-group">
                      <label class="control-label">Direct Cost</label>
                      <input type="text" name="direct_cost"  id="direct_cost" value="{{old('direct_cost')}}" class="form-control" >
                    </div>

                    <div class="form-group">
                      <label class="control-label">Contingency</label>
                      <input type="text" name="contingency"  id="contingency" value="{{old('contingency')}}" class="form-control" >
                    </div>

                    <div class="form-group">
                      <label class="control-label">Sales Tax</label>
                      <input type="text" name="sales_tax" id="sales_tax" value="{{old('sales_tax')}}" class="form-control" >
                    </div>

                    <div class="form-group">
                      <label class="control-label text-right">Remarks</label>
                      <input type="text" name="remarks" id="remarks" value="{{ old('remarks') }}"  class="form-control">
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

<script type="text/javascript">
$(document).ready(function() {
  
  //only number value entered
    $('#total_cost, #man_month_cost, #direct_cost, #contingency, #sales_tax').on('change, keyup', function() {
    var currentInput = $(this).val();
    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
    $(this).val(fixedInput);
    });

    //Enter Comma after three digit
    $('#total_cost, #man_month_cost, #direct_cost, #contingency, #sales_tax').keyup(function(event) {

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




  $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('projectConsultancyCost.create') }}",
        columns: [
            {data: "pr_cost_type_id", name: 'pr_cost_type_id'},
            {data: "total_cost", name: 'total_cost'},
            {data: "man_month_cost", name: 'man_month_cost'},
            {data: "direct_cost", name: 'direct_cost'},
            {data: "contingency", name: 'contingency'},
            {data: "sales_tax", name: 'sales_tax'},
            {data: "remarks", name: 'remarks'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},

        ],
     
        order: [[ 0, "desc" ]]
    });

    $('#createConsultancyCost').click(function () {
        $('#json_message_modal').html('');
        $('#saveBtn').val("Create Cost");
        $('#pr_cost_type_id').val('');
        $('#pr_cost_type_id').trigger('change');
        $('#cost_id').val('');
        $('#consultancyCostForm').trigger("reset");
        $('#modelHeading').html("Create New Cost");
        $('#ajaxModel').modal('show');
    });
    $('body').unbind().on('click', '.editCost', function () {
      var cost_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/projectConsultancyCost') }}" +'/' + cost_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Consultancy Cost");
          $('#saveBtn').val("edit-Cost");
          $('#ajaxModel').modal('show');
          $('#cost_id').val(data.id);
          $('#pr_cost_type_id').val(data.pr_cost_type_id);
          $('#pr_cost_type_id').trigger('change');
          var totalCost = (data.total_cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#total_cost').val(totalCost);
          var manMonthCost = (data.man_month_cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#man_month_cost').val(manMonthCost);
          var directCost = (data.direct_cost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#direct_cost').val(directCost);
          var contingency = (data.contingency).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#contingency').val(contingency);
          var salesTax = (data.sales_tax).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#sales_tax').val(salesTax);
          $('#remarks').val(data.remarks);
              console.log(data);
         
      })
   });
    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save');
         
        $.ajax({
          data: $('#consultancyCostForm').serialize(),
          url: "{{ route('projectConsultancyCost.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#consultancyCostForm').trigger("reset");
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
    
    $('body').on('click', '.deleteCost', function () {
     
        var cost_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('projectConsultancyCost.store') }}"+'/'+cost_id,
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
