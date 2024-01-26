<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <!-- Logo icon --><b>

                    <img src="{{asset('Massets/images/bqb-white-logo-1.png') }}" alt="HRMS" class="light-logo" style="height:70px" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span>
                    <h3 class="light-logo"> <img src="{{asset('Massets/images/bqb-white-logo-2.png')}}" /></h3>
                </span>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items Left Site-->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto mt-md-0">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>

                </li>

            </ul>
            <!-- ============================================================== -->
            <!-- User profile and Notification Right Side -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">

                <!-- Employee Picture -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <div class="dropdown-menu dropdown-menu-right scale-up">
                            <ul class="dropdown-user">
                                <li><a id="logout" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="link" data-toggle="tooltip" title=""><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>

                        </div>
                </li>
            </ul>
        </div>
    </nav>
</header>