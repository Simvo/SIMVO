<!DOCTYPE html>
<html>
  <head>
    <?php session()->regenerate(); ?>
    <meta charset="utf-8">
    <meta name="_token" content="<?php echo csrf_token(); ?>"/>
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
    <link href="<?php echo e(asset('css/foundation.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('css/BootstrapStyle.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/registrationPage.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/loginPage.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/flowchartStyle.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/landing.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('css/flowchartGroup.css')); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo e(asset('css/CustomCard.css')); ?>" rel="stylesheet" type="text/css">
    <!-- Javascript libraries -->
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/jquery.ui.touch-punch.min.js')); ?>"></script>

    <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
    0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
    for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
    mixpanel.init("56f94a0225a467da44d64a0fbe2cf363");</script><!-- end Mixpanel -->
  </head>
  <body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
          <?php if(isset($user) && $user): ?>
            <span class="mdl-layout-title">Hello&nbsp<?php echo e($user->firstName); ?>!</span>
          <?php else: ?>
            <span class="mdl-layout-title">Simvo McGill</span>
          <?php endif; ?>


          <div class="mdl-layout-spacer"></div>
          <nav class="mdl-navigation mdl-layout--large-screen-only">
            <?php if(isset($user) && $user): ?>
              <a class="mdl-navigation__link" href="<?php echo e(route('logout')); ?>">Logout</a>
            <?php else: ?>
              <a class="mdl-navigation__link" href="<?php echo e(route('loginView')); ?>">Login</a>
            <?php endif; ?>

          </nav>
        </div>
      </header>

      <main class="mdl-layout__content">
        <div class="page-content">
          <?php echo $__env->yieldContent('landing'); ?>
          <?php echo $__env->yieldContent('login'); ?>
          <?php echo $__env->yieldContent('registration'); ?>
          <?php echo $__env->yieldContent('flowchart'); ?>
          <?php echo $__env->yieldContent('passwordforgot'); ?>
          <?php echo $__env->yieldContent('passwordreset'); ?>
        </div>
      </main>
    </div>

    <!-- Javascript Files and Libraries -->
    <script defer src="https://code.getmdl.io/1.1.2/material.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/SortableRendering.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/tools.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/flowchartStyle.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/DegreeAjax.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('js/flowchart.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/js/foundation.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/js/foundation.reveal.js')); ?>"></script>
    <script type="text/javascript"> $(document).foundation(); </script>


  </body>
</html>
