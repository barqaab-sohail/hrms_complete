@if($result->count()!=0)
<hr>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="progressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="progressModalLabel">Generating PDF</h5>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Generating PDF...</span>
                </div>
                <p class="mt-3">Please wait while we prepare your PDF export.</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive m-t-40">
            <table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0" style="color:black;">
                <thead>
                    <tr>
                        <th style="color:black;">Asset Id</th>
                        <th style="color:black; width: 50%;">Description</th>
                        <th style="color:black;">Ownership</th>
                        <th style="color:black;">Purchase Condition</th>
                        <th style="color:black;">Purchase Date</th>
                        <th style="color:black;">Purchase Cost</th>
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
                            <td style="color:black;">{{ ($asset->asPurchase->as_purchase_condition_id ?? null) == 1 ? 'New' : (isset($asset->asPurchase->as_purchase_condition_id) ? 'Used' : '') }}</td>
                            <td style="color:black;">{{ $asset->asPurchase?->purchase_date ? \Carbon\Carbon::parse($asset->asPurchase?->purchase_date)->format('M d, Y') : '' }}</td>
                            <td style="color:black;">{{ $asset->asPurchase?->purchase_cost ? number_format($asset->asPurchase->purchase_cost, 2) : '' }}</td>
                            <td style="color:black;">
                                @isset($asset->asCurrentAllocation->first_name)
                                {{ $asset->asCurrentAllocation?->employee_no }}-{{ $asset->asCurrentAllocation?->full_name }}-{{ $asset->asCurrentAllocation?->employeeCurrentDesignation?->name }}
                                    <br>
                                @endisset
                                {{ $asset->asCurrentLocation->name ?? '' }}
                            </td>
            
                            <td style="color:black;">
                                @php
                                    $imagePath = public_path('storage/' . $asset->asPicture->path . $asset->asPicture->file_name);
                                    $imageUrl = asset('storage/' . $asset->asPicture->path . $asset->asPicture->file_name);
                                    $defaultImage = asset('Massets/images/asset1.png');
                                    
                                    // Prepare base64 for PDF
                                    if(file_exists($imagePath)) {
                                        $imageData = base64_encode(file_get_contents($imagePath));
                                        $imageSrc = 'data:'.mime_content_type($imagePath).';base64,'.$imageData;
                                    } else {
                                        $defaultPath = public_path('Massets/images/asset1.png');
                                        if(file_exists($defaultPath)) {
                                            $imageData = base64_encode(file_get_contents($defaultPath));
                                            $imageSrc = 'data:'.mime_content_type($defaultPath).';base64,'.$imageData;
                                        } else {
                                            $imageSrc = '';
                                        }
                                    }
                                @endphp
            
                                @if(file_exists($imagePath))
                                    <img class="img-fluid profile-pic ViewIMG export-image"
                                         src="{{ $imageUrl }}"
                                         data-base64="{{ $imageSrc }}"
                                         onerror="this.src='{{ asset('Massets/images/default.png') }}';"
                                         alt="Asset Image"
                                         style="width: 50px; height: 50px; object-fit: cover;" />
                                @else
                                    <img class="img-fluid profile-pic ViewIMG export-image"
                                         src="{{ $defaultImage }}"
                                         data-base64="{{ $imageSrc }}"
                                         alt="Default Image"
                                         style="width: 50px; height: 50px; object-fit: cover;" />
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
        // Function to view from list table
        $(document).on('click', '.ViewIMG', function() {
            $(this).EZView();
        });

        // Custom PDF button with progress indicator
        var customPdfButton = {
            extend: 'pdfHtml5',
            text: 'PDF',
            className: 'btn-primary',
            action: function(e, dt, node, config) {
                $('#progressModal').modal('show');
                
                // Use setTimeout to allow the modal to show before generating PDF
                setTimeout(function() {
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
                    $('#progressModal').modal('hide');
                }, 2000);
            },
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7]
            },
            orientation: 'landscape',
            pageSize: 'A4',
            customize: function(doc) {
                // Set landscape layout
                doc.pageOrientation = 'landscape';
                
                // Set smaller font sizes to fit more content
                doc.defaultStyle.fontSize = 8;
                doc.styles.tableHeader.fontSize = 9;
                
                // Adjust page margins
                doc.pageMargins = [20, 30, 20, 30];
                
                // Process images for PDF
                var images = document.querySelectorAll('.export-image');
                
                for (var i = 1; i < doc.content[1].table.body.length; i++) {
                    if (images[i-1] && images[i-1].getAttribute('data-base64')) {
                        doc.content[1].table.body[i][7] = {
                            image: images[i-1].getAttribute('data-base64'),
                            width: 30,
                            height: 30
                        };
                    } else {
                        doc.content[1].table.body[i][7] = {text: '[Image]', style: 'imagePlaceholder'};
                    }
                }
                
                // Add style definition for image placeholders
                doc.styles.imagePlaceholder = {
                    fontSize: 7,
                    alignment: 'center',
                    background: '#f0f0f0'
                };
                
                return doc;
            }
        };

        $('#myDataTable').DataTable({
            processing: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            stateSave: false,
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // Exclude image column for copy
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // Exclude image column for Excel
                    }
                },
                customPdfButton,
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // Exclude image column for CSV
                    }
                }
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

<style>
    .spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }
    
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
    
    .modal-header {
        border-bottom: none;
    }
    
    .modal-content {
        border-radius: 0.7rem;
    }
</style>
@endif