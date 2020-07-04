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
								<td class="text-center">{{$task->status}}</td>

								
								<td class="text-center">
								 <a class="btn btn-info btn-sm" id="editTask" href="{{route('task.edit',$task->id)}}" data-toggle="tooltip" data-original-title="Edit"> <i class="fas fa-pencil-alt text-white "></i></a>
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

	 $('a[id^=edit]').click(function (e){
        e.preventDefault();
       
        var url = $(this).attr('href');
         console.log(url);
        getAjaxData(url);
       
      });


  	$("form[id^=deleteTask]").submit(function(e) { 
  	e.preventDefault();
  	var url = $(this).attr('action');
  	$('.fa-spinner').show(); 

  	submitForm(this, url);
  
  	//refreshTable("{{route('posting.table')}}",1000);
    });




});
</script>
