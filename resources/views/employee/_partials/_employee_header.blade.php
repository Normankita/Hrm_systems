<header class="main-header" style="border-bottom: 7px solid rgb(243, 87, 87);" id="header">
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>


        <div class="navbar-right ">
            <span>{{ \Carbon\Carbon::now()->format('d M') }}</span>

            <!-- search form -->
            {{-- <div class="search-form">
                <form action="index.html" method="get">
                    <div class="input-group input-group-sm" id="input-group-search">
                        <input type="text" autocomplete="off" name="query" id="search-input"
                            class="form-control" placeholder="Search..." />
                        <div class="input-group-append">
                            <button class="btn" type="button">/</button>
                        </div>
                    </div>
                </form>
                <ul class="dropdown-menu dropdown-menu-search">

                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Morbi leo risus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Dapibus ac facilisis in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Porta ac consectetur ac</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Vestibulum at eros</a>
                    </li>

                </ul>

            </div> --}}

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
                        {{-- <li>
                            <a class="dropdown-link-item" href="email-inbox.html">
                                <i class="mdi mdi-email-outline"></i>
                                <span class="nav-text">Message</span>
                                <span class="badge badge-pill badge-primary">24</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-link-item" href="user-activities.html">
                                <i class="mdi mdi-diamond-stone"></i>
                                <span class="nav-text">Activitise</span></a>
                        </li> --}}
                        <li>
                            <!-- the value of settings id will be found on session  -->
                            {{-- <a class="dropdown-link-item" href="
                            {{ route('admin.companies.edit', auth()->user()->company_id) }}">
                                <i class="mdi mdi-settings"></i>
                                <span class="nav-text">Company Profile</span>
                            </a> --}}
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
