<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
				<div class="col-lg-1">
				</div>

		        <div class="col-lg-10">
		            <div style="margin-top:10px; margin-right: 10px;">                   
		            </div>
		            <div class="card-body">
		                <form id= "hrMonthlyReportProject"  action="{{route('hrMonthlyReportProject.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                    <h3 class="box-title">Add Projects</h3>
		                    <hr class="m-t-0 m-b-40">
	                            <div class="row">
	                            	<div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Month<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="hr_monthly_report_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($hrMonthlyReports as $hrMonthlyReport)
													<option value="{{$hrMonthlyReport->id}}" {{(old("hr_monthly_report_id")==$hrMonthlyReport->id? "selected" : "")}}>{{$hrMonthlyReport->month}} - {{$hrMonthlyReport->year}}</option>
                                                    @endforeach
                                                  
                                                </select>
		                                    </div>
		                                </div>
		                            </div>
		                            <!--/span-->
		                            <div class="col-md-6">
	                                    <div class="form-group row"> 
	                                        <div class="col-md-12">
	                                        <label class="control-label text-right">Projects</label>
	                                            <select  name="pr_detail_id"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($projects as $project)
													<option value="{{$project->id}}" {{(old("pr_detail_id")==$project->id? "selected" : "")}}>{{$project->project_no}} - {{$project->name}}</option>
                                                    @endforeach
                                                  
                                                </select>
	                                        </div>
	                                    </div>
	                            	</div>   
		                            <!--/span-->
		                            <div class="col-md-1">
	                                    <div class="form-group row">
	                                    	<div class="col-md-12">
	                                        <label class="control-label text-right">Status</label>
	                                        
	                                        	<select  name="is_lock"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    <option value="0">UnLock</option>
													<option value="1">Lock</option>  
                                                </select>
	                                        </div>
	                                    </div>
	                            	</div>   
		                            <!--/span-->
		                            <div class="col-md-3">
		                                <div class="form-actions">
	                                		<div class="col-md-12"> <br>
                                            	<button type="submit" class="btn btn-success btn-prevent-multiple-submits">Save</button>
	                                		</div>
		                        		</div>
		                            </div>
		                        </div>
		                </div>
		           
		            	</form>

			            	<div class="row">
	        					<div class="col-md-12 table-container">
	           
	            
	        					</div>
	    					</div>      
		        		</div>       
		        	</div>
		        </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

	refreshTable("{{route('hrMonthlyProject.table')}}",1000);
	 //submit function
     $("#hrMonthlyReportProject").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url,1);
        refreshTable("{{route('hrMonthlyProject.table')}}",1000);
       
    });

});

</script>






	