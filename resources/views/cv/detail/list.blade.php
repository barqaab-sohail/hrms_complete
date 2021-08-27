@extends('layouts.master.master')
@section('title', 'BARQAAB CV Record')
@section('Heading')

	<h3 class="text-themecolor">CV Detail</h3>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>
	</ol>
	
@stop
@section('content')
	<div class="card">
		<div class="card-body">
			<!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>-->
			<h4 class="card-title">List of CVs</h4>

			
			<div class="table-responsive m-t-40">
				
				<table id="myTable" class="table table-bordered table-striped"  style="width:100%" >
					<thead>
					<tr>
						<th>Id</th>
						<th>Name</th>
						<th>Education</th>
						<th>Contact No.</th>
						<th>CNIC</th>
						
						<th class="text-center"style="width:5%">Edit</th>
						@can('cv delete record')
						<th class="text-center"style="width:5%">Delete</th>
						@endcan
					</tr>
					</thead>
					<tbody>
						@foreach($cvs as $cv)
							<tr>
								<td>{{$cv->id}}</td>
								<td>{{$cv->full_name}}</td>
								<td>
								@foreach ($cv->cvEducation as $education)
									{{$education->degree_name??''}}
									<br>
								@endforeach
								</td>
								
								
								<td>{{$cv->cvPhone->first()->phone??''}}</td>
								

								<td>{{$cv->cnic??''}}</td>
								
					

								@can('cv edit record')
								<td class="text-center">
									<a class="btn btn-success btn-sm" href="{{route('cv.edit',$cv->id)}}"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
								</td>
								@endcan
								@can('cv delete record')
								<td class="text-center"> 
								 <form action="{{route('cv.destroy',$cv->id)}}" method="POST">
								 @method('DELETE')
								 @csrf
								 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure to Delete')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
								 </form>
								
								 </td>
								 @endcan
															
							</tr>
						@endforeach
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<style>
	 th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: auto;
    }
	</style>

	<!-- start - This is for datatabe Fixed Columns only -->
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<!-- end - This is for datatabe Fixed Columns only -->
	
	<script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                stateSave: false,
        
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    }, {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4]
                        }
                    },
                ],
                scrollY:        "300px",
      			scrollX:        true,
        		scrollCollapse: true,
        		paging:         false,
        		fixedColumns:   {
            		leftColumns: 1,
            		rightColumns:2
        		}
            });
            
        });
        $(document).ready(function () {
            $("#month").change(function(e){
                var url = "/" + $(this).val();
                if (url) {
                    window.location = url;
                }
                return false;
            });
        

        });

    		


	</script>

@stop