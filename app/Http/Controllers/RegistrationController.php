<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;

class RegistrationController extends Controller
{
  /**
  * Function to Return Login Auth View
  **/
  public function loginView()
  {
    return view('auth.login');
  }

  /**
  * Function to Return Registration View Along with list of faculties and majors
  **/
  public function registrationView()
  {
    $faculties_PDO = DB::table('programs')
                  ->groupBy('PROGRAM_TEACHING_FACULTY')
                  ->get(['PROGRAM_TEACHING_FACULTY']);

    $faculties = [];

    foreach($faculties_PDO as $fac)
    {
      $faculties[] = $fac->PROGRAM_TEACHING_FACULTY;
    }

    return view('auth.registration',[
      'faculties'=> $faculties
    ]);
  }

  /**
  * Function to Validate and execute New User Registration Form
  **/
  public function newUserRegistration(Request $request)
  {
    $this->validate($request, [
    'Email'=>'required|min:6|unique:auth_users,email_prefix',
    'First_Name' => 'required',
    'Last_Name' => 'required',
    'Password' => 'required|min:8|same:Confirm_Password',
    'Confirm_Password' => 'required|min:8|same:Password',
    // 'Semester' => 'required|digits:1',
    // 'Major' => 'required|digits:1',
    // 'Minor' => 'digits_between:1,2',
    ]);

    User::create([
      'firstName' => htmlentities($request->First_Name),
      'lastName' => htmlentities($request->Last_Name),
      'password' => bcrypt($request->Password)
    ]);

    if(Auth::attempt(['email' => $request->Email, 'password' => $request->Password]))
    {
      return redirect()->intended('flowchart');
    }

  }
}
