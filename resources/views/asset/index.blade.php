@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
	<h3 class="text-themecolor">List of Assets</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">	
		<h4 class="card-title" style="color:black">List of Assets</h4>

		<div class="table-responsive m-t-40">	
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th>Id</th>
					<th>Asset Code</th>
					<th>Description</th>
					<th>Location/Allocation</th>
					<th>QRCode</th>
					<th>Image</th>
					<th class="text-center"style="width:5%">Edit</th>
					@can('asset delete record')
					<th class="text-center"style="width:5%">Delete</th>
					@endcan
				
				</tr>
				</thead>
				
			</table>
		</div>
	</div>
</div>


<script>
$(document).ready(function() {
	
    $(function () {
      	$.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    	});

	    var table = $('#myTable').DataTable({
	  		processing: true,
	  		serverSide: true,
	  		"aaSorting": [],
		  	ajax: {
		   	url: "{{ route('asset.index') }}",
		  	},
		  	columns: [
			   {data: 'id', name: 'id'},
			   {data: 'asset_code', name: 'asset_code'},
			   {data: 'description', name: 'description'},
			   {data: 'location', name: 'location'},
			   {data: 'bar_code', name: 'bar_code'},
			   {data: 'image', name: 'image'},
			   {data: 'edit',name: 'edit', orderable: false, searchable: false },
			   @can('asset delete record')
			   {data: 'delete',name: 'delete', orderable: false, searchable: false }
			   @endcan
		  	],
		  	"drawCallback": function( settings ) {
        		$("[id^='ViewIMG'], [id^='ViewPDF']").EZView();

    		},
    		'columnDefs': [
			  	{
			      "targets": 4,
			      "className": "text-center",
			 	},
			],

 		});

 		$('body').on('click', '.deleteAsset', function () {
	        var asset_id = $(this).data("id");
	        var con = confirm("Are You sure want to delete !");
	        if(con){
	          $.ajax({
	            type: "DELETE",
	            url: "{{ route('asset.store') }}"+'/'+asset_id,
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
    	});    

     
  	}); // end function

    
	     
});


</script>

@stop