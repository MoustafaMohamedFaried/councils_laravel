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
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap 4 Select2 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
            <div class="d-flex">
                <div class="sidebar-navigation col-md-2">
                    <ul>
                        <li><a href="#">Users Management <em class="mdi mdi-chevron-down"></em></a>
                            <ul>
                                <li><a href="{{ route('users.index') }}">Users</a></li>
                                <li><a href="{{ route('users.registerRequests') }}">Register Requestes</a></li>
                            </ul>
                        </li>
                        <li><a href="#">College Management <em class="mdi mdi-chevron-down"></em></a>
                            <ul>
                                <li><a href="{{ route('headquarters.index') }}">Headquarters</a></li>
                                <li><a href="{{ route('faculties.index') }}">Faculties</a></li>
                                <li><a href="{{ route('departments.index') }}">Departments</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Topics Management <em class="mdi mdi-chevron-down"></em></a>
                            <ul>
                                <li><a href="{{ route('topics.index') }}">Topics</a></li>
                                <li><a href="{{ route('agendas.index') }}">Agendas</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Session Management <em class="mdi mdi-chevron-down"></em></a>
                            <ul>
                                <li><a href="{{ route('sessions-departments.index') }}">Department</a></li>
                                <li><a href="#">College Council</a></li>
                                <li><a href="#">Faculty</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <main class="p-5 col-md-10">
                    @yield('content')
                </main>
            </div>
        @endauth

        <!-- Main content if guest-->
        @guest
            <main class="p-5">
                @yield('content')
            </main>
        @endguest

    </div>

    <style>
        .sidebar-navigation {
            width: 200px;
            height: 50rem;
            background-color: #fff;
            /*margin: 20px auto;*/
            margin: -1px 1px;
            webkit-box-shadow: 3px 5px 10px 0px rgba(0, 0, 0, 0.16);
            -moz-box-shadow: 3px 5px 10px 0px rgba(0, 0, 0, 0.16);
            box-shadow: 3px 5px 10px 0px rgba(0, 0, 0, 0.16);
            font-family: "Poppins", sans-serif;
            font-size: 12px;
        }

        .sidebar-navigation .title {
            display: block;
            font-size: 1.2em;
            background-color: #1e1e1e;
            padding: 20px 25px;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .sidebar-navigation>ul>li>a {
            text-transform: uppercase;
        }

        .sidebar-navigation ul {
            margin: 0;
            padding: 0;
        }

        .sidebar-navigation ul li {
            display: block;
        }

        .sidebar-navigation ul li a {
            position: relative;
            display: block;
            font-size: 1em;
            font-weight: 600;
            padding: 20px 25px;
            text-decoration: none;
            color: #2e2e2e;
            letter-spacing: 0.02em;
            /* border-bottom: 1px solid #eee; */
            -webkit-transition: all 0.3s linear;
            -moz-transition: all 0.3s linear;
            -o-transition: all 0.3s linear;
            transition: all 0.3s linear;
        }

        .sidebar-navigation ul li a em {
            font-size: 24px;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            padding: 5px;
            border-radius: 50%;
        }

        .sidebar-navigation ul li:hover>a,
        .sidebar-navigation ul li.selected>a {
            background-color: #ecf0f1;
            color: #495d62;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-navigation ul li ul {
            display: none;
        }

        .sidebar-navigation ul li ul.open {
            display: block;
        }

        .sidebar-navigation ul li ul li a {
            color: #495d62;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-navigation ul li ul li a:before {
            content: "";
            width: 10px;
            height: 1px;
            margin-right: 5px;
            display: inline-block;
            vertical-align: middle;
            background-color: #495d62;
            -webkit-transition: all 0.2s linear;
            -moz-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            transition: all 0.2s linear;
        }

        .sidebar-navigation ul li ul li:hover>a,
        .sidebar-navigation ul li ul li.selected>a {
            background-color: #e6ebed;
        }

        .sidebar-navigation ul li ul li:hover>a:before,
        .sidebar-navigation ul li ul li.selected>a:before {
            margin-right: 10px;
        }

        .sidebar-navigation ul li ul li.selected.selected--last>a {
            background-color: #94aab0;
            color: #fff;
        }

        .sidebar-navigation ul li ul li.selected.selected--last>a:before {
            background-color: #fff;
        }

        .subMenuColor1 {
            background-color: #fbfcfc;
        }

        .subMenuColor2 {
            background-color: white;
        }
    </style>

    <script>
        $(function() {
            var $ul = $('.sidebar-navigation > ul');

            $ul.find('li a').click(function(e) {
                var $li = $(this).parent();

                if ($li.find('ul').length > 0) {
                    e.preventDefault();

                    if ($li.hasClass('selected')) {
                        $li.removeClass('selected').find('li').removeClass('selected');
                        $li.find('ul').slideUp(400);
                        $li.find('a em').removeClass('mdi-flip-v');
                    } else {

                        if ($li.parents('li.selected').length == 0) {
                            $ul.find('li').removeClass('selected');
                            $ul.find('ul').slideUp(400);
                            $ul.find('li a em').removeClass('mdi-flip-v');
                        } else {
                            $li.parent().find('li').removeClass('selected');
                            $li.parent().find('> li ul').slideUp(400);
                            $li.parent().find('> li a em').removeClass('mdi-flip-v');
                        }

                        $li.addClass('selected');
                        $li.find('>ul').slideDown(400);
                        $li.find('>a>em').addClass('mdi-flip-v');
                    }
                }
            });


            $('.sidebar-navigation > ul ul').each(function(i) {
                if ($(this).find('>li>ul').length > 0) {
                    var paddingLeft = $(this).parent().parent().find('>li>a').css('padding-left');
                    var pIntPLeft = parseInt(paddingLeft);
                    var result = pIntPLeft + 20;

                    $(this).find('>li>a').css('padding-left', result);
                } else {
                    var paddingLeft = $(this).parent().parent().find('>li>a').css('padding-left');
                    var pIntPLeft = parseInt(paddingLeft);
                    var result = pIntPLeft + 20;

                    $(this).find('>li>a').css('padding-left', result).parent().addClass('selected--last');
                }
            });

            var t = ' li > ul ';
            for (var i = 1; i <= 10; i++) {
                $('.sidebar-navigation > ul > ' + t.repeat(i)).addClass('subMenuColor' + i);
            }

            var activeLi = $('li.selected');
            if (activeLi.length) {
                opener(activeLi);
            }

            function opener(li) {
                var ul = li.closest('ul');
                if (ul.length) {

                    li.addClass('selected');
                    ul.addClass('open');
                    li.find('>a>em').addClass('mdi-flip-v');

                    if (ul.closest('li').length) {
                        opener(ul.closest('li'));
                    } else {
                        return false;
                    }

                }
            }

        });
    </script>
</body>

</html>
