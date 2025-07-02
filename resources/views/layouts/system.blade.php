<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    @include('_partials._link')

    @yield('_links')
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
                    <x-system.success-and-error />
                    <div class="row justify-content-between mb-3">
                        <div class="col-md-4">
                            <button onclick="history.back()" class="btn btn-outline-primary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </button>
                        </div>

                        <!-- at right side, add search button with search mdi icon, a toggle buttons and a submit button -->
                        <div class="col-md-8">
                            <!-- aa search button with input search field with date picker -->
                            <form class="d-flex align-items-center gap-2" action="{{ $_SERVER['REQUEST_URI'] }}"
                                method="GET">
                                <button class="btn btn-primary btn-sm" type="submit" name="submit" id="submitBtn"
                                    {{ session('dateEnabled') ? '' : 'disabled' }}>submit</button>

                                <input type="date" class="form-control" name="date" id="dateInput" required
                                    value="{{ session('date') }}" {{ session('dateEnabled') ? '' : 'disabled' }}
                                    placeholder="Date">

                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="dateEnabled" type="checkbox"
                                        id="flexSwitchCheckDefault" {{ session('dateEnabled') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Enable Form</label>
                                </div>
                            </form>
                        </div>
                    </div>
                    @yield('content')
                </div>

            </div>
            <!-- Footer -->
            @include('_partials._footer')
        </div>
    </div>
    <!-- Card Offcanvas -->
    @include('_partials._offCanvas')

    @include('_partials._scripts')

    @yield('scripts')

    <script>
        $(document).ready(function() {
            $('#flexSwitchCheckDefault').on('change', function() {
                const isChecked = $(this).is(':checked');

                $('#submitBtn').prop('disabled', !isChecked);
                $('#dateInput').prop('disabled', !isChecked);
            });
        });
    </script>
    </script>

</body>

</html>
