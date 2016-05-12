<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;

class RegistrationController extends Controller
{

  use ProgramTrait;
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
    $faculties = $this->getFaculties();

    array_unshift($faculties, "Select");

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

  public function getMajorsInFaculty(Request $request)
  {
    return json_encode($this->getMajors($request->faculty));
  }
}
