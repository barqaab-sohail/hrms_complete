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
		                <form method="post" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
		                {{csrf_field()}}
		                <div class="form-body">
		                   
	                            <div class="row">
	                            	<div class="col-md-2">
		                                <div class="form-group row">
		                                    <div class="col-md-12">
		                                       	<label class="control-label text-right">Month & Year<span class="text_requried" data-validation="required">*</span></label>
		                                       
	                                           	<select  name="month" id="month"  class="form-control selectTwo" data-validation="required">
                                                    <option value=""></option>
                                                    @foreach($inputMonths as $month)
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
	                                           	<select  name="project" id="project" class="form-control selectTwo" data-validation="required">     
                                              </select>
		                                    </div>
		                                </div>
		                            </div> 
		                        </div>
		                </div>
		                <hr> 
		            	</form>
      							<table id="inputTable" class="table table-bordered ">
      								<h2 align="center" id="heading"></h2>
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Input</th>
                            <th>Remarks</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
      					    </table>
		        		</div>       
		        	</div>
		        </div>
            </div>
        </div>
    </div>
</div>
@include('input.inputModal')

<script>
 $(document).ready(function() {


 	selectTwo();
  	
    

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

                          $("#project").append('<option value="'+value['id']+'">'+value['pr_detail']['name']+'</option>'); 
                      });
                       $('#state').select2('destroy');
                       $('#state').select2();

                  }
                 
             }

          });//end ajax
        }
    }); 
  $(function () {
    $('#project').change (function (){
      if($(this).val()){
        var cid = $(this).val();
        var month = $('#month').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         var table = $('#inputTable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: "{{route('input.projectList','')}}"+"/"+cid+"/"+month,
            columns: [
                {data: "DT_RowIndex", name: 'DT_RowIndex'},
                {data: "full_name", name: 'full_name'},
                {data: "designation", name: 'designation'},
                {data: 'input', name: 'input'},
                {data: 'remarks', name: 'remarks'},
                {data: 'edit', name: 'edit', orderable: false, searchable: false},
                {data: 'delete', name: 'delete', orderable: false, searchable: false}
            ],
            order: [[ 1, "desc" ]]
        });
        $('#heading').empty();
        $('#heading').append( "<br><a class='btn btn-success' href='#' data-toggle='modal' id=add_employee>Add Employee</a>" );


         $('#add_employee').click(function () {
          $('#json_message_modal').html('');
          $('#saveBtn').val("create-Input");
          $('#hr_employee_id').val('');
          $('#hr_employee_id').trigger('change');
          $('#hr_designation_id').val('');
          $('#hr_designation_id').trigger('change');
          $('#input').val('');
          $('#input_id').val('');
          $('#remarks').val('');
          $('#inputForm').trigger("reset");
          $('#input_project_id').val(cid);
          $('#month_id').val(month);
          $('#projectModal').modal('show');

         
          });

          $('#saveBtn').unbind().click(function (e) {
          e.preventDefault();
        
          $(this).html('Save');
            $.ajax({
              data: $('#inputForm').serialize(),
              url: "{{ route('input.store') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                  $('#inputForm').trigger("reset");
                  $('#projectModal').modal('hide');
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
            }); //end ajax function
          }); //end save function

          $('body').unbind().on('click', '.editInput', function () {
            var input = $(this).data('id');

            $('#json_message_modal').html('');
            $.get("{{ url('input/input') }}" +'/' + input +'/edit', function (data) {
              console.log(data);
                $('#modelHeading').html("Edit Input");
                $('#saveBtn').val("edit-Input");
                $('#projectModal').modal('show');
                $('#hr_employee_id').val(data.hr_employee_id);
                $('#hr_employee_id').trigger('change');
                $('#hr_designation_id').val(data.hr_designation_id);
                $('#hr_designation_id').trigger('change');
                 $('#input_project_id').val(data.input_project_id);
                $('#input').val(data.input);
                $('#remarks').val(data.remarks);
                $('#input_id').val(data.id);
                
               
            })
          }); // end edit function

          $('body').on('click', '.deleteInput', function () {
       
            var input_id = $(this).data("id");
            var con = confirm("Are You sure want to delete !");
            if(con){
              $.ajax({
                type: "DELETE",
                url: "{{ route('input.store') }}"+'/'+input_id,
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
          }); //end delete function
        }else{

          $('#inputTable_wrapper').hide();
          $('#heading').empty();
        }

    });//change function
  });//end function
   
 


    // $('#project').change(function(){
    //   var cid = $(this).val();
    //   var month = $('#month').val();
    //     if(cid){
    //       $.ajax({
    //          type:"get",
    //          url: "{{route('input.projectList','')}}"+"/"+cid+"/"+month,
    //          //url:"http://localhost/hrms4/public/country/"+cid, **//Please see the note at the end of the post**
    //          success:function(res)
    //          {       
    //               if(res)
    //               { 
    //               	$("#inputTable td").remove();
    //                 $('#heading').text(
    //               		res['pr_detail']['name']+'-'+res['pr_detail']['project_no']
    //               		)
    //               	$('#heading').append( "<br><a class='btn btn-success' href='#' data-toggle='modal' data-target='#projectModal'>Add Employee</a>" );
    //                 $('#input_project_id').val(res['id']);
    //                 $('#month_id').val(res['input_month_id']);
    //                 $.each(res['hr_employee'],function(key,val){ 
    //                   var editUrl='{{route("input.edit",":id")}}';
    //                       editUrl= editUrl.replace(':id', res[' input'][key]['id']);

    //                   $("#inputTable tbody").append('<tr><td>'+ (key+1) +
    //                     '</td><td>'+val['first_name']+' '+val['last_name']+
    //                     '</td><td>'+res['hr_designation'][key]['name']+
    //                     '</td><td>'+res[' input'][key]['input']+
    //                     '</td><td>'+res[' input'][key]['remarks']+
    //                     '</td><td><form id=deleteForm'+key+' action='+"{{route('input.destroy','')}}"+
    //                     '/'+res[' input'][key]['id']+' method="POST"><a class="btn btn-success"href='
    //                       +editUrl+'>Edit</a>@csrf @method("DELETE")<button type="submit" class="btn btn-danger">Delete</button></form></td></tr>');  
                           
    //                 }); 
    //               }
    //          }

    //       });//end ajax
    //     }
    // }); 

    // $("#inputForm").submit(function(e){
    //   e.preventDefault();
    //   alert('lk');
    // });





    
});

</script>

@stop