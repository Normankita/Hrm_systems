<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    @include('_partials._link')

    @yield('_links')

    <style>
        #toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 12px 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            font-size: 16px;
            opacity: 0;
            transition: all 0.5s ease-in-out;
        }

        #toast.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }
    </style>

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

                    <div id="toast"></div>

                    <div class="row justify-content-between mb-3">
                        <div class="col-md-4">

                            @if (isset($_SERVER['HTTP_REFERER']))
                                <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-outline-primary btn-sm">
                                    <i class="mdi mdi-arrow-left"></i>back
                                </a>
                            @else
                                <a href="#" class="btn btn-primary btn-sm" disabled>
                                    <i class="mdi mdi-arrow-left"></i> Back
                                </a>
                            @endif
                        </div>

                        <!-- at right side, add search button with search mdi icon, a toggle buttons and a submit button -->
                        {{-- <div class="col-md-8">
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
                        </div> --}}
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

    <script>
        function showToast(message, type = 'success', duration = 3000) {
            const toast = document.getElementById("toast");

            // Choose color based on type
            const colors = {
                success: "#4BB543", // Soft green
                error: "#e74c3c", // Bright red
            };

            toast.innerText = message;
            toast.style.backgroundColor = colors[type] || "#333"; // fallback

            toast.className = "show";

            setTimeout(() => {
                toast.className = toast.className.replace("show", "");
            }, duration);
        }
    </script>

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
