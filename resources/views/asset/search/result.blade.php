@if($result->count()!=0)
<hr>

<div class="card">
    <div class="card-body">
        <!--<div class="float-right">
                    <input id="month" class="form-control" value="" type="month">
                </div>-->

        <div class="table-responsive m-t-40">

            <table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0" style="color:black;">
                <thead>
                    <tr>
                        <th style="color:black;">Asset Id</th>
                        <th style="color:black; width: 50%;">Description</th>
                        <th style="color:black;">Ownership</th>
                        <th style="color:black;">Allocation/Location</th>
                        <th style="color:black;">Image</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $asset)
                        <tr>
                            <td style="color:black;">{{ $asset->asset_code ?? '' }}</td>
            
                            <td style="color:black; white-space: normal; word-wrap: break-word; max-width:100%;">
                                <a href="{{ route('asset.edit', $asset->id) }}" style="color:black;">
                                    {{ $asset->description }}
                                </a>
                            </td>
            
                            <td style="color:black;">{{ $asset->asOwnership->name ?? '' }}</td>
            
                            <td style="color:black;">
                                @isset($asset->asCurrentAllocation->first_name)
                                    {{ $asset->asCurrentAllocation?->first_name }}
                                    {{ $asset->asCurrentAllocation?->last_name }}
                                    - {{ $asset->asCurrentAllocation?->employeeCurrentDesignation?->name }}
                                    <br>
                                @endisset
                                {{ $asset->asCurrentLocation->name ?? '' }}
                            </td>
            
                            <td style="color:black;">
                                @php
                                    $imagePath = public_path('storage/' . $asset->asPicture->path . $asset->asPicture->file_name);
                                    $imageUrl = asset('storage/' . $asset->asPicture->path . $asset->asPicture->file_name);
                                    $defaultImage = asset('Massets/images/asset1.png');
                                @endphp
            
                                @if(file_exists($imagePath))
                                    <img class="img-fluid profile-pic ViewIMG"
                                         src="{{ $imageUrl }}"
                                         onerror="this.src='{{ asset('Massets/images/default.png') }}';"
                                         alt="Asset Image"
                                         style="width: 30%;" />
                                @else
                                    <img class="img-fluid profile-pic ViewIMG"
                                         src="{{ $defaultImage }}"
                                         alt="Default Image"
                                         style="width: 30%;" />
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
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
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
                        columns: [0, 1, 2, 3, 4]
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