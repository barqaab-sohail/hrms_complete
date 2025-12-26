<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('Massets/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('Massets/login/css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md bg-primary navbar-dark navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="img-rounded" alt="" src="{{ asset('Massets/images/bqb-white-logo-1.png') }}" width="40px"> {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" style="color: white;" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
            @yield('content')
        </main>
    </div>

    <!-- Scripts at the bottom for better performance -->
    <script src="{{ asset('Massets/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.fa-spinner').hide();

            // Caps Lock detection for password field
            $('#password').on('keyup', function(event) {
                var text = document.getElementById("text");
                if (event.originalEvent.getModifierState && event.originalEvent.getModifierState("CapsLock")) {
                    if (text) text.style.display = "block";
                } else {
                    if (text) text.style.display = "none";
                }
            });

            // Convert email to lowercase
            $('input[type=email]').on('keyup', function() {
                $(this).val($(this).val().toLowerCase());
            });

            // Prevent multiple form submissions
            $('.form-prevent-multiple-submits').on('submit', function() {
                $('.fa-spinner').show();
                $('.btn-prevent-multiple-submits').prop('disabled', true);
            });

            // Toggle password visibility
            $('#eye').on('click', function() {
                var passwordField = $('#password');
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
            
            // CNIC formatting - ONLY if element exists
            var cnicField = $('#cnic');
            if (cnicField.length > 0) {
                cnicField.on('input', function(ev) {
                    ev.preventDefault();
                    let input = ev.target.value.split("-").join("");
                    if (ev.target.value.length > 15) {
                        input = input.substring(0, input.length - 1);
                    }
                    
                    input = input.split('').map(function(cur, index) {
                        if (index == 5 || index == 12) {
                            return "-" + cur;
                        } else {
                            return cur;
                        }
                    }).join('');
                    
                    $(this).val(input);
                });
            }
        });
    </script>
</body>

</html>