<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use Auth;

class RegistrationController extends Controller
{

  use ProgramTrait;
  use Tools;

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

    $current_semester = $this->get_current_semester();

    $semesters = [$this->get_semester($current_semester)];

    for ($i=0; $i <10 ; $i++)
    {
      array_push($semesters, $this->get_semester($this->get_previous_semester($this->encode_semester($semesters[count($semesters)-1]))));
    }

    return view('auth.registration',[
      'faculties'=> $faculties,
      'semesters'=> $semesters
    ]);
  }

  /**
  * Function to Validate and execute New User Registration Form
  **/
  public function newUserRegistration(Request $request)
  {
    $this->validate($request, [
    'Email'=>'required|min:6|unique:users,email',
    'First_Name' => 'required',
    'Last_Name' => 'required',
    'Password' => 'required|min:8|same:Confirm_Password',
    'Confirm_Password' => 'required|min:8|same:Password',
    'Semester' => 'required',
    'Faculty' => 'required',
    'Major' => 'required',
    ]);

    User::create([
      'firstName' => htmlentities($request->First_Name),
      'lastName' => htmlentities($request->Last_Name),
      'email' => htmlentities($request->Email),
      'faculty'
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
