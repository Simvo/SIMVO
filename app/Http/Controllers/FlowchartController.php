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

      $schedule_check = Schedule::where('user_id', $user->id)
                        ->where('semester', "<>", 'exemption')
                        ->count();

      //If (user has not yet setup courses or recommended Stream is not provided)
      if($schedule_check == 0)
      {
        $groupsWithCourses = $this->getGroupsWithCourses($user->programID);

        //Get User's entering semester
        $startingSemester = $user->enteringSemester;

        $schedule = [];
        $schedule[$this->get_semester($user->enteringSemester)] = [0,[],$startingSemester];
      }

      //if user has not completed initial setup: ie, some courses are in the schedule but some remain in the setup area
      if($schedule_check > 0)
      {
        $schedule = $this->generateSchedule($user);
      }

      $progress = $this->generateProgressBar($user->programID);

      return view('flowchart', [
        'user'=>$user,
        'schedule'=> $schedule,
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
        $courses = $this->getCoursesInGroup($programID, $key, false);

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

    public function generateSchedule($user)
    {
      $the_schedule = [];

      $user_schedule=Schedule::where('user_id', $user->id)
      ->whereNotIn('semester', ['complementary_course', 'elective_course'])
      ->where('semester' ,"<>", 'Exemption')
      ->groupBy('semester')
      ->get();

      $sorted=$user_schedule->sortBy('semester');

      foreach($sorted as $semester)
      {
        $new_semester=[];

        $class_array=[];

        $tot_credits=0;

         $classes=DB::table('schedules')
         ->where('user_id', $user->id)
         ->where('semester', $semester->semester)
         ->get(['schedules.id', 'schedules.status','schedules.SUBJECT_CODE', 'schedules.COURSE_NUMBER']);

        foreach($classes as $class)
        {
          $credits = DB::table('programs')->where('SUBJECT_CODE', $class->SUBJECT_CODE)
                     ->where('COURSE_NUMBER', $class->COURSE_NUMBER)
                     ->first(['COURSE_CREDITS'])->COURSE_CREDITS;

          $class_array[] = [$class->id, $class->SUBJECT_CODE, $class->COURSE_NUMBER, $credits, $class->status];
          $tot_credits+=$credits;
        }

        var_dump($semester->semester);
        var_dump($this->get_semester($semester->semester));
        $the_schedule[$this->get_semester($semester->semester)]=[$tot_credits,$class_array, $semester->semester];
      }

      return $the_schedule;
    }
}
