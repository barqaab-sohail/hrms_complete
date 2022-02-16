<div class="card-body">
	<form action="{{route('projectPosition.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
            {{csrf_field()}}
            <h3 class="box-title">Add Positions</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Position Name<span class="text_requried">*</span></label><br>

                           	<input type="text"  name="name" value="{{ old('name') }}"  class="form-control" data-validation="required">
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Position Type<span class="text_requried">*</span></label><br>
                            <select    name="pr_position_type_id"  class="form-control selectTwo" data-validation="required">
                                    <option value=""></option>
                                    @foreach($positionTypes as $positionType)
                                    <option value="{{$positionType->id}}" {{(old("pr_position_type_id")==$positionType->id? "selected" : "")}}>{{$positionType->name}}</option>
                                    @endforeach 
                            </select>
                            
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Man-Month</label>
                           	<input type="number" step="0.001" id="total_mm"  name="total_mm" value="{{ old('total_mm') }}"  class="form-control" data-validation-allowing="range[0.001;6000.00],float"> 
                        </div>
                    </div>
                </div>
                  <!--/span-->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<br>
                           	<button type="submit" id="submitEditUserLogin"  class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Save</button>
                        </div>
                    </div>
                </div>
            </div><!--/End Row-->
    </form>
    <div class="row">
        <div class="col-md-12 table-container">
       
        
        </div>
    </div>           
</div>

<script>
$(document).ready(function (){
    refreshTable("{{route('projectPosition.table')}}");


    $('#total_mm').on('change',function(){

        if($(this).val() !=''){
        $(this).attr('data-validation','number');
        }else {
             $(this).removeAttr('data-validation');
        }
    });

     //submit function
      $("form").submit(function(e) { 
      e.preventDefault();
      var url = $(this).attr('action');
            $('.fa-spinner').show(); 
      submitForm(this, url,1);
      refreshTable("{{route('projectPosition.table')}}",1000);
    });

});

</script>