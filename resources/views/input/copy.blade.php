<div class="modal fade" id="copyModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
              <div id="json_message_modal2" align="left"><strong></strong><i hidden class="fas fa-times float-right"></i> </div>
                <form id="copyInputForm" name="copyInputForm" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label text-right">Copy From<span class="text_requried">*</span></label><br>
                          <select  name="copyFrom"  id="copyFrom" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach ($inputMonths as $month)
                              <option value="{{$month->id}}" {{(old("copyFrom")==$month->id? "selected" : "")}}>{{$month->month}} {{$month->year}}</option>
                              @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label text-right">Copy To<span class="text_requried">*</span></label><br>
                          <select  name="copyTo"  id="copyTo" class="form-control" data-validation="required">
                              <option value=""></option>
                              @foreach ($inputMonths as $month)
                              <option value="{{$month->id}}" {{(old("copyTo")==$month->id? "selected" : "")}}>{{$month->month}} {{$month->year}}</option>
                              @endforeach
                            </select>
                    </div>
                     
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-success" id="copyBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

   $('#copyFrom, #copyTo').select2({       
        width: "100%",
        theme: "classic"
    });

});

</script>