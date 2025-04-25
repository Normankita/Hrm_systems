<header class="main-header" style="border-bottom: 7px solid rgb(233, 243, 87);" id="header">
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>


        <div class="navbar-right ">
            <span>{{ \Carbon\Carbon::now()->format('d M') }}</span>

            <ul class="nav navbar-nav">
                <!-- Offcanvas -->

                <!-- User Account -->
                <li class="dropdown user-menu">
                    <button class="dropdown-toggle nav-link" data-toggle="dropdown">

                        <span class="d-none d-lg-inline-block">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a class="dropdown-link-item" href="user-profile.html">
                                <i class="mdi mdi-account-outline"></i>
                                <span class="nav-text">My Profile</span>
                            </a>
                        </li>


                        <li class="dropdown-footer">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {!! '<i class="mdi mdi-logout"></i> Log Out' !!}
                                </x-responsive-nav-link>
                            </form>

                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>


</header>
