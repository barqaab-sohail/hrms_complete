
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('office.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Offices</button>
    </div>

         
    <div class="card-body">
        <form id= "formSubmission" method="post" action="{{route('office.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label text-right">Office Name<span class="text_requried">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label text-right">Establish Date</label><br>
                        <input type="text" id="submission_date" name="submission_date" value="{{ old('submission_date') }}" class="form-control date_input" readonly>     
                        <br>
                        <i class="fas fa-trash-alt text_requried"></i>
                    </div>
                </div>
            
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="control-label text-right">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control"  placeholder="Please enter Office Address"> 
                    </div>
                </div>
            </div>
            
            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-success btn-prevent-multiple-submits" id="saveBtn" value="create">Save
             </button>
            </div>
        </form>
	</div> <!-- end card body -->    


<script>
    if("{{$data->subDescription->sub_evaluation_type_id??''}}"!=1){
        $('.QCB').hide();
    }

    $("#sub_evaluation_type_id").change(function(){

        if($(this).val() == 1){
            $('.QCB').show();
        }else{
            $('#technical_weightage').val('');
            $('#financial_weightage').val('');
            $('.QCB').hide();
        }

    });


    $('#sub_type_id').change(function(){
        var subTypeId = $(this).val();
        if(subTypeId == 3){
            $('.hide').show();
        }else{
            $('.hide').hide();
        }
        

        $.get("{{ url('hrms/submission/submissionNo') }}"+"/"+subTypeId, function (data) {
                    $("#submission_no").val(data);
            
        });
    });
</script>	  



