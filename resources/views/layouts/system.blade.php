<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    @include('_partials._link')
</head>


<body class="navbar-fixed sidebar-fixed" id="body">
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>
    <!-- ====================================
    ——— WRAPPER
    ===================================== -->
    <div class="wrapper">
        <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
        @include('_partials._sideBar')
        <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
        <div class="page-wrapper">
            <!-- Header -->
            @include('_partials._header')
            <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
            <div class="content-wrapper">
                <div class="content"><!-- For Components documentaion -->
                   @yield('content')
                </div>
            </div>
            <!-- Footer -->
            @include('_partials._footer')
        </div>
    </div>
    <!-- Card Offcanvas -->
    @include("_partials._offCanvas")

    @include("_partials._scripts")
</body>

</html>
