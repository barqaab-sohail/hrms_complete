@if($result->count()!=0)
<hr>
<div class="card">
    <div class="card-body">
        <!--<div class="float-right">
                    <input id="month" class="form-control" value="" type="month">
                </div>-->

        <div class="table-responsive m-t-40">

            <table id="myDataTable" class="table table-bordered table-striped " width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Asset Id</th>
                        <th>Description</th>
                        <th>Ownership</th>
                        <th>Image</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $asset)

                    <tr>
                        <td>{{$asset->asset_code??''}}</td>
                        <td>{{$asset->description}}</td>
                        <td>{{$asset->asOwnership->name??''}}</td>
                        <td>
                            @php
                            $arrContextOptions=array(
                            "ssl"=>array(
                            "verify_peer"=>false,
                            "verify_peer_name"=>false,
                            ),
                            );

                            $path = asset('storage/'.$asset->asPicture->path.$asset->asPicture->file_name);
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path, false, stream_context_create($arrContextOptions));
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            @endphp
                            <img class="img-fluid profile-pic ViewIMG" src="{{$base64}}" onerror="this.src ='{{asset('Massets/images/default.png')}}';" alt="user" width="10%" />
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>

<script>
    $(document).ready(function() {
        //function view from list table
        $(document).on('click', '.ViewIMG', function() {
            $(this).EZView();
        });

        $('#myDataTable').DataTable({
            stateSave: false,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    },
                    customize: function(doc) {
                        //find paths of all images, already in base64 format
                        var arr2 = $('.img-fluid').map(function() {
                            return this.src;
                        }).get();
                        for (var i = 0, c = 1; i < arr2.length; i++, c++) {
                            doc.content[1].table.body[c][3] = {
                                image: arr2[i],
                                width: 50
                            }
                        }
                    },
                }, {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
            ],

            scrollY: "400px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 2
            }
        });

    });
</script>
@endif