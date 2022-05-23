<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        @yield('title')
    </title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/mmenu.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" media="all" />
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
</head>

<body>
    <div id="login-page">
        <div class="container">
            <div class="row">
                <div class="panel login-panel ">
                    <div class="panel-body">
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img width="100" height="100" src="{{ asset('img/logo.png') }}" alt="logo">
                            </a>
                        </div>
                        @if ($errors->any())
                            {{ implode('', $errors->all(':message')) }}
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript" src="./include/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./include/js/mmenu.js"></script>
    <script type="text/javascript" src="./include/js/script.js"></script>
</body>

</html>
