<!DOCTYPE html>
<html>

<head>
  <?php session()->regenerate(); ?>
  <meta charset="utf-8">
  <meta name="_token" content="{!! csrf_token() !!}" />
  <meta property="og:title" content="S!MVO McGill" />
  <meta property="og:description" content="S!MVO Allows students to plan their degrees using an intuitive web application. S!mvo was developed by students at McGill Univeristy."
  />
  <meta property="og:image" content="http://www.international.gouv.qc.ca/Content/Users/Documents/FicheContenu/263.jpg" />
  <meta property="og:type" content="website" />
  <title>S!MVO</title>
  <!-- Stylesheets for Material Design and FontAwesome-->
  <!-- Stylesheets -->
  <link href="{{ asset('css/landing.css')}}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
    crossorigin="anonymous">
    <!-- Javascript libraries -->
</head>

<body>
  <nav id="topNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
      </div>
      <div class="navbar-collapse collapse" id="bs-navbar">
        <ul class="nav navbar-nav">
          <li>
            <a class="page-scroll" href="#one">My Degree</a>
          </li>
          <li>
            <a class="page-scroll" href="#two">Students Of S!MVO</a>
          </li>
          <li>
            <a class="page-scroll" href="#three">Apply Now</a>
          </li>
          <li>
            <a class="page-scroll" href="#four">Affiliations</a>
          </li>
          <li>
            <a class="page-scroll" href="#five">What is S!MVO</a>
          </li>
          <li>
            <a class="page-scroll" href="#last">A Note from the Provost</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <header id="first">
    <div class="header-content">
      <div class="inner">
        <h1 id="landing-title"><img id="logo-lines" src="/media/SIMVO-LINES.png"
          /> S
          <span class="primary-color">!</span>MVO
        </h1>
        <h4 class="cursive landing-sub">Changing the Institution, One Project at A Time</h4>
        <hr>
        <a href="{{ route('flowchart') }}" class="btn btn-primary btn-xl page-scroll">Go To Degree Planner</a>
      </div>
    </div>
    <video autoplay="" loop="true" class="fillWidth fadeIn wow collapse in" data-wow-delay="0.5s" poster="" id="video-background">
      <source src="/media/test720.mp4" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
    </video>
  </header>
  <section class="" id="one">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary"><a href="{{ route('flowchart') }}">The Degree Planner</a></h2>
          <hr class="primary">
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 text-center">
          <div class="feature">
            <i class="icon-lg ion-android-laptop wow fadeIn" data-wow-delay=".3s"></i>
            <h3>Visualize Your Degree</h3>
            <p class="text-muted" style="margin-bottom: 10px">Choose your Major And Stream. Information Comes directly from McGill ensuring accuracy.</p>
          </div>
          <video autoplay="" loop="true" class="" data-wow-delay="0.5s" poster="" id="demo-vid">
            <source src="/media/SimvoVisualizeDegree.mov" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
          </video>
        </div>
        <div class="col-lg-6 col-md-12 text-center">
          <div class="feature">
            <i class="icon-lg ion-social-sass wow fadeInUp" data-wow-delay=".2s"></i>
            <h3>Preview Semesters</h3>
            <p class="text-muted">Drag courses into your current semester and quickly see what your schedule will look like.</p>
          </div>
          <video autoplay="" loop="true" class="" data-wow-delay="0.5s" poster="" id="demo-vid">
            <source src="/media/SimvoPreviewSched.mov" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
          </video>
        </div>
        <div class="col-lg-6 col-md-12 text-center col-centered">
          <div class="feature">
            <i class="icon-lg ion-ios-star-outline wow fadeIn" data-wow-delay=".3s"></i>
            <h3>Tweak to Your Liking!</h3>
            <p class="text-muted">Visualize your graduation date if you add a Minor, do an internship or a semester off.</p>
          </div>
          <video autoplay="" loop="true" class="" data-wow-delay="0.5s" poster="https://s3-us-west-2.amazonaws.com/coverr/poster/Traffic-blurred2.jpg"
            id="demo-vid">
            <source src="/media/SimvoAddInternship.mov" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
            </video>
        </div>
      </div>
    </div>
  </section>
  <section id="two" class="SOS-tab">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary">Students of S!MVO</h2>
          <hr class="primary">
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-5 text-center">
          <h2 class="sub-header" style="margin-bottom: 20px;">What is the Students of S!MVO Program?</h2>
          <p class="primary-text text-faded">The Students of S!MVO program is a 1 term educational program that will select 15-20 student applicants to develop
            and implement multiple S!MVO projects that focus on improving some aspect of McGill. Students will be split into
            dedicated project groups whilst being guided, trained and mentored throughout the semester. The projects available
            for this winter range from electronic financial modeling, to building a group study finder app, to writing and
            publishing an academic paper.</p>
        </div>
        <div class="col-lg-2 text-center"></div>
        <div class="col-lg-5 text-center">
          <h2 class="sub-header" style="margin-bottom: 20px;">Who Is The Program Suited for?</h2>
          <p class="primary-text text-faded">The Students of S!MVO program is McGill’s ideal opportunity to earn invaluable experience and develop a unique
            mindset critical for students wishing: To become valuable future employees with unique corporate and interpersonal
            skills Or especially Those wishing to become future entrepreneurs with their own idea Join a large group of motivated
            like-minded individuals, develop and share your experiences, and make an impact on your peers by enrolling in
            our Students of S!MVO program. If you are selected, you along with your team will be taking on one the following
            S!MVO projects:
          </p>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary">What We Offer</h2>
          <hr class="primary">
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-med-12 text-center">
          <h3 class="sub-header">Self Development</h3>
          <p class="primary-text text-faded">Special guest talks, team networking events, and lessons in psychology will shift your mindset and mentality about
            your life goals and ambitions. You will also develop key employability skills through completing and implementing
            your project for your peers to enjoy.</p>
        </div>
        <div class="col-lg-4 col-med-12 text-center">
          <h3 class="sub-header">Intristic</h3>
          <p class="primary-text text-faded">Few things are better than the joy of seeing your work come to life and impact the people and students around you.
          </p>
        </div>
        <div class="col-lg-4 col-med-12 text-center">
          <h3 class="sub-header">Benefits</h3>
          <p class="primary-text text-faded">Join a network of like minded students; enjoy weekly talks on entrepreneurship, key mentalities, and personal psychology;
            attend networking events; build friendships on your retreat to Mt. Tremblant. It is just like you are working
            for a company.
          </p>
        </div>
      </div>
    </div>
  </section>
  <section id="three" class="apply-tab">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary">Apply Today!</h2>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 text-center">
          <a class="app-link btn btn-success btn-lg" href="https://form.jotformeu.com/70064802041342">Universal Application</a>
        </div>
      </div>
    </div>
  </section>
  <section id="four" class="affiliations-tab">
    <div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary">Affiliations</h2>
          <hr class="default">
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-lg-4 text-center">
          <a href="http://d3center.ca/en/home/" target="_blank" class="logo-div">
            <img src="/media/D3Logo.png"
              class="img-responsive" alt="Image 1">
              <div class="gallery-box-caption">
                <div class="gallery-box-content">
                  <div>
                    <h3>D3</h3>
                  </div>
                </div>
              </div>
          </a>
        </div>
        <div class="col-lg-4 text-center">
          <a href="https://8courses.me/" target="_blank" class="logo-div">
            <img src="/media/8coursesLogo.png"
              class="img-responsive" alt="Image 1">
              <div class="gallery-box-caption">
                <div class="gallery-box-content">
                  <div>
                    <h3>8Courses</h3>
                  </div>
                </div>
              </div>
          </a>
        </div>
        <div class="col-lg-4 text-center">
          <a href="http://www.mcgilleus.ca/" target="_blank" class="logo-div">
            <img src="/media/eusLogo.png" style="margin-top: 80px"
              class="img-responsive" alt="Image 1">
              <div class="gallery-box-caption">
                <div class="gallery-box-content">
                  <div>
                    <h3>EUS</h3>
                  </div>
                </div>
              </div>
          </a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-centered text-center">
          <a href="http://mcgill.ca/" target="_blank" class="logo-div">
            <img src="/media/mcgillLogo.png"
              class="img-responsive" alt="Image 1">
              <div class="gallery-box-caption">
                <div class="gallery-box-content">
                  <div>
                    <h3>McGill University</h3>
                  </div>
                </div>
              </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <section id="five" class="about-tab">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary">What is S!MVO?</h2>
          <hr class="primary">
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <p class="primary-text text-faded">S!MVO is short for Simvolus, the greek word for “advisor”. It is the name behind our Interactive Degree Advising
            platform that you can access here if you are a McGill Engineer. S!MVO’s identity goes beyond just degree planning
            and extends towards the entirety of university life. The student body is McGill’s most valuable asset when it
            comes to improving this institution, as a result the Students of S!MVO program was deployed. With respect to
            this, S!MVO is an opportunity to develop unique hands-on experience, a gateway to a wider network and brighter
            career after graduation, your way into a talented, motivated, and truly unique group of people, the strategic
            choice to turn your vision into reality, a place to work hard, have fun, and learn more about yourself, all while
            making an impact on your peers and improving your university. So S!MVO is many things, but where is the fun in
            revealing it all? Apply now to find out more about us and our projects, or ask a Student of S!MVO when you see
            one. They can be spotted wearing our red/yellow/green striped hoodies around campus.
          </p>
        </div>
      </div>
    </div>
  </section>
  <div id="galleryModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <img src="//placehold.it/1200x700/222?text=..." id="galleryImage" class="img-responsive" />
          <p>
            <br/>
            <button class="btn btn-primary btn-lg center-block" data-dismiss="modal" aria-hidden="true">Close <i class="ion-android-close"></i></button>
          </p>
        </div>
      </div>
    </div>
  </div>
  <!--scripts loaded here from cdn for performance -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js"></script>
  <script type="text/javascript" src="{{ asset('/js/landing.js') }}"></script>
</body>