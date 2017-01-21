<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Darubini Real Estate</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

       <style>
        {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    </style>
</head>

    <!-- Styles -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/materialize.min.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/styles.css') }}">  
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/w3.css') }}">  
    <!-- Scripts -->
 
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57e5bf9489c7acbf"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      
    </head>
    <body>
        @yield('navigation')
        <div class="navbar-fixed">
          <nav class="main-color">
            <div class="nav-wrapper">
              <a class="brand-logo" href="{{ url('/') }}">
                 Darubini
            </a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="{{ url('/') }}"> Home</a></li>
                <li><a href="{{ url('/about-us') }}">Properties</a></li>
                <li><a href="{{ url('/members') }}"> Alerts</a></li>
                <li><a href="{{ url('/chat') }}">Dashboard </a></li>
                <li><a href="{{ url('/contact-us') }}"> Contact us </a></li>
                <!-- Authentication Links -->
                @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                <li >
                    <a class="dropdown" href='#' data-activates='dropdown1'>
                        {{ Auth::user()->name }} <i class="material-icons right">arrow_drop_down</i>
                    </a>

                    <ul id='dropdown1' class='dropdown-content'>
                     <li class="divider"></li>
                    <li class="main-color">
                       <a href="{{ url('/member-profile') }}" class="white-text">Dashboard </a>
                    </li>
                     <li class="divider"></li>
                        <li class="main-color">
                            <a href="{{ url('/logout') }}" class="white-text"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
        <ul class="side-nav main-color" id="mobile-demo">
           <li class=""><a  class="white-text" href="{{ url('/') }}"><i class="fa-btn fa fa-home"></i> Home</a></li>
           <li class=""><a class="white-text" href="{{ url('/about-us') }}"><i class="fa-btn fa fa-home"></i> Houses</a></li>
           <li class=""><a class="white-text" href="{{ url('/about-us') }}"><<i class="fa-btn fa fa-square"></i>Land</a></li>
           <li><a  class="white-text" href="{{ url('/members') }}"><i class="fa-btn fa fa-envelope"></i> Alerts</a></li>
           <li><a  class="white-text" href="{{ url('/contact-us') }}"> <i class="fa-btn fa fa-phone"></i> Contact us</a></li>
             @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                <li >
                    <a class="dropdown" href='#' data-activates='dropdown1'>
                        {{ Auth::user()->name }} <i class="material-icons right">arrow_drop_down</i>
                    </a>

                    <ul id='dropdown1' class='dropdown-content'>
                     <li class="divider"></li>
                    <li class="main-color">
                       <a href="{{ url('/member-profile') }}" class="white-text">Dashboard </a>
                    </li>
                     <li class="divider"></li>
                        <li class="main-color">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
            @endif
       </ul>
   </div>
</nav>
</div>

@yield('content')

@yield('mainfooter')

 <footer class="page-footer main-color myfooter" >
 <div class="main-color">
  <div class="container main-color">
    <div class="row">
      <div class="col l6 s12">
        <h5 class="white-text">Darubini Real Estate</h5>
        <p class="grey-text text-lighten-4">Kenya's number one property market</p>
    </div>
    <div class="col l3 s6 m6 offset-l0 ">
        <ul class="footerlist">
         <li><a href="{{ url('/about') }}">About Us</a></li>
            <li><a href="{{ url('/contact') }}">Contact Us</a></li>
            <li><a href="{{ url('/selling-buying-tips') }}">Selling & Buying Tips</a></li>
      </ul>
  </div>
  <div class="col l3 s6 m6 offset-l0 ">
   <ul class="footerlist">
            <li><a href="{{ url('/terms-and-conditions') }}">Terms and Condition</a></li>
            <li><a href="{{ url('/privacy') }}">Privacy Statement</a></li>
            <li><a href="{{ url('/add-property') }}">Add property</a></li>
            <li><a href="{{ url('/subscribe/subscribe-alert') }}">Subscribe Alert</a></li>
        </ul>
</div>
</div>
</div>
<div class="footer-copyright">
    <div class="container">
        © 2016 Darubini Real Estate
        <a class="grey-text text-lighten-4 right" href="#!">Darubini</a>
    </div>
</div>
</div>
</footer> 

@yield('jsimport')
<!-- Scripts -->
<!-- <script src="/js/app.js"></script> -->
 
<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> -->
<script type="text/javascript" src="{{ asset('assets/js/jquery-2.2.4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/materialize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.nicescroll.plus.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.scrolline.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/skrollr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/velocity.min.js') }}"></script>
<!-- <script type="text/javascript" src="{{ asset('assets/js/google-map.js') }}"></script> -->
<script type="text/javascript">
   $(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal-trigger').leanModal();

    $('select').material_select();

    $('.carousel').carousel();

     
});
</script>
</body>
</html>
