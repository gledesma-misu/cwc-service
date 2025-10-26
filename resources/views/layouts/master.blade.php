<!doctype html>
<html lang="en">

<head>
    <title>CWC - Service</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('public/build/assets/app-WR_sbLG9.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('public/sidebar/css/style.css') }}">

</head>

<body>
    <div class="wrapper d-flex align-items-stretch" id="app">
        <nav id="sidebar">
            <div class="p-4 pt-5">
                <a href="#">
                    <img class="img logo mb-5" src="{{ asset('public/images/login_logo.png') }}" alt="Logo">
                </a>
                <ul class="list-unstyled components mb-5">
                    <li class="{{ Request::is('dashboard', '/index') }}">
                        <a href="#">Dashboard</a>
                    </li>
                    @can('employee')
                        <li class="{{ Request::is('techassistance/index') ? 'active' : '' }}">
                            <a href="{{ route('techAssistanceIndex') }}">Technical Assistance</a>
                        </li>
                    @endcanany
                    @canany(['administrator','misu'])
                        <li class="{{ Request::is('techassistance/index', 'techassistance/report') ? 'active' : '' }}">
                            <a href="#homeSubmenu" data-bs-toggle="collapse" role="button" aria-expanded=" {{ Request::is('techassistance/index', 'techassistance/report') ? 'true' : 'false' }}"
                                aria-controls="homeSubmenu">Technical Assistance
                                <i class="fa fa-angle-down float-end mt-2"></i></a>
                            <ul class="collapse list-unstyled
                            {{ Request::is('techassistance/index', 'techassistance/report') ? 'show' : '' }}" id="homeSubmenu">
                                <li class="{{ Request::is('techassistance/index') ? 'active' : '' }}">
                                    <a href="{{ route('techAssistanceIndex') }}">Request List</a>
                                </li>
                                {{-- <li class="{{ Request::is('techassistance/list') ? 'active' : '' }}">
                                <a href="{{ route('techAssistanceList') }}">Request List</a>
                            </li> --}}
                                <li class="{{ Request::is('techassistance/report') ? 'active' : '' }}">
                                    <a href="{{ route('techAssistanceReport') }}">Report</a>
                                </li>
                            </ul>
                        </li>
                    @endcanany
                    @can('administrator')
                        <li
                            class="{{ Request::is('divisions/index', 'announcement/', 'roles/index', 'permissions/index', 'staffs/index') ? 'active' : '' }}">
                            <a href="#pageSubmenu" data-bs-toggle="collapse" role="button"
                                aria-expanded="
                            {{ Request::is('divisions/index', 'announcement/', 'roles/index', 'permissions/index') ? 'true' : 'false' }}"
                                aria-controls="pageSubmenu">System Settings
                                <i class="fa fa-angle-down float-end mt-2"></i></a>
                            <ul class="collapse list-unstyled 
                            {{ Request::is('divisions/index', 'announcement/', 'roles/index', 'roles/create', 'permissions/index', 'permissions/create', 'staffs/index') ? 'show' : '' }}"
                                id="pageSubmenu">
                                <li>
                                    <a href="#">Announcement</a>
                                </li>
                                <li>
                                    <a href="#">System Announcement</a>
                                </li>
                                @can('users-read')
                                    <li class="{{ Request::is('staffs/index') ? 'active' : '' }}">
                                        <a href="{{ route('staffsIndex') }}">Staff</a>
                                    </li>
                                @endcan
                                @can('divisions-read')
                                    <li class="{{ Request::is('divisions/index') ? 'active' : '' }}">
                                        <a href="{{ route('divisionsIndex') }}">Division/Unit</a>
                                    </li>
                                @endcan
                                @can('roles-read')
                                    <li class="{{ Request::is('roles/index', 'roles/create') ? 'active' : '' }}">
                                        <a href="{{ route('rolesIndex') }}">Type of Account</a>
                                    </li>
                                @endcan
                                @can('permissions-read')
                                    <li class="{{ Request::is('permissions/index') ? 'active' : '' }}">
                                        <a href="{{ route('permissionsIndex') }}">Permissions</a>
                                    </li>
                                @endcan
                                <li>
                                    <a href="#">Technical Assistance (Remarks)</a>
                                </li>
                            </ul>
                        </li>
                    @endcan


                </ul>

                <div class="footer">
                    <p>
                    </p>
                </div>

            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>

                    <span class="ml-3">
                        Account: {{Auth::user()->fname . ' ' . Auth::user()->lname}}
                    </span>

                    <notifications-component></notifications-component>

                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" id="navbarCollapse">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profileIndex') }}">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <logout-component></logout-component>
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {{-- <p class="text-success">
                    {{ Session::get('success') }}
                </p> --}}
            @endif

            @yield('content')

        </div>

    </div>

    {{-- <script src="{{ asset('public/sidebar/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/sidebar/js/popper.js') }}"></script> --}}
    {{-- <script src="{{ asset('public/sidebar/js/bootstrap.min.js') }}"></script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Example for showing an alert after page load (e.g., for flash messages)
            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    position: "center",
                    showConfirmButton: true,
                    text: '{{ Session::get('success') }}',
                    timer: 3000
                });
            @endif

        });
    </script>

    @auth
        <script>
            window.token = {!! json_encode(session()->get('token')) !!}
            window.auth_user = {!! json_encode(auth()->user()) !!};
            window.auth_roles = {!! json_encode(auth()->user()->roles) !!};
            window.auth_permissions = {!! json_encode(auth()->user()->permissions) !!};
        </script>
    @endauth

    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="{{ asset('public/sidebar/js/main.js') }}"></script>
</body>

</html>
