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

  use Traits\NewObjectsTrait;
  use Traits\ProgramTrait;
  use Traits\Tools;
    /**
    * Function called upon GET request. Will determine if schedule needs to be generated or simply displayed
    * Consists of generating four main parts.
    * 1) Progress Bar 2)Course Schedule 3) Complementary Courses 4) Elecitive Courses
    **/
    public function generateFlowChart()
    {
      $user=Auth::User();
      $groupsWithCourses = null;
      $complementaryCourses = null;
      $schedule = [];
      //Get User's entering semester
      $startingSemester = $user->enteringSemester;

      //load excpemtions
      $exemptions = $this->getExemptions($user);

      $schedule_check = Schedule::where('user_id', $user->id)
                        ->where('semester', "<>", 'exemption')
                        ->count();
      $userSetupComplete = $this->checkUserSetupStatus($user);

      //If (user has not yet setup courses or recommended Stream is not provided)
      if(!$userSetupComplete)
      {
        $groupsWithCourses = $this->getGroupsWithCourses($user->programID, true)[0];
      }
      else{
        $complementaryCourses = $this->getGroupsWithCourses($user->programID, true)[1];
      }

      if($schedule_check == 0)
      {
        $schedule[$this->get_semester($user->enteringSemester)] = [0,[],$startingSemester];
      }

      //if user has not completed initial setup: ie, some courses are in the schedule but some remain in the setup area
      else if($schedule_check > 0)
      {
        $schedule = $this->generateSchedule($user);
      }

      $progress = $this->generateProgressBar($user);


      $startingSemester = $this->get_semester($startingSemester);

      return view('flowchart', [
        'user'=>$user,
        'schedule'=> $schedule,
        'progress' => $progress,
        'groupsWithCourses' => $groupsWithCourses,
        'complementaryCourses' => $complementaryCourses,
        'exemptions' => $exemptions,
        'startingSemester' => $startingSemester
      ]);
    }

    public function getExemptions($user)
    {
      $exemptions_PDO = Schedule::where('user_id',$user->id)
                        ->where('semester', 'exemption')
                        ->get();
      $exemptions = [];

      $sum = 0;

      foreach ($exemptions_PDO as $exemption)
      {
        $status = DB::table('programs')->where('PROGRAM_ID', $user->programID)
                  ->where('SUBJECT_CODE', $exemption->SUBJECT_CODE)
                  ->where('COURSE_NUMBER', $exemption->COURSE_NUMBER)
                  ->first(['COURSE_CREDITS']);
        $sum += $status->COURSE_CREDITS;

        $exemptions[] = [$exemption->id, $exemption->SUBJECT_CODE, $exemption->COURSE_NUMBER, $status->COURSE_CREDITS, $exemption->status];
      }

      return [$exemptions,$sum];
    }

    public function generateSchedule($user)
    {
      $the_schedule = [];

      //Always have their starting semester available -- therefore if they accidentally remove all classes from it and refresh, it will remain.
      $the_schedule[$this->get_semester($user->enteringSemester)] = [0,[],$user->enteringSemester];

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

        $the_schedule[$this->get_semester($semester->semester)]=[$tot_credits,$class_array, $semester->semester];
      }

      return $the_schedule;
    }

    public function checkUserSetupStatus($user)
    {
      $requiredGroups = $this->getRequiredGroups($user->programID);

      foreach($requiredGroups as $key=>$group)
      {
        $coursesInGroup = $this->getCoursesInGroup($user->programID, $key, true);
        if(count($coursesInGroup) > 0) return false;
      }

      return true;
    }

}
