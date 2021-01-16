@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">Add Input of Employees</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>	
	</ol>
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline-info">
			<div class="row">
		        <div class="col-lg-12">
		            <div style="margin-top:10px; margin-right: 10px;">                   
		            </div>
		            <div class="card-body">
		                <form id= "inputForm"  action="{{route('input.store')}}" method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                   
	                            <div class="row">
	                            	<div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Month & Year<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="month" id="month"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($hrInputMonths as $month)
													<option value="{{$month->id}}" {{(old("month")==$month? "selected" : "")}}>{{$month->month}}-{{$month->year}}</option>
                                                    @endforeach     
                                                </select>
		                                    </div>
		                                </div>
		                            </div> 
		                            <div class="col-md-6">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Projects<span class="text_requried" data-validation="required">*</span></label> 
	                                           	<select  name="hr_input_project_id" id="project" class="form-control selectTwo" data-validation="required">     
                                              </select>
		                                    </div>
		                                </div>
		                            </div> 
		                        </div>
		                </div>
		                <hr> 
		            	</form>
							<table id="inputTable" class="table table-bordered">
								<h2 align="center" id="heading"></h2>
						        <tr>
						            <th>No</th>
						            <th>Name</th>
						            <th>Designation</th>
						            <th>Input</th>
                        <th>Remarks</th>
						            <th width="250px">Action</th>
						        </tr>
						            
						        </tr>
					    	</table>
		        		</div>       
		        	</div>
		        </div>
            </div>
        </div>
    </div>
</div>
@include('input.inputProject.projectModal')

<script>
 $(document).ready(function() {


 	selectTwo();
  	
    //submit function
     $("#inputFrom").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        submitForm(this, url,1);
       
    });

    $('#month').change(function(){
      var cid = $(this).val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{url('input/inputProject')}}"+"/"+cid,

             //url:"http://localhost/hrms4/public/country/"+cid, **//Please see the note at the end of the post**
             success:function(res)
             {       
                  if(res)
                  {
                     $("#project").empty();
                     $("#inputTable td").remove();
                     $('#heading').text(''); 
                     $("#project").append('<option value="">Select Project</option>');
                      $.each(res,function(key,value){
                          $("#project").append('<option value="'+value['pr_detail']['id']+'">'+value['pr_detail']['name']+'</option>'); 
                      });
                       $('#state').select2('destroy');
                       $('#state').select2();

                  }
                 
             }

          });//end ajax
        }
    }); 

     $('#project').change(function(){
      var cid = $(this).val();
      var month = $('#month').val();
        if(cid){
          $.ajax({
             type:"get",
             url: "{{route('input.projectList','')}}"+"/"+cid+"/"+month,
             //url:"http://localhost/hrms4/public/country/"+cid, **//Please see the note at the end of the post**
             success:function(res)
             {       
                  if(res)
                  { 
                  	$("#inputTable td").remove();
                    $('#heading').text(
                  		res['pr_detail']['name']+'-'+res['pr_detail']['project_no']
                  		)
                  	$('#heading').append( "<br><a class='btn btn-success' href='#' data-toggle='modal' data-target='#projectModal'>Add Employee</a>" );
                    $('#hr_input_project_id').val(res['id']);
                    $('#month_id').val(res['hr_input_month_id']);
                    $.each(res['hr_employee'],function(key,val){ 
                      var editUrl='{{route("input.edit",":id")}}';
                          editUrl= editUrl.replace(':id', res['hr_input'][key]['id']);

                      $("#inputTable tbody").append('<tr><td>'+ (key+1) +
                        '</td><td>'+val['first_name']+' '+val['last_name']+
                        '</td><td>'+res['hr_designation'][key]['name']+
                        '</td><td>'+res['hr_input'][key]['input']+
                        '</td><td>'+res['hr_input'][key]['remarks']+
                        '</td><td><form id=deleteForm'+key+' action='+"{{route('input.destroy','')}}"+
                        '/'+res['hr_input'][key]['id']+' method="POST"><a class="btn btn-primary"href='
                          +editUrl+'>Edit</a>@csrf @method("DELETE")<button type="submit" class="btn btn-danger">Delete</button></form></td></tr>');  
                            //console.log(val['first_name']);
                    }); 
                  }
             }

          });//end ajax
        }
    }); 

    // $("#inputForm").submit(function(e){
    //   e.preventDefault();
    //   alert('lk');
    // });

    $(document).on('submit','form[id^=deleteForm]',function(event){
    event.preventDefault();
    var url = $(this).attr('action');
    var tr = $(this).closest('tr');
      $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Objectl
            }
        });
      $.ajax({
             method:"DELETE",
             url: url,
             success:function(res)
             {       
                  if(res)
                  {
                    tr.remove();
                    $('#inputTable tr').each(function(i) {
                      $(this).children('td:first-child').text(i);
                    });
                   // console.log(tr);
                  }     
             }

          });//end ajax
    }); //end submit




    
});

</script>

@stop