<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') - ElQassim </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> --}}

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.4/purify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


</head>

<body>
    <div id="app">
        {{-- nav bar section --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" style="margin-left: -90px">
                    {{-- {{ config('app.name', 'Laravel') }} --}}
                    ElQassim Councils
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown" style="margin-right: -65px">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
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

        @auth
            {{-- sidebar & content --}}
            <nav class="nav d-flex row">

                <!-- Sidebar content -->
                <ul class="nav flex-column col-md-2 mt-2" style="display: inline">

                    <!-- Dropdown Menu Users Management -->
                    <li class="nav-item dropdown">
                        <button class="btn btn-lg btn-secondary m-2 w-100" type="button"
                            onclick="toggleVisibility('usersManagement')">
                            Users Management
                        </button>

                        <div class="dropdown-menu text-body-secondary text-center" id="usersManagement"
                            style="display: none; width: 16rem;">

                            <a type="button" href="{{ route('users.index') }}" class="btn btn-light w-100">Users</a>
                            <a type="button" href="{{ route('users.registerRequests') }}"
                                class="btn btn-light w-100">Register Requests</a>
                        </div>
                    </li>

                    <!-- Dropdown Menu College Management -->
                    <li class="nav-item dropdown">
                        <button class="btn btn-lg btn-secondary m-2 w-100" type="button"
                            onclick="toggleVisibility('collegeManagement')">
                            College Management
                        </button>

                        <div class="dropdown-menu text-body-secondary text-center" id="collegeManagement"
                            style="display: none; width: 16rem;">
                            <a type="button" href="{{ route('headquarters.index') }}"
                                class="btn btn-light w-100">Headquarters</a>
                            <a type="button" href="{{ route('faculties.index') }}"
                                class="btn btn-light w-100">Faculties</a>
                            <a type="button" href="{{ route('departments.index') }}"
                                class="btn btn-light w-100">Departments</a>
                        </div>

                    </li>

                    <!-- Dropdown Menu Topics Management -->
                    <li class="nav-item dropdown">

                        <button class="btn btn-lg btn-secondary m-2 w-100" type="button"
                            onclick="toggleVisibility('topicsManagement')">
                            Topics Management
                        </button>

                        <div class="dropdown-menu text-body-secondary text-center" id="topicsManagement"
                            style="display: none; width: 16rem;">

                            <a type="button" href="{{ route('topics.index') }}" class="btn btn-light w-100">Topics</a>
                            <a type="button" href="{{ route('agendas.index') }}" class="btn btn-light w-100">Agendas</a>

                        </div>

                    </li>

                    <!-- Dropdown Menu Sessions Management -->
                    <li class="nav-item dropdown">

                        <button class="btn btn-lg btn-secondary m-2 w-100" type="button"
                            onclick="toggleVisibility('sessionsManagement')">
                            Sessions Management
                        </button>

                        <div class="dropdown-menu text-body-secondary text-center" id="sessionsManagement"
                            style="display: none; width: 16rem;">

                            <a type="button" href="{{ route('sessions-departments.index') }}" class="btn btn-light w-100">Department
                                Sessions</a>
                            <a type="button" href="#" class="btn btn-light w-100">College
                                Council</a>
                            <a type="button" href="{{ route('sessions-faculties.index') }}" class="btn btn-light w-100">Faculty
                                Sessions</a>

                        </div>

                    </li>

                </ul>

                <!-- Main content -->
                <main class="p-5 col-md-10">
                    @yield('content')
                </main>
            </nav>
        @endauth

        <!-- Main content if guest-->
        @guest
            <main class="p-5">
                @yield('content')
            </main>
        @endguest

    </div>

    <script>
        function toggleVisibility(sectionId) {
            // Select the target div
            var section = document.getElementById(sectionId);

            // Toggle display property
            if (section.style.display === 'none' || section.style.display === '') {
                // Hide all sections first
                document.querySelectorAll('.dropdown-menu').forEach(function(div) {
                    div.style.display = 'none';
                });

                // Then show the clicked section
                section.style.display = 'block';
            } else {
                // Hide the section if it's already visible
                section.style.display = 'none';
            }
        }
    </script>
</body>

</html>
