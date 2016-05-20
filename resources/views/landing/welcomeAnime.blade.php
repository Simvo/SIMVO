@extends('master')

@section('landing')
<script type="text/javascript">
window.addEventListener('load',function(){
var welcome = document.querySelector('.greet'),
    subtext = document.querySelector('.subTexts'),
    form    = document.querySelector('.sub'),
    follow  = document.querySelector('.followUs'),
    social  = document.querySelectorAll('.socialIcon'),
    delay = 1000;


setTimeout(function(){welcome.style.top='0';},delay);
setTimeout(function(){subtext.style.bottom = '0%';},delay*2);
setTimeout(function(){subtext.style.bottom = '-100%';},delay*4);
setTimeout(function(){form.style.opacity='1';},delay*5);
setTimeout(function(){follow.style.bottom='0%';},delay*6);
setTimeout(
  function(){
    social[0].style.marginTop='0px';
    social[1].style.marginTop='0px';
    social[2].style.marginTop='0px';
  },delay*7
);

});




</script>
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
      <div class="container_card mdl-card mdl-shadow--2dp">
        <div id="mdl-card__title">
          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--2-col"></div>
              <div class="mdl-cell mdl-cell--8-col">
                <div class="test_card mdl-card mdl-shadow--4dp">
                  <div class="mdl-card__title">
                    <div class="content">
                      <div class="welcomeText">
                        <div class="welcome">
                          <h1 class="greet">WELCOME</h1>
                        </div>
                        <div class="subText">
                          <div class="subTexts">
                            <p class="subscribe">Subscribe to our newsletter</p>
                            <p class="soon">We are launching soon</p>
                           </div>
                        </div>
                      </div>

                      <div class="form">
                        <form action="" class="sub">
                          <input class='email' type="email">
                          <button class="button" value="Send">Subscribe</button>
                        </form>
                      </div>

                      <div class="social">
                        <div class="follow">
                          <p class="followUs">Or follow us</p>
                        </div>
                        <div class="socialIcons">
                          <div class="socialIcon facebook brandico-facebook-rect"></div>
                          <div class="socialIcon  twitter brandico-twitter-bird"></div>
                          <div class="socialIcon  github brandico-github"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>



              <div class="mdl-cell mdl-cell--2-col"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
