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
              {!! Form::open(['route' => 'passwordResetPost']) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="token" value="{{ $token }}" />
                <ul class="list-style-none">
                  <li>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                        {!! Form::label('email', 'E-mail', ['class'=> 'mdl-textfield__label']) !!}
                        {!! Form::email('email', old('email'), ['class'=> 'mdl-textfield__input','name'=>"email"]) !!}
                    </div>
                  </li>
                  <li>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                      {!! Form::label('Password', 'Password', ['class'=> 'mdl-textfield__label']) !!}
                      {!! Form::password('password', ['class'=> 'mdl-textfield__input']) !!}
                    </div>
                  </li>
                  <li>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo">
                      {!! Form::label('Confirm_Password', 'Confirm Password', ['class'=> 'mdl-textfield__label']) !!}
                      {!! Form::password('password_confirmation', ['class'=> 'mdl-textfield__input']) !!}
                    </div>
                  </li>
                  <li>
                    {!! Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent']) !!}
                  </li>
              </ul>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
