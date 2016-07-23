@extends('master')

@section('passwordreset')
<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--3-col"></div>
    <div class="mdl-cell mdl-cell--6-col">
      <div class="mdl-card mdl-shadow--2dp registration_card">
        <div class="mdl-card__title mdl-card--expand">
          <h4>Password Reset</h4>
        </div>
        <div class="mdl-card__actions mdl-card--border">
          <ul class="list-style-none">
            @foreach ($errors->all() as $error)
              <li class="submit_error">{{ $error }}</li>
            @endforeach
          </ul>
          <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__panel is-active" id="login-panel">
              <form style="width:100%;" role="form" method="POST" action="{{ url('/password/reset') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
                <ul class="list-style-none">
                  <li>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                      <label class='mdl-textfield__label'>Email</label>
                      <input type="mdl-textfield__input" name="email" value="{{ $email or old('email') }}" />
                    </div>
                  </li>
                  <li>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                      <label class='mdl-textfield__label'>Password</label>
                      <input type="password" class="mdl-textfield__input" name="password">
                    </div>
                  </li>
                  <li>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                      <label class='mdl-textfield__label'>Password</label>
                      <input type="password" class="mdl-textfield__input" name="password_confirmation" />
                    </div>
                  </li>
                  <li>
                    {!! Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
                  </li>
              </ul>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
