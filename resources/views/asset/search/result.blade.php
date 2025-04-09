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
                        <th>Allocation/Location</th>
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
                            @isset($asset->asCurrentAllocation->full_name)
                            {{$asset->asCurrentAllocation->full_name}} - {{$asset->asCurrentAllocation->designation}}
                            @endisset
                            {{$asset->asCurrentLocation->name??''}}
                        </td>
                        <td>

                            @if(file_exists(public_path('storage/'.$asset->asPicture->path.$asset->asPicture->file_name)))
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
                            @else
                            <img class="img-fluid profile-pic ViewIMG" src="{{asset('Massets/images/asset1.png')}}" alt="user" width="30%" />
                            @endif

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
                        // Remove the default created footer
                        doc.content.splice(1, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'center',
                            // image: 'data:image/png;base64,...yourLogoBase64...',
                            width: 100
                        });

                        // Process all rows (not just visible ones)
                        var rows = [];
                        $('#myDataTable').DataTable().rows({
                            search: 'applied'
                        }).every(function() {
                            var row = this.data();
                            var imgSrc = $(this.node()).find('img').attr('src');
                            row[4] = {
                                image: imgSrc,
                                width: 50
                            }; // Replace 4 with your image column index
                            rows.push(row);
                        });

                        doc.content[2].table.body = rows;
                    },
                    pageSize: 'A4',
                    orientation: 'portrait',
                    header: true
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
            paging: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 2
            }
        });

    });
</script>
@endif