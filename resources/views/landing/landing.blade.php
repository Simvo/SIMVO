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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
            <a class="page-scroll" href="#last">What is S!MVO</a>
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
        <h1>S!IMVO McGill</h1>
        <h4 class= "cursive">Changing the Institution, One Project at A Time</h4>
        <hr>
      </div>
    </div>
    <video autoplay="" loop="true" class="fillWidth fadeIn wow collapse in" data-wow-delay="0.5s" poster="https://s3-us-west-2.amazonaws.com/coverr/poster/Traffic-blurred2.jpg"
      id="video-background">
      <source src="/media/McGillTour.mp4" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
    </video>
  </header>
  <section class="" id="one">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="margin-top-0 text-primary">The Degree Planner</h2>
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
          <video autoplay="" loop="true" class="" data-wow-delay="0.5s" poster="https://s3-us-west-2.amazonaws.com/coverr/poster/Traffic-blurred2.jpg"
            id="demo-vid">
            <source src="/media/SimvoVisualizeDegree.mov" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
          </video>
        </div>
        <div class="col-lg-6 col-md-12 text-center">
          <div class="feature">
            <i class="icon-lg ion-social-sass wow fadeInUp" data-wow-delay=".2s"></i>
            <h3>Preview Semesters</h3>
            <p class="text-muted">Drag courses into your current semester and quickly see wht your schedule will look like.</p>
          </div>
          <video autoplay="" loop="true" class="" data-wow-delay="0.5s" poster="https://s3-us-west-2.amazonaws.com/coverr/poster/Traffic-blurred2.jpg"
            id="demo-vid">
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
          <h2 class="text-primary" style="margin-bottom: 20px;">What is the Students of S!MVO Program?</h2>
          <p class="primary-text text-faded">The Students of S!MVO program is a 1 term educational 
          program that will select 15-20 student applicants to develop and implement 
          multiple S!MVO projects that focus on improving some aspect of McGill. 
          Students will be split into dedicated project groups whilst being guided, 
          trained and mentored throughout the semester. The projects available for 
          this winter range from electronic financial modeling, to building a group 
          study finder app, 
          to writing and publishing an academic paper.</p>
        </div>
        <div class="col-lg-2 text-center"></div>
        <div class="col-lg-5 text-center">
          <h2 class="text-primary" style="margin-bottom: 20px;">Who Is The Program Suited for?</h2>
          <p class="primary-text text-faded">The Students of S!MVO program is McGill’s ideal opportunity to earn invaluable experience and develop a unique mindset critical for students wishing:
            To become valuable future employees with unique corporate and interpersonal skills

            Or especially Those wishing to become future entrepreneurs with their own idea

            Join a large group of motivated like-minded individuals, develop and share your experiences, and make an impact on your peers by enrolling in our Students of S!MVO program. 

            If you are selected, you along with your team will be taking on one the following S!MVO projects:
          </p>
        </div>
      </div>
    </div>
  </section>
  <section id="three" class="no-padding">
    <div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-lg-4 col-sm-6">
          <a href="#galleryModal" class="gallery-box" data-toggle="modal" data-src="//splashbase.s3.amazonaws.com/unsplash/regular/photo-1430916273432-273c2db881a0%3Fq%3D75%26fm%3Djpg%26w%3D1080%26fit%3Dmax%26s%3Df047e8284d2fdc1df0fd57a5d294614d">
            <img src="//splashbase.s3.amazonaws.com/unsplash/regular/photo-1430916273432-273c2db881a0%3Fq%3D75%26fm%3Djpg%26w%3D1080%26fit%3Dmax%26s%3Df047e8284d2fdc1df0fd57a5d294614d"
              class="img-responsive" alt="Image 1">
            <div class="gallery-box-caption">
              <div class="gallery-box-content">
                <div>
                  <i class="icon-lg ion-ios-search"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a href="#galleryModal" class="gallery-box" data-toggle="modal" data-src="//splashbase.s3.amazonaws.com/getrefe/regular/tumblr_nqune4OGHl1slhhf0o1_1280.jpg">
            <img src="//splashbase.s3.amazonaws.com/getrefe/regular/tumblr_nqune4OGHl1slhhf0o1_1280.jpg" class="img-responsive" alt="Image 2">
            <div class="gallery-box-caption">
              <div class="gallery-box-content">
                <div>
                  <i class="icon-lg ion-ios-search"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a href="#galleryModal" class="gallery-box" data-toggle="modal" data-src="//splashbase.s3.amazonaws.com/unsplash/regular/photo-1433959352364-9314c5b6eb0b%3Fq%3D75%26fm%3Djpg%26w%3D1080%26fit%3Dmax%26s%3D3b9bc6caa190332e91472b6828a120a4">
            <img src="//splashbase.s3.amazonaws.com/unsplash/regular/photo-1433959352364-9314c5b6eb0b%3Fq%3D75%26fm%3Djpg%26w%3D1080%26fit%3Dmax%26s%3D3b9bc6caa190332e91472b6828a120a4"
              class="img-responsive" alt="Image 3">
            <div class="gallery-box-caption">
              <div class="gallery-box-content">
                <div>
                  <i class="icon-lg ion-ios-search"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a href="#galleryModal" class="gallery-box" data-toggle="modal" data-src="//splashbase.s3.amazonaws.com/lifeofpix/regular/Life-of-Pix-free-stock-photos-moto-drawing-illusion-nabeel-1440x960.jpg">
            <img src="//splashbase.s3.amazonaws.com/lifeofpix/regular/Life-of-Pix-free-stock-photos-moto-drawing-illusion-nabeel-1440x960.jpg"
              class="img-responsive" alt="Image 4">
            <div class="gallery-box-caption">
              <div class="gallery-box-content">
                <div>
                  <i class="icon-lg ion-ios-search"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a href="#galleryModal" class="gallery-box" data-toggle="modal" data-src="//splashbase.s3.amazonaws.com/lifeofpix/regular/Life-of-Pix-free-stock-photos-new-york-crosswalk-nabeel-1440x960.jpg">
            <img src="//splashbase.s3.amazonaws.com/lifeofpix/regular/Life-of-Pix-free-stock-photos-new-york-crosswalk-nabeel-1440x960.jpg"
              class="img-responsive" alt="Image 5">
            <div class="gallery-box-caption">
              <div class="gallery-box-content">
                <div>
                  <i class="icon-lg ion-ios-search"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-lg-4 col-sm-6">
          <a href="#galleryModal" class="gallery-box" data-toggle="modal" data-src="//splashbase.s3.amazonaws.com/lifeofpix/regular/Life-of-Pix-free-stock-photos-clothes-exotic-travel-nabeel-1440x960.jpg">
            <img src="//splashbase.s3.amazonaws.com/lifeofpix/regular/Life-of-Pix-free-stock-photos-clothes-exotic-travel-nabeel-1440x960.jpg"
              class="img-responsive" alt="Image 6">
            <div class="gallery-box-caption">
              <div class="gallery-box-content">
                <div>
                  <i class="icon-lg ion-ios-search"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
  <section class="container-fluid" id="four">
    <div class="row">
      <div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
        <h2 class="text-center text-primary">Features</h2>
        <hr>
        <div class="media wow fadeInRight">
          <h3>Simple</h3>
          <div class="media-body media-middle">
            <p>What could be easier? Get started fast with this landing page starter theme.</p>
          </div>
          <div class="media-right">
            <i class="icon-lg ion-ios-bolt-outline"></i>
          </div>
        </div>
        <hr>
        <div class="media wow fadeIn">
          <h3>Free</h3>
          <div class="media-left">
            <a href="#alertModal" data-toggle="modal" data-target="#alertModal"><i class="icon-lg ion-ios-cloud-download-outline"></i></a>
          </div>
          <div class="media-body media-middle">
            <p>Yes, please. Grab it for yourself, and make something awesome with this.</p>
          </div>
        </div>
        <hr>
        <div class="media wow fadeInRight">
          <h3>Unique</h3>
          <div class="media-body media-middle">
            <p>Because you don't want your Bootstrap site, to look like a Bootstrap site.</p>
          </div>
          <div class="media-right">
            <i class="icon-lg ion-ios-snowy"></i>
          </div>
        </div>
        <hr>
        <div class="media wow fadeIn">
          <h3>Popular</h3>
          <div class="media-left">
            <i class="icon-lg ion-ios-heart-outline"></i>
          </div>
          <div class="media-body media-middle">
            <p>There's good reason why Bootstrap is the most used frontend framework in the world.</p>
          </div>
        </div>
        <hr>
        <div class="media wow fadeInRight">
          <h3>Tested</h3>
          <div class="media-body media-middle">
            <p>Bootstrap is matured and well-tested. It's a stable codebase that provides consistency.</p>
          </div>
          <div class="media-right">
            <i class="icon-lg ion-ios-flask-outline"></i>
          </div>
        </div>
      </div>
    </div>
  </section>
  <aside class="bg-dark">
    <div class="container text-center">
      <div class="call-to-action">
        <h2 class="text-primary">Get Started</h2>
        <a href="http://www.bootstrapzero.com/bootstrap-template/landing-zero" target="ext" class="btn btn-default btn-lg wow flipInX">Free Download</a>
      </div>
      <br>
      <hr/>
      <br>
      <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
          <div class="row">
            <h6 class="wide-space text-center">BOOTSTRAP IS BASED ON THESE STANDARDS</h6>
            <div class="col-sm-3 col-xs-6 text-center">
              <i class="icon-lg ion-social-html5-outline" title="html 5"></i>
            </div>
            <div class="col-sm-3 col-xs-6 text-center">
              <i class="icon-lg ion-social-sass" title="sass"></i>
            </div>
            <div class="col-sm-3 col-xs-6 text-center">
              <i class="icon-lg ion-social-javascript-outline" title="javascript"></i>
            </div>
            <div class="col-sm-3 col-xs-6 text-center">
              <i class="icon-lg ion-social-css3-outline" title="css 3"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </aside>
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
  <div id="alertModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <h2 class="text-center">Nice Job!</h2>
          <p class="text-center">You clicked the button, but it doesn't actually go anywhere because this is only a demo.</p>
          <p class="text-center"><a href="http://www.bootstrapzero.com">Learn more at BootstrapZero</a></p>
          <br/>
          <button class="btn btn-primary btn-lg center-block" data-dismiss="modal" aria-hidden="true">OK <i class="ion-android-close"></i></button>
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