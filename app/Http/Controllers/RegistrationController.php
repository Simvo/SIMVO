<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use Auth;

class RegistrationController extends Controller
{

  use Traits\ProgramTrait;
  use Traits\Tools;
  /**
  * Function to Return Login Auth View
  **/
  public function loginView()
  {
    return view('auth.login');
  }


  /**
  * Function to Logout User
  **/
  public function logout()
  {
    if(Auth::Check())
    {
      Auth::logout();
    }

    return redirect('/');
  }

  /**
* Login Function
*
* @param Request; post
**/

public function login(Request $request)
{
  $this->validate($request, [
    'email'=>'required|exists:users,email',
    'password'=>'required|min:8'
    ]);

  if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
  {
    return redirect()->intended('flowchart');
  }

  else
  {
    return redirect()->back()->withErrors(['Could Not Authenticate You! Wrong Password!']);
  }
}
  /**
  * Function to Return Registration View Along with list of faculties and majors
  **/
  public function registrationView()
  {
    $faculties = $this->getFaculties();

    array_unshift($faculties, "Select");

    $semesters = $this->generateListOfSemesters(10);

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
    'Semester' => 'required|digits:1,2',
    'Faculty' => 'required|digits:1,2',
    'Major' => 'required',
    'Cegep' => 'required|digits:1'
    ]);

    $semesters = $this->generateListOfSemesters(10);
    $faculties = $this->getFaculties();

    $new_user = new User();
    $new_user->firstName = htmlentities($request->First_Name);
    $new_user->lastName = htmlentities($request->Last_Name);
    $new_user->email = htmlentities($request->Email);
    $new_user->programID = htmlentities($request->Major);
    $new_user->faculty = $faculties[$request->Faculty - 1];
    $new_user->password = bcrypt($request->Password);
    $new_user->enteringSemester = $this->encode_semester($semesters[$request->Semester]);
    $new_user->cegepEntry = $request->Cegep;
    $new_user->save();

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
