
    <div style="margin-top:10px; margin-right: 10px;">
        <button type="button" onclick="window.location.href='{{route('employee.index')}}'" class="btn btn-info float-right" data-toggle="tooltip" title="Back to List">List of Employees</button>
    </div>
         
    <div class="card-body">
        <form id= "addEducation" method="post" class="form-horizontal form-prevent-multiple-submits" action="{{route('education.create')}}" enctype="multipart/form-data">
        @csrf
            <div class="form-body">
                    
                <h3 class="box-title">Add Education</h3>
                
                <hr class="m-t-0 m-b-40">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Name of Degree<span class="text_requried">*</span></label><br>
                                <select id="degree_name" name="degree_name[]" data-validation="required" class="form-control selectTwo">
                                   <option></option>
                                    @foreach($degrees as $degree)
                                    <option value="{{$degree->id}}" {{(old("degree_name.0")==$degree->id? "selected" : "")}}>{{$degree->degree_name}}</option>
                                    @endforeach
                                  
                                </select>
                                <br>
                                @can('hr edit record')
                                <button type="button" class="btn btn-sm btn-info"  data-toggle="modal" data-target="#eduModal"><i class="fas fa-plus"></i>
                                </button>
                                @endcan 

                               
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Institute<span class="text_requried">*</span></label>
                                
                                <input type="text" name="institute" id="institute" value="{{ old('institute') }}" class="form-control" data-validation="required" >
                            </div>
                        </div>
                    </div>
                     
                </div><!--/End Row-->

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">From</label>
                                
                                <input type="text" id="from" name="from" value="{{ old('from') }}" class="form-control" readonly>
								
								<br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">To</label>
                                
                                <input type="text" id="to" name="to" value="{{ old('to') }}" class="form-control" readonly>
                                
                                <br>
                                @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                            </div>
                        </div>
                    </div>
                     <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                               	<label class="control-label text-right">Total Marks</label>       
                                <input type="number" id="total_marks" name="total_marks" value="{{ old('total_marks') }}" class="form-control"  >
                            </div>
                        </div>
                    </div>
                      <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Marks Obtain</label>       
                                <input type="number" id="marks_obtain" name="marks_obtain" value="{{ old('marks_obtain') }}" class="form-control"  >
                            </div>
                        </div>
                    </div>
                      <!--/span-->
                    <div class="col-md-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="control-label text-right">Grade</label>       
                                <input type="number" id="grade" name="grade" value="{{ old('grade') }}" class="form-control"  >
                            </div>
                        </div>
                    </div>
                </div><!--/End Row-->

               
            </div> <!--/End Form Boday-->

            <hr>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success btn-prevent-multiple-submits"><i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Add Education</button>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
	</div> <!-- end card body -->    
    @include('cv.detail.eduModal') 

@push('scripts')
<script>
$(document).ready(function(){
    $('.fa-trash-alt').hide();
    $('#from, #to').datepicker( {
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    onClose: function(dateText, inst) { 
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        $(this).siblings('i').show();
    }
    });

    $(".fa-trash-alt").click(function (){
        if(confirm("Are you sure to clear date")){
        $(this).siblings('input').val("");
        $(this).hide();
        $(this).siblings('span').text("");
        }
    });
});

</script>
@endpush
@stack('scripts')
