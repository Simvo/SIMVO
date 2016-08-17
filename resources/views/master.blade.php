<!DOCTYPE html>
<html>
  <head>
    <?php session()->regenerate(); ?>
    <meta charset="utf-8">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta property="og:title" content="S!MVO McGill" />
    <meta property="og:description" content="S!MVO Allows students to plan their degrees using an intuitive web application. S!mvo was developed by students at McGill Univeristy." />
    <meta property="og:image" content="http://www.international.gouv.qc.ca/Content/Users/Documents/FicheContenu/263.jpg"/>
  	<meta property="og:type" content="website"/>
    <title>S!MVO</title>
    <!-- Stylesheets for Material Design and FontAwesome-->
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.red-indigo.min.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Stylesheets -->
    <link href="{{ asset('css/foundation.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/BootstrapStyle.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/registrationPage.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/loginPage.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/flowchartStyle.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/landing.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/flowchartGroup.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/CustomCard.css')}}" rel="stylesheet" type="text/css">
    <!-- Javascript libraries -->
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  </head>
  <body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
          @if (isset($user) && $user)
            <span class="mdl-layout-title">Hello&nbsp{{$user->firstName}}!</span>
          @else
            <span class="mdl-layout-title">Simvo McGill</span>
          @endif


          <div class="mdl-layout-spacer"></div>
          <nav class="mdl-navigation mdl-layout--large-screen-only">
            @if (isset($user) && $user)
              <a class="mdl-navigation__link" href="{{ route('logout') }}">Logout</a>
            @else
              <a class="mdl-navigation__link" href="{{ route('loginView') }}">Login</a>
            @endif

          </nav>
        </div>
      </header>

      <main class="mdl-layout__content">
        <div class="page-content">
          @yield('landing')
          @yield('login')
          @yield('registration')
          @yield('flowchart')
          @yield('passwordforgot')
          @yield('passwordreset')
        </div>
      </main>
    </div>

    <!-- Javascript Files and Libraries -->
    <script defer src="https://code.getmdl.io/1.1.2/material.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/SortableRendering.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tools.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/DegreeAjax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/flowchart.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/foundation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/foundation.reveal.js') }}"></script>
    <script type="text/javascript"> $(document).foundation(); </script>


  </body>
</html>
