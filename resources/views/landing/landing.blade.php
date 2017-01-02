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
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.red-indigo.min.css"/>
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
    <script type="text/javascript" src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
  </head>
  <body>
    <div class="demo-layout-transparent mdl-layout mdl-js-layout">
      <header class="mdl-layout__header mdl-layout__header--transparent">
        <div class="mdl-layout__header-row">
          <!-- Title -->
          <span class="mdl-layout-title">S!MVO McGill</span>
          <!-- Add spacer, to align navigation to the right -->
          <div class="mdl-layout-spacer"></div>
          <!-- Navigation -->
          <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="">Home</a>
            <a class="mdl-navigation__link" href="">My Degree</a>
            <a class="mdl-navigation__link" href="">Students of S!MVO</a>
            <a class="mdl-navigation__link" href="">Apply Now</a>
            <a class="mdl-navigation__link" href="">Affiliations</a>
            <a class="mdl-navigation__link" href="">What is S!IMVO</a>
            <a class="mdl-navigation__link" href="">A Note from the Provost</a>
          </nav>
        </div>
      </header>
      <main class="mdl-layout__content">
      </main>
    </div>
  </body>
