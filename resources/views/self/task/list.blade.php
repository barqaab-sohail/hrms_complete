@if($tasks->count()!=0)

<hr>         
		<div class="card">
		<div class="card-body">
			
					
			<div class="table-responsive">
				
				<table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
	                    
	                    <tr>
	                        <th>Task Detail</th>
	                        <th class="text-center">Completion Date</th>
	                        <th class="text-center">Target Completion Date</th>
	                        <th class="text-center">Remaining Days</th>
	                        <th class="text-center">Status</th>
	                        <th class="text-center"> Edit </th>
	                        <th class="text-center">Delete</th>
	                    </tr>
	                    </thead>
					<tbody>
						@foreach($tasks as $task)

							@php
								$end_date = \Carbon\Carbon::parse($task->completion_date);
		                        $remainingDays = $end_date->diffInDays(\Carbon\Carbon::today(),false)*-1;
							@endphp
							<tr>
								<td >{{$task->task_detail}}</td>
								<td class="text-center">{{$task->completion_date}}</td>
								<td class="text-center">{{$task->target_completion}}</td>
								<td class="text-center">{{$remainingDays}}</td>
								
								<td class="text-center">
								 <a id="updateStatus{{$task->id}}" href="{{route('task.updateStatus',$task->id)}}" data-toggle="tooltip" data-original-title="status"onclick="return confirm('Are you Sure to Change Status')"  @if($task->status ==="Pending")class="btn btn-danger btn-sm" @else class="btn btn-success btn-sm"  @endif>{{$task->status}}</a>
								</td>

								<td class="text-center">
								 <a class="btn btn-success btn-sm" id="editTask={{$task->id}}" href="{{route('task.edit',$task->id)}}" data-toggle="modal" data-target="#editTaskModal"  data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
								</td>
								
								 
								
								 <td class="text-center">
								 <form id="deleteTask{{$task->id}}" action="{{route('task.destroy',$task->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit"  class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash"></i></button>
								 </form>

								 </td>
								
								
	
							</tr>
						@endforeach
					
					 
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<hr>  
@endif
<script>
$(document).ready(function() {
	 $("form").submit(function (e) {
         e.preventDefault();
      });

	 $('a[id^=editTask]').click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
           url:url,
           method:"GET",
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
                var id = data.data.id;
                $('#edit_task_detail').val(data.data.task_detail);
                $('#edit_completion_date').val(data.data.completion_date);
                $('#edit_target_completion').val(data.data.target_completion);
                $('#edit_remarks').val(data.data.remarks);
               
                var action = "{{route('task.update',":id")}}";
                action = action.replace(':id',id);
                $('#editTaskFrom').attr('action', action);
                        
              },
            error: function (jqXHR, textStatus, errorThrown){
                if (jqXHR.status == 401){
                    location.href = "{{route ('login')}}"
                    }      
                          

                }//end error
        }); //end ajax  

    }); // end document ready function


  	$("form[id^=deleteTask]").submit(function(e) { 
	  	e.preventDefault();
	  	var url = $(this).attr('action');
	  	$('.fa-spinner').show(); 

	  	submitForm(this, url);
		  	setTimeout(function(){
		  		load_data("{{route('task.index')}}");
		  	},1000);
  	
    });

     $('a[id^=updateStatus]').click(function (e){
        e.preventDefault();
       
        var url = $(this).attr('href');
        getAjaxMessage(url);
        
	  	setTimeout(function(){
	  		load_data("{{route('task.index')}}");
	  	},1000);
       
      });




});
</script>
