
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubCompetitor" data-toggle="modal" >Add Contact</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:15%">Name</th>
          <th style="width:15%">Technical Score</th>
          <th style="width:20%">Financial Score</th>
          <th style="width:10%">Technical & Financial Score</th>
          <th style="width:10%">Rank</th>
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
              <form id="subCompetitorForm" name="subCompetitorForm" class="form-horizontal">
                   
                  <input type="hidden" name="sub_competitor_id" id="sub_competitor_id">
                  <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" name="name"  id="name" value="{{old('name')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Technical Opening Date<span class="text_requried">*</span></label>
                          <input type="text" name="technical_date"  id="technical_date" value="{{old('technical_date')}}" class="form-control date_input" readonly data-validation="required" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Technical Score<span class="text_requried">*</span></label>
                          <input type="text" name="technical_score"  id="technical_score" value="{{old('technical_score')}}" class="form-control" data-validation="required" >
                        </div>
                    </div>
                    <div class="col-md-10 financial" id='financial_1'>
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                              <label class="control-label">Currency</label>
                                <select  id="currency_id"   name="currency_id[]"  class="form-control" data-validation="required">
                                <option value=""></option>
                                  @foreach ($currencies as $currency)
                                  <option value="{{$currency->id}}">{{$currency->name}}</option>
                                  @endforeach
                                </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                              <label class="control-label">Conversion Rate</label>
                              <input type="text" name="conversion_rate[]"  id="conversion_rate" value="{{old('conversion_rate')}}" class="form-control" data-validation="required">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                              <label class="control-label">Quoted Price</label>
                              <input type="text" name="quoted_price[]"  id="quoted_price" value="{{old('quoted_price')}}" class="form-control" data-validation="required">
                          </div>
                        </div>
                        <div class="col-md-1">
                          <br>
                            <div class="float-right">
                            <button type="button" name="add" id="add" class="btn btn-success add" >+</button>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                          <label class="control-label">Technical & Financial Score </label>
                          <input type="text" name="technical_financial_score"  id="technical_financial_score" value="{{old('technical_financial_score')}}" class="form-control" >
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
  $('select:not(.selectTwo)').chosen();
  //Dynamic add Financial
    
    // Add new element
     $("#add").click(function(){
      
      // Finding total number of elements added
      var total_element = $(".financial").length;
      
      // last <div> with element class id
      var lastid = $(".financial:last").attr("id");
      var split_id = lastid.split("_");
      var nextindex = Number(split_id[1]) + 1;
      var max = 5;
      // Check total number elements
      if(total_element < max ){
       //Clone Financial div and copy 
      $('.financial').find('select').chosen('destroy');
        var clone = $("#financial_1").clone();
        clone.prop('id','financial_'+nextindex).find('input:text').val('');
        clone.find("#add").html('X').prop("class", "btn btn-danger remove remove_financial");
        clone.insertAfter("div.financial:last");
      $('.financial').find('select').chosen();
       
      }
     
    });
     // Remove element
     $(document).on("click", '.remove_financial', function(){
     $(this).closest(".financial").remove();
    }); 


 
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
        ajax: "{{ route('submissionCompetitor.create') }}",
        columns: [
            {data: "name", name: 'name'},
            {data: "technical_score", name: 'technical_score'},
            {data: "financial_cost", name: 'financial_cost'},
            {data: "technical_financial_score", name: 'technical_financial_score'},
            {data: "rank", name: 'rank'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
     
        order: [[ 0, "desc" ]],
    });
    

    $('#createSubCompetitor').click(function () {
        $('#json_message_modal').html('');
        $('#subCompetitorForm').trigger("reset");
        $('#sub_competitor_id').val('');
        $('#currency_id').val('');
        $('#currency_id').trigger('change');
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editSubmissionCompetitor', function () {
      var sub_competitor_id = $(this).data('id');
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionCompetitor') }}" +'/' + sub_competitor_id +'/edit', function (data) {
          console.log(data);
          $('#modelHeading').html("Edit Competitor");
          $('#saveBtn').val("edit-Competitor");
          $('#sub_competitor_id').val(data.id);
          $('#name').val(data.name);
          $('#technical_score').val(data.sub_technical_score.technical_score);
          $('#currency_id').val(data.sub_financial_score[0].currency_id);
          $('#currency_id').trigger('change');
          $('#conversion_rate').val(data.sub_financial_score[0].conversion_rate);
          $('#quoted_price').val(data.sub_financial_score[0].quoted_price);
          $('#technical_financial_score').val(data.sub_result.technical_financial_score);
          $('#rank').val(data.sub_result.rank);
          $('#ajaxModel').modal('show');
      })
    });

    $('#saveBtn').unbind().click(function (e) {
        e.preventDefault();
        $(this).html('Save'); 
        $.ajax({
          data: $('#subCompetitorForm').serialize(),
          url: "{{ route('submissionCompetitor.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#subCompetitorForm').trigger("reset");
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
    
    $('body').on('click', '.deleteSubmissionCompetitor', function () {
     
        var sub_competitor_id = $(this).data("id");
        var con = confirm("Are You sure want to delete !");
        if(con){
          $.ajax({
            type: "DELETE",
            url: "{{ route('submissionCompetitor.store') }}"+'/'+sub_competitor_id,
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
