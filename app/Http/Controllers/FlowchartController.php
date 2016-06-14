<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;


use App\Http\Requests;

class FlowchartController extends Controller
{

  use Traits\ProgramTrait;
  use Traits\tools;

    /**
    * Function called upon GET request. Will determine if schedule needs to be generated or simply displayed
    * Consists of generating four main parts.
    * 1) Progress Bar 2)Course Schedule 3) Complementary Courses 4) Elecitive Courses
    **/
    public function generateFlowChart()
    {
      $user=Auth::User();
      $groupsWithCourses = null;

      //If user has not yet setup courses or recommended Stream is not provided
      $groupsWithCourses = $this->getGroupsWithCourses($user->programID);

      $progress = $this->generateProgressBar($user->programID);

      return view('flowchart', [
        'user'=>$user,
        'progress' => $progress,
        'groupsWithCourses' => $groupsWithCourses
      ]);
    }

    public function generateProgressBar($programID)
    {
      $groups = $this->getGroupsWithCredits($programID);

      $progress = [];

      foreach ($groups as $key=>$value)
      {
        $courses = $this->getCoursesInGroup($programID, $key);

        $totCredits = $value;
        $creditsTaken = 0;

        foreach ($courses as $course)
        {
        }

        $progress[$key] = [0,$value];
      }
      return $progress;
    }
}
