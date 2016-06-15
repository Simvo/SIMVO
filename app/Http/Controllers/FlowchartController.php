<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Schedule;
use App\UICourse;
use DB;


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

      //load excpemtions
      $exemptions = $this->getExemptions($user);

      //If user has not yet setup courses or recommended Stream is not provided
      $groupsWithCourses = $this->getGroupsWithCourses($user->programID);


      $progress = $this->generateProgressBar($user->programID);

      return view('flowchart', [
        'user'=>$user,
        'progress' => $progress,
        'groupsWithCourses' => $groupsWithCourses,
        'exemptions' => $exemptions
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

    public function getExemptions($user)
    {
      $exemptions_PDO = Schedule::where('user_id',$user->id)
                        ->where('semester', 'exemption')
                        ->get();
      $exemptions = [];

      foreach ($exemptions_PDO as $exemption)
      {
        $status = DB::table('programs')->where('PROGRAM_ID', $user->programID)
                  ->where('SUBJECT_CODE', $exemption->SUBJECT_CODE)
                  ->where('COURSE_NUMBER', $exemption->COURSE_NUMBER)
                  ->first(['SET_TYPE']);

        $exemptions[] = [$exemption->id, $exemption->SUBJECT_CODE, $exemption->COURSE_NUMBER, $exemption->COURSE_CREDITS, $status->SET_TYPE];
      }

      return $exemptions;
    }
}
