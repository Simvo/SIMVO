@extends('master')
@section('login')
<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--3-col"></div>
  <div class="mdl-cell mdl-cell--6-col">
    <div class="mdl-card mdl-shadow--2dp auth_card">

      <div class="mdl-card__title mdl-card--expand"><h2 class="mdl-card__title-text">Login</h2></div>

      <div class="mdl-card__supporting-text">
        <ul>
          @foreach($errors->all() as $error)
            <li><p class="submit_error">{{$error}}</p></li>
          @endforeach
        </ul>


        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">

          <div class="mdl-tabs__panel is-active" id="login-panel">
            {!! Form::open() !!}
            <ul class="list-style-none">
              <li>
                  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                    {!! Form::label('email', 'E-mail', ['class'=> 'mdl-textfield__label']) !!}
                    {!! Form::email('email', null, ['class'=> 'mdl-textfield__input']) !!}
                  </div>
              </li>

              <li>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                  {!! Form::label('password', 'Password', ['class'=> 'mdl-textfield__label']) !!}
                  {!! Form::password('password', ['class'=> 'mdl-textfield__input']) !!}
                </div>
              </li>

              <li>
                {!! Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}

              </li>
            </ul>
            {!! Form::close() !!}

              <div class="center"><a href="{{ route('registration') }}">Not Signed up? Click here to Get Started!</a></div>
              <div class="center"><a>Forgot Password?</a></div>
          </div>

        </div>

      </div>
    </div>

  </div>
</div>
</div>
@stop
