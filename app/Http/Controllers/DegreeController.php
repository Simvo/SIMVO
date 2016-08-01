<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DegreeController extends Controller
{
  // traits the controller will use
  use Traits\ProgramTrait;
  use Traits\Tools;

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
}
