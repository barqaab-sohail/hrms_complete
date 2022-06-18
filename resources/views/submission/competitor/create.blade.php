
<div class="card-body">
  
  <button type="button" class="btn btn-success float-right"  id ="createSubCompetitor" data-toggle="modal" >Add Competitor</button>
  <br>
  <table class="table data-table">
    <thead>
      <tr>
          <th style="width:15%">Name</th>
          <th style="width:15%">Technical Number</th>
          @if($data->subDescription->sub_evaluation_type_id==1)
          <th style="width:15%">Technical Score</th>
          @endif
          <th style="width:15%">Total Price</th>
          @if($data->subDescription->sub_evaluation_type_id==1)
          <th style="width:20%">Financial Score</th>
          <th style="width:10%">Technical & Financial Score</th>
          @endif
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
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" name="name"  id="name" value="{{old('name')}}" class="form-control" data-validation="required">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Technical Number<span class="text_requried">*</span></label>
                          <input type="text" name="technical_number"  id="technical_number" value="{{old('technical_number')}}" class="form-control" data-validation="required" >
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group">
                          <label class="control-label">Total Cost<span class="text_requried">*</span></label>
                          <input type="text" name="total_price"  id="total_price" value="{{old('total_price')}}" class="form-control prc_1" data-validation="required" >
                        </div>
                    </div>
                    <div class="col-md-12 financial" id='financial_1'>
                      <div class="row">
                         <input type="hidden" name="sub_multi_currency_id[]">
                        <div class="col-md-2 conversion">
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
                        <div class="col-md-2 conversion">
                          <div class="form-group conversion_date">
                              <label class="control-label">Conversion Date</label>
                              <input type="text" id="conversion_date" name="conversion_date[]"  value="{{old('conversion_date')}}" class="form-control date_input" readonly data-validation="required">
                          </div>
                        </div>
                        <div class="col-md-2 conversion">
                          <div class="form-group">
                              <label class="control-label">Conversion Rate</label>
                              <input type="text" id="conversion_rate" name="conversion_rate[]" value="{{old('conversion_rate')}}" class="form-control" data-validation="required">
                          </div>
                        </div>
                        <div class="col-md-2 conversion">
                          <div class="form-group">
                              <label class="control-label">Financial Cost</label>
                              <input type="text" id="currency_price" name="currency_price[]" value="{{old('currency_price')}}" class="form-control prc_1" data-validation="required">
                          </div>
                        </div>
                        <div class="col-md-1 conversion">
                          <br>
                            <div class="float-right">
                              <button type="button" name="add" id="add" class="btn btn-success add" >+</button>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" name="multi_currency" id="multi_currency">
                          <label class="form-check-label" for="multi_currency">
                          Multi Currency
                        </label>
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
    max-width: 80%;
    display: flex;
}
</style>

<script type="text/javascript">


$(document).ready(function() {

  $('.conversion').hide();
  $('#multi_currency').click(function(){
      if($(this).is(':checked')){
        $(this).val('checked');
        $(this).prop('checked', true);
        $('.conversion').show();
        $("#currency_id").val('');
        $('#currency_id').trigger("chosen:updated");
      }else{
        $(this).prop('checked', false);
        $('.conversion').find('input:text').val(''); 
        $('select').val('');
        $('select').trigger("chosen:updated");
        $('.conversion').hide();
        $(this).val('');
      }
  });

 

   
  $('.prc_1').keyup(function(){
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


  $('select:not(.selectTwo)').chosen({width: "100%"});
  //Dynamic add Financial
    
    // Add new element
     $("#add").unbind().click(function(){
      
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
        clone.find(".conversion_date").hide();
        clone.find('input[name="sub_multi_currency_id[]"]').val('');
      $('.financial').find('select').chosen(); 
      }
     
    });
     // Remove element
     $(document).on("click", '.remove_financial', function(){
     $(this).closest(".financial").remove();
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
            {data: "technical_number", name: 'technical_number'},
            @if($data->subDescription->sub_evaluation_type_id==1)
            {data: "technical_score", name: 'technical_score'},
            @endif
            {data: "total_price", name: 'total_price'},
            @if($data->subDescription->sub_evaluation_type_id==1)
            {data: "financial_score", name: 'financial_score'},
            {data: "technical_financial_score", name: 'technical_financial_score'},
            @endif
            {data: "rank", name: 'rank'},
            {data: 'Edit', name: 'Edit', orderable: false, searchable: false},
            {data: 'Delete', name: 'Delete', orderable: false, searchable: false},
        ],
      order: [],
    });
    

    $('#createSubCompetitor').unbind().click(function () {
        $('input[name="sub_multi_currency_id[]"]').eq(0).val('');
        $('input[name="sub_multi_currency_id[]"]').eq(1).val('');
        $('input[name="sub_multi_currency_id[]"]').eq(2).val('');
        $('input[name="sub_multi_currency_id[]"]').eq(3).val('');
        $('input[name="sub_multi_currency_id[]"]').eq(4).val('');
        $("[id^=financial_2]").remove();
        $("[id^=financial_3]").remove();
        $("[id^=financial_4]").remove();
        $("[id^=financial_5]").remove();
        $('.conversion').hide();
        $('#json_message_modal').html('');
        $('#subCompetitorForm').trigger("reset");
        $('#sub_competitor_id').val('');
        $('#currency_id').val('');
        $('#currency_id').trigger("chosen:updated");
        $('#ajaxModel').modal('show');
    });



    $('body').unbind().on('click', '.editSubmissionCompetitor', function () {
      var sub_competitor_id = $(this).data('id');
      $(".remove_financial").trigger('click');
      //$('#multi_currency').prop('checked', false);
      $('.conversion').hide();
      $('#json_message_modal').html('');
      $.get("{{ url('hrms/submissionCompetitor') }}" +'/' + sub_competitor_id +'/edit', function (data) {
        
          $('#modelHeading').html("Edit Competitor");
          $('#saveBtn').val("edit-Competitor");
          $('#sub_competitor_id').val(data.id);
          $('#name').val(data.name);
          if(data.sub_technical_number){
          $('#technical_number').val(data.sub_technical_number.technical_number);
          }
          if(data.sub_financial_cost){

          $('#currency_id').val(data.sub_financial_cost.currency_id);
          $('#currency_id').trigger('change');
          $('#conversion_rate').val(data.sub_financial_cost.conversion_rate);
          var totalPrice = (data.sub_financial_cost.total_price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          $('#total_price').val(totalPrice);
          }

          if(data.sub_multi_currency.length>0){
            
            $("#multi_currency").prop('checked', true);
            $('.conversion').show();
              $.each(data.sub_multi_currency, function(index, value) {
                if(index !=0){
                  $("#add").trigger('click');
                }
                
                $('select:not(.selectTwo)').chosen('destroy');
                $('select:not(.selectTwo)').chosen({width: "100%"});
                $('input[name="sub_multi_currency_id[]"]').eq(index).val(value.id);
                $('select[name="currency_id[]"]').eq(index).val(value.currency_id);
                $('select[name="currency_id[]"]').eq(index).trigger("chosen:updated");
                $('input[name="conversion_date[]"]').eq(index).val(value.conversion_date);
                $('input[name="conversion_rate[]"]').eq(index).val(value.conversion_rate);
                var currencyPrice = (value.currency_price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $('input[name="currency_price[]"]').eq(index).val(currencyPrice);
              });
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
          data: $('#subCompetitorForm').serialize(),
          url: "{{ route('submissionCompetitor.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#subCompetitorForm').trigger("reset");
              $('#ajaxModel').modal('hide');
                table.draw(); 
             $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
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
