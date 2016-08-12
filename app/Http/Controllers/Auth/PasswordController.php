<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
     protected $redirectTo = '/flowchart';

    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
      $this->auth = $auth;
      $this->passwords = $passwords;
      $this->subject = 'Your Password Reset Link';
      $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */

    public function getEmail()
    {
        return view('auth.passwords.email');
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */

    public function getReset($token = null)
    {
        if (is_null($token))
        {
            throw new NotFoundHttpException;
        }
        return view('auth.passwords.reset')-> with('token', $token);
    }

    public function postEmail(Request $request)
    {
    $this->validate($request, ['email' => 'required:exists:users']);

    $response = $this->passwords->sendResetLink($request->only('email'), function($message)
    {
        $message->subject('Password Reminder');
    });

    switch ($response)
    {
        case PasswordBroker::RESET_LINK_SENT:
            return redirect()->back()->with('status', trans($response));

        case PasswordBroker::INVALID_USER:
            return redirect()->back()->withErrors(['email' => trans($response)]);
    }
  }

  public function postReset(Request $request)
  {
      $this->validate($request, [
          'token' => 'required',
          'email' => 'required',
          'password' => 'required|min:8|confirmed',
      ]);

      $credentials = $request->only(
          'email', 'password', 'password_confirmation', 'token'
      );

      $response = Password::reset($credentials, function ($user, $password) {
          $this->resetPassword($user, $password);
      });

      switch ($response) {
          case Password::PASSWORD_RESET:
              return redirect($this->redirectPath());

          default:
              return redirect()->back()
                          ->withInput($request->only('email'))
                          ->withErrors(['email' => trans($response)]);
      }
  }



}
