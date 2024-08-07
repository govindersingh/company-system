<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Company System</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('custom/custom.css') }}" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @notifyCss
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{asset('images/header-logo.png') }}" alt="logo"  width="200" height="50" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li><a class="nav-link text-dark" href="{{ url('/') }}">Home</a></li>

                            @canany(['show-client', 'create-client', 'edit-client', 'delete-client'])
                                <li><a class="nav-link text-dark" href="{{ route('clients.index') }}">Clients</a></li>
                            @endcanany

                            @canany(['show-project', 'create-project', 'edit-project', 'delete-project'])
                                <li><a class="nav-link text-dark" href="{{ route('projects.index') }}">Projects</a></li>
                            @endcanany

                            {{--
                            @canany(['show-billing', 'create-billing', 'edit-billing', 'delete-billing'])
                                <li><a class="nav-link text-dark" href="{{ route('billings.index') }}">Billings</a></li>
                            @endcanany
                            --}}

                            @canany(['show-scrum', 'create-scrum', 'edit-scrum', 'delete-scrum'])
                                <li><a class="nav-link text-dark" href="{{ route('scrums.index') }}">Scrum</a></li>
                            @endcanany

                            @canany(['show-report', 'create-report', 'edit-report', 'delete-report'])
                                <li><a class="nav-link text-dark" href="{{ route('reports.index') }}">Report</a></li>
                            @endcanany

                            <!-- <li><a class="nav-link text-dark" href="{{ route('chatify') }}">Messages</a></li> -->
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @canany(['show-user', 'create-user', 'edit-user', 'delete-user'])
                                        <a class="dropdown-item text-dark" href="{{ route('users.index') }}">Manage Users</a>
                                    @endcanany

                                    @canany(['show-role', 'create-role', 'edit-role', 'delete-role'])
                                        <a class="dropdown-item text-dark" href="{{ route('roles.index') }}">Manage Roles</a>
                                    @endcanany

                                    <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-12">
                        
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success text-center" role="alert">
                                {{ $message }}
                            </div>
                        @endif

                        <!-- <h3 class="text-center mt-3 mb-3">C.S. <a href="#">URL</a></h3> -->
                        @yield('content')
                        
                        <!-- <div class="row justify-content-center text-center mt-3">
                            <div class="col-md-12">
                                <p>All copy rights: <a href="#"><strong></strong></a></p>
                                <p>Visit website: <a href="https://bytecodetechnologies.in/" target="_blank"><strong>bytecodetechnologies.in</strong></a></p>
                            </div>
                        </div> -->


                    </div>
                </div>
            </div>
        </main>
    </div>
    <x-notify::notify />
    @notifyJs
</body>
</html>