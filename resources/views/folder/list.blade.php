@extends('layouts.master.master')
@section('title', 'List of Folders')
@section('Heading')
<h3 class="text-themecolor">List of Folders</h3>
@stop
@section('content')
<div class="card">
	<div class="card-body">
		<h4 class="card-title" style="color:black">List of Folders</h4>
		<div class="table-responsive m-t-40">
			<table id="myTable" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="width:90%">Name</th>
						<th style="width:5%">Detail</th>
					</tr>
				</thead>
                <tbody>
                    @foreach($folders as $folder)
                    <tr>
                        <td><a href="{{route('folder_documents.show',$folder->id)}}" title="Detail" style="color:black;">{{$folder->name}}</a></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{route('folder_documents.show',$folder->id)}}" title="Detail"><i class="fas fa-pencil-alt text-white "></i></a>
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