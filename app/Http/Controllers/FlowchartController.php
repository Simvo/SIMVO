<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Schedule;
use App\UICourse;
use App\Degree;
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
      $degree = null;
      $new_user = false;
      $degrees = $this->getDegrees($user);
      if($degrees == -1)
      {
        $new_user = true;
        return view('flowchart', [
          'user'=>$user,
          'newUser' => $new_user,
          'schedule'=> [],
          'progress' => [],
          'groupsWithCourses' => [],
          'complementaryCourses' => [],
          'exemptions' => [],
          'startingSemester' => ""
        ]);
      }
      else
      {
        $degree = $this->getDefaultDegree();
        $flowchart = $this->createDegree($degree);
        return view('flowchart', [
          'user'=>$user,
          'newUser' => $new_user,
          'schedule'=> $flowchart['Schedule'],
          'progress' => $flowchart['Progress'],
          'groupsWithCourses' => $flowchart['Groups With Courses'],
          'complementaryCourses' => $flowchart['Complementary Courses'],
          'exemptions' => $flowchart['Exemptions'],
          'startingSemester' => $flowchart['Starting Semester']
        ]);
      }
    }
    public function createDegree($degree)
    {
      $schedule = [];
      //Get User's entering semester
      $startingSemester = $degree->enteringSemester;
      //load excpemtions
      $exemptions = $this->getExemptions($degree);
      $schedule_check = Schedule::where('user_id', $user->id)
                        ->where('semester', "<>", 'exemption')
                        ->count();
      $userSetupComplete = $this->checkUserSetupStatus($user);
      //all courses in the users program. Index 0 is required, 1 is complementaries, 2 is electives.
      $courses = $this->getGroupsWithCourses($user->programID, true);
      //If (user has not yet setup courses or recommended Stream is not provided)
      if(!$userSetupComplete)
      {
        $groupsWithCourses = $courses[0];
      }
      else
      {
        $complementaryCourses[0] = $courses[1];
        $complementaryCourses[1] = $courses[2];
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
      return [
        'Schedule'=> $schedule,
        'Progress' => $progress,
        'Exemptions' => $exemptions,
        'Groups With Courses' => $groupsWithCourses,
        'Complementary Courses'=> $complementaryCourses,
        'Starting Semester' => $startingSemester,
      ];
    }
    public function getDegrees($user)
    {
      $degrees = Degree::where('user_id', $user->id)->get();
      if(count($degrees) == 0)
      {
        return -1;
      }
      else
      {
        return $degrees;
      }
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
