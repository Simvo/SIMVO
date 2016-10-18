<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;

use App\Degree;

class DegreeController extends Controller
{
  // traits the controller will use
  use Traits\ProgramTrait;
  use Traits\Tools;
  use Traits\ScheduleTrait;

  public function getMajorsInFaculty(Request $request)
  {
    return json_encode($this->getMajors($request->faculty));
  }

  public function getProgramVersionsInMajor(Request $request)
  {
    return json_encode($this->getProgramVersions($request->program_id));
  }

  public function getProgramStreams(Request $request)
  {
    return json_encode($this->getStreams($request->program_id, $request->version));
  }

  public function getSemesters(Request $request)
  {
    return json_encode($this->getProperSemesters($request->semesters));
  }

  public function deleteDegree(Request $request)
  {
    $degree = Session::get('degree');\

    Session::forget('degree');

    if($degree == null)
    {
      return;
    }

    // Remove all instances of schedule belongin to the degree
    $this->emptySchedule($degree);

    $degree = Degree::where('id', $degree->id)->first();
    $degree->delete();

    return redirect('/flowchart');
  }
}
