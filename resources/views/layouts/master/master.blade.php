<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Sohail Afzal">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('Massets/images/favicon.ico') }}">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user_id" content="{{Auth::check() ? Auth::user()->id :''}}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" />


    <!-- Jquery Cropper -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />

    <!-- Jquery UI Datepicker "Redmond theme" CSS -->
    <link href="{{asset('Massets/js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('Massets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- JS Validation  CSS -->
    <link href="{{asset('Massets/css/js-validation/js-validation.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- chartist CSS -->
    <link href="{{asset('Massets/plugins/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{asset('Massets/plugins/chartist-js/dist/chartist-init.css') }}" rel="stylesheet">
    <link href="{{asset('Massets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{asset('Massets/plugins/c3-master/c3.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->

    <link href="{{asset('Massets/css/hrstyle.css') }}" rel="stylesheet">
    <link href="{{asset('Massets/select2/select2.min.css') }}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    @if(Config::get('app.url')==='http://localhost/hrms/public/')
    <link href="{{asset('Massets/css/colors/megna.css') }}" id="theme" rel="stylesheet">
    @else
    <link href="{{asset('Massets/css/colors/blue.css') }}" id="theme" rel="stylesheet">
    @endif
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{asset('Massets/css/toastr.min.css')}}">
    <link href="{{asset('Massets/plugins/footable/css/footable.bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('Massets/plugins/wizard/steps.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('Massets/plugins/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('Massets/plugins/html5-editor/bootstrap-wysihtml5.css')}}" />
    <link rel="stylesheet" href="{{asset('Massets/js/crop/croppie.css')}}" />
    <!-- start - This is for datatabe Fixed Columns only -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.4.0/css/rowGroup.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <!-- end - This is for datatabe Fixed Columns only -->

</head>
@include('layouts.master.adminScripts')
<style>
    thead th {
        font-weight: bold;
    }
     .page-wrapper {
        margin-left: 270px; /* This should match your sidebar width */
        padding-left: 10px; /* This adds the 20px space you want */
    }

    .page-titles{
        margin-left: 0px; 

    }
    
    /* If you're using a collapsed sidebar state, adjust that too */
    .mini-sidebar .page-wrapper {
        margin-left: 70px; /* Adjust if you have a collapsed sidebar width */
        padding-left: 20px;
    }
    
    /* Make sure the transition is smooth if you're using sidebar toggling */
    .page-wrapper {
        transition: all 0.3s ease;
    }

     /* Add loading indicator style */
     .ajax-loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }
</style>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"></circle>
        </svg>
    </div>


    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">

        @include('layouts.master.adminHeader')
        @include('layouts.master.adminNav')
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-12 col-8 align-self-center">
                     
                        <h3 class="text-themecolor"> @yield('h3')</h3>
                        @yield('Heading')

                    </div>
                </div>

                @include('layouts.master.message')
                <!-- AJAX loading indicator -->
                <div class="ajax-loading">
                    <img src="{{asset('spinner.gif')}}" alt="Loading..." />
                </div>

                {{-- <div class="col-lg-12 d-flex flex-column min-vh-100 justify-content-center align-items-center">
                    <img id="loading-image" src="{{asset('spinner.gif')}}" style="display:none;" />
                </div> --}}
                 <!-- Main content container for AJAX loading -->
                 <div id="ajax-content-container">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @yield('footer')
    <div style="text-align:center">
        <p>HRMS {{ Config::get('app.version') }} - Developed by: BARQAAB IT</p>
    </div>

    <script>
        $(document).ready(function() {
            // Handle navigation link clicks
            $(document).on('click', '#sidebarnav a:not(.has-arrow):not(.not-ajax)', function(e) {
                e.preventDefault();
                
                var url = $(this).attr('href');
                var title = $(this).text().trim();
                
               // Remove active class from all a tags
                $('#sidebarnav a').removeClass('active');
                
                // Add active class to clicked a tag
                $(this).addClass('active');
                
                // Update browser history
                history.pushState(null, title, url);
                
                // Load content via AJAX
                loadContent(url);
            });
            
            // Handle browser back/forward buttons
            window.onpopstate = function() {
                loadContent(location.pathname + location.search);
            };
            
            function loadContent(url) {
                // Show loading indicator
                
                $('.ajax-loading').show();
                
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success: function(response) {
                        // Extract the content section from the response
                        var content = $(response).filter('#ajax-content-container').html();
                        if (!content) {
                            content = $(response).find('#ajax-content-container').html();
                        }
                        
                        // Update the content container
                        $('#ajax-content-container').html(content);
                        
                        // Hide loading indicator
                        $('.ajax-loading').hide();
                        
                        // Update page title if available
                        var pageTitle = $(response).filter('title').text();
                      
                        if (pageTitle) {
                            document.title = pageTitle;
                             // Update the h3 text
                             var headingElements = $('h3.text-themecolor');
                            if (headingElements.length > 1) {
                                // Keep the first one and remove all others
                                headingElements.slice(1).remove();
                                
                                // Optional: console log how many were removed
                                console.log('Removed ' + (headingElements.length - 1) + ' duplicate heading(s)');
                            }
                           $('h3.text-themecolor').text(pageTitle);
                        }
                       
                    },
                    error: function(xhr) {
                        // Handle errors
                        $('.ajax-loading').hide();
                        console.error('Error loading content: ', xhr.statusText);
                        
                        // Fallback to full page load if AJAX fails
                        window.location.href = url;
                    }
                });
            }
            // Initialize active state based on current URL on page load
            function initializeActiveState() {
                var currentPath = window.location.pathname;
                
                // Remove active class from all a tags
                $('#sidebarnav a').removeClass('active');
                
                // Find matching link and add active class
                $('#sidebarnav a').each(function() {
                    var href = $(this).attr('href');
                    if (currentPath === href || currentPath.startsWith(href)) {
                        $(this).addClass('active');
                        return false; // break the loop after first match
                    }
                });
            }
            
            // Call the initialization function when page loads
            initializeActiveState();
            
        });
        </script>


</body>

</html>