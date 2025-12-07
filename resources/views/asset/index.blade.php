@extends('layouts.master.master')
@section('title', 'Assets List')
@section('content')
<div class="card">
    <div class="card-body">
        @if(isset($subClassId) && $subClassId)
            <h4 class="card-title" style="color:black">List of Assets - Filtered by Subclass</h4>
            <button onclick="window.history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        @else
            <h4 class="card-title" style="color:black">List of All Assets</h4>
        @endif

        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">Id</th>
                        <th width="7%">Asset Code</th>
                        <th width="35%">Description</th>
                        <th width="10%">Ownership</th>
                        <th width="23%">Location/Allocation</th>
                        <th width="10%">Image</th>
                        <th class="text-center" width="5%">Edit</th>
                        @can('asset delete record')
                        <th class="text-center" width="5%">Delete</th>
                        @endcan
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Determine the URL based on whether we're filtering by subclass
    var url = "{{ route('asset.loadData') }}";
    @if(isset($subClassId) && $subClassId)
        url = "{{ route('asset.loadSubclassData', $subClassId) }}";
    @endif

    var table = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'POST'
        },
        deferRender: true,
        scrollX: true,
        scrollY: 400,
        scroller: {
            loadingIndicator: true
        },
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        pageLength: 25,
        order: [[0, 'desc']],
        dom: '<"top"B<"float-left"l><"float-right"f>><"clear">rt<"bottom"ip<"clear">>',
        buttons: [
            {
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
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            }
        ],
        columns: [
            {
                data: 'id',
                name: 'id',
                searchable: false
            },
            {
                data: 'asset_code',
                name: 'asset_code'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'ownership',
                name: 'ownership',
                orderable: false,
                searchable: false
            },
            {
                data: 'location',
                name: 'location',
                orderable: false,
                searchable: false
            },
            {
                data: 'image',
                name: 'image',
                orderable: false,
                searchable: false
            },
            {
                data: 'edit',
                name: 'edit',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            @can('asset delete record')
            {
                data: 'delete',
                name: 'delete',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
            @endcan
        ],
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> Loading...'
        },
        // EZView integration
        drawCallback: function(settings) {
            // Apply EZView to newly loaded images/PDFs
            $("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
            
            // Lazy load images for better performance
            lazyLoadImages();
        },
        initComplete: function() {
            // Initial EZView setup
            $("[id^='ViewIMG'], [id^='ViewPDF']").EZView();
            lazyLoadImages();
        }
    });

    // Lazy load images function
    function lazyLoadImages() {
        if ("IntersectionObserver" in window) {
            var lazyImageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var lazyImage = entry.target;
                        
                        // Check if it's already loaded
                        if (!lazyImage.classList.contains('loaded')) {
                            // Load the actual image
                            var actualSrc = lazyImage.getAttribute('data-src') || lazyImage.src;
                            lazyImage.src = actualSrc;
                            lazyImage.classList.add('loaded');
                            
                            // Reinitialize EZView for this image
                            if (lazyImage.id && (lazyImage.id.startsWith('ViewIMG') || lazyImage.id.startsWith('ViewPDF'))) {
                                $(lazyImage).EZView();
                            }
                        }
                        
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });
            
            // Observe all images with loading="lazy"
            $('img[loading="lazy"]').each(function() {
                if (!$(this).hasClass('loaded')) {
                    lazyImageObserver.observe(this);
                }
            });
        }
    }

    // Delete asset
    $(document).on('click', '.deleteAsset', function() {
        var asset_id = $(this).data("id");
        if (confirm("Are you sure you want to delete this asset?")) {
            $.ajax({
                type: "DELETE",
                url: "{{ route('asset.store') }}" + '/' + asset_id,
                success: function(response) {
                    table.draw();
                    if (response.error) {
                        showAlert('danger', response.error);
                    } else {
                        showAlert('success', 'Asset deleted successfully');
                    }
                },
                error: function(xhr) {
                    showAlert('danger', xhr.responseJSON?.message || 'An error occurred while deleting the asset');
                }
            });
        }
    });

    function showAlert(type, message) {
        $('.alert-dismissible').remove();
        
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                        message +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>';
        
        $('.card-body').prepend(alertHtml);
        
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 5000);
    }
});
</script>

@stop