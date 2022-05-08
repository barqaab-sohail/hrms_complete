<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('Massets/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>{{ config('app.name_pms', 'PMS') }}</title>

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
       <nav class="navbar navbar-expand-md bg-success navbar-dark navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('pms') }}">
                   <img class="img-rounded" alt="" src="{{asset('Massets/images/EmailMono.jpg')}}" width="40px"> {{ config('app.name_full_name_pms', 'Project Monitoring System') }}
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
                           <!--  <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('pms') }}">{{ __('Login') }}</a>
                            </li> -->
                            @if (Route::has('register'))
                               <!--  <li class="nav-item">
                                    <a class="nav-link" style="color: white;" href="{{ url('pmsRegister') }}">{{ __('Register') }}</a>
                                </li> -->
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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
</body>
</html>
<script src="{{asset('Massets/plugins/jquery/jquery.min.js') }}"></script>
<script>
 $('.fa-spinner').hide();

$(document).ready(function(){

   var input = document.getElementById("password");
   var text = document.getElementById("text");
   input.addEventListener("keyup", function(event) {
        if (event.getModifierState("CapsLock")) {
        text.style.display = "block";
        } else {
        text.style.display = "none"
        }
    });

    jQuery('#password').keypress(function(e) {
        var s = String.fromCharCode( e.which );
          if ( s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey ) {
            jQuery('#capslockdiv').show();
          }
          else { 
            jQuery('#capslockdiv').hide();
          }
    });




    $('input[type=email]').keyup(function() {
    $(this).val($(this).val().toLowerCase());
    });

    
    (function(){
        $('.form-prevent-multiple-submits').on('submit', function(){
            $('.fa-spinner').show();
            $('.btn-prevent-multiple-submits').attr('disabled','ture');
           
        })
    })();

      
     //Make sure that the event fires on input change
    $("#cnic").on('input', function(ev){
        
        //Prevent default
        ev.preventDefault();
        
        //Remove hyphens
        let input = ev.target.value.split("-").join("");
        if(ev.target.value.length>15){
            input =  input.substring(0,input.length-1)
        }
        
        //Make a new string with the hyphens
        // Note that we make it into an array, and then join it at the end
        // This is so that we can use .map() 
        input = input.split('').map(function(cur, index){
            
            //If the size of input is 6 or 8, insert dash before it
            //else, just insert input
            if(index == 5 || index == 12)
                return "-" + cur;
            else
                return cur;
        }).join('');
        
        //Return the new string
        $(this).val(input);
    });



    });
   
   $(function(){
      
      $('#eye').click(function(){
           
            if($(this).hasClass('fa-eye-slash')){
               
              $(this).removeClass('fa-eye-slash');
              
              $(this).addClass('fa-eye');
              
              $('#password').attr('type','text');
                
            }else{
             
              $(this).removeClass('fa-eye');
              
              $(this).addClass('fa-eye-slash');  
              
              $('#password').attr('type','password');
            }
        });
    });
    
</script>

