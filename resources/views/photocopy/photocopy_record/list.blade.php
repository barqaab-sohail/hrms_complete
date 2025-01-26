@extends('layouts.master.master')
@section('title', 'Photocopies')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')
<div class="card">
	<div class="card-body">
		<h4 class="card-title" style="color:black">List of Photocopies</h4>
		<div class="table-responsive m-t-40">
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="width:90%">Name</th>
						<th style="width:5%">Detail</th>
					</tr>
				</thead>
                <tbody>
                    @foreach($photocopies as $photocopy)
                    <tr>
                        <td>{{$photocopy->name}}</td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{route('photocopy_record.show',$photocopy->id)}}" title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
			</table>
		</div>
	</div>
</div>

</div>

@stop