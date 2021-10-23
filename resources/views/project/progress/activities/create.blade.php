<div class="card-body">
	<form action="" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
            {{csrf_field()}}
            <h3 class="box-title">Add Progress Activities</h3>
            <hr class="m-t-0 m-b-40">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Name Activity<span class="text_requried">*</span></label><br>
                           	<input type="text"  name="name" value="{{ old('name') }}"  class="form-control" data-validation="required">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-md-12">
                           	<label class="control-label text-right">Percentage Weightage</label>
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