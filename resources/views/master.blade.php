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
    <!-- Stylesheets -->
    <link href="{{ asset('css/registrationPage.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/loginPage.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
    <!-- Stylesheets for Material Design and FontAwesome-->
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.red-indigo.min.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


    <!-- Javascript libraries -->
  </head>
  <body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Simvo McGill</span>

          <div class="mdl-layout-spacer"></div>
          <nav class="mdl-navigation mdl-layout--large-screen-only">
            <a class="mdl-navigation__link" href="{{ route('login') }}">Login</a>
          </nav>
        </div>
      </header>

      <main class="mdl-layout__content">
        <div class="page-content">
          @yield('login')
          @yield('registration')
        </div>
      </main>
    </div>

    <!-- Javascript Files and Libraries -->
    <script defer src="https://code.getmdl.io/1.1.2/material.min.js"></script>
  </body>
</html>
