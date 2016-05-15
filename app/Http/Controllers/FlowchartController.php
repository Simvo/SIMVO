<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Http\Requests;

class FlowchartController extends Controller
{

  use ProgramTrait;
  use tools;
    /**
    * Function called upon GET request. Will determine if shedule needs to be generated or simply displayed
    * Consists of generating four main parts.
    * 1) Progress Bar 2)Course Schedule 3) Complementary Courses 4) Elecitive Courses
    **/
    public function generateFlowChart()
    {
      $user=Auth::User();

      $groups = $this->generateProgressBar($user->major);

      return view('flowchart', [
        'user'=>$user,
        'groups' => $groups
      ]);
    }

    public function generateProgressBar($programID)
    {
      $groups = $this->getGroups($programID);

      $courses = $this->getCoursesInGroup($programID, $groups[0]);

      return $groups;
    }
}
