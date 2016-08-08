<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Schedule;
use App\UICourse;
use App\Degree;
use App\Error;
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
    $degrees = $this->getDegrees($user);

    $new_user = false;

    if(count($degrees) == 0)
    {
      $faculties = $this->getFaculties();
      array_unshift($faculties, "Select");

      $semesters = $this->generateListOfSemesters(10);

      $new_user = true;

      return view('flowchart', [
        'user'=>$user,
        'newUser' => $new_user,
        'degreeLoaded' => false,
        'schedule'=> [],
        'progress' => [],
        'groupsWithCourses' => [],
        'exemptions' => [],
        'startingSemester' => "",
        'faculties'=> $faculties,
        'semesters' => $semesters,
      ]);
    }
    else
    {
      $degree = $degrees[0];
      Session::put('degree', $degree);

      $flowchart = $this->generateDegree($degree);

      return view('flowchart', [
        'user'=>$user,
        'degree'=>$degree,
        'newUser' => $new_user,
        'degreeLoaded' => true,
        'schedule'=> $flowchart['Schedule'],
        'progress' => $flowchart['Progress'],
        'groupsWithCourses' => $flowchart['Groups With Courses'],
        'course_errors' => $flowchart['Errors'],
        'exemptions' => $flowchart['Exemptions'],
        'startingSemester' => $flowchart['Starting Semester']
      ]);
    }
  }

  public function generateDegree($degree)
  {
    $user=Auth::User();

    $groupsWithCourses = [];
    $complementaryCourses = null;

    $schedule = [];

    //Get User's entering semester
    $startingSemester = $degree->enteringSemester;

    //load excpemtions
    $exemptions = $this->getExemptions($degree);

    $schedule_check = Schedule::where('degree_id', $degree->id)
                      ->where('semester', "<>", 'exemption')
                      ->count();

    $userSetupComplete = $this->checkUserSetupStatus($degree);

    //all courses in the users program.
    $courses = $this->getGroupsWithCourses($degree, true);
    $groupsWithCourses['Required'] = $courses[0];
    $groupsWithCourses['Complementary'] = $courses[1];
    $groupsWithCourses['Elective'] = $courses[2];


    if($schedule_check == 0)
    {
      $schedule[$this->get_semester($degree->enteringSemester)] = [0,[],$startingSemester];
    }
    //if user has not completed initial setup: ie, some courses are in the schedule but some remain in the setup area
    else if($schedule_check > 0)
    {
      $schedule = $this->generateSchedule($degree);
    }

    $progress = $this->generateProgressBar($degree);
    $startingSemester = $this->get_semester($startingSemester);
    $errors = $this->getErrors($user);


    return [
      'Schedule'=> $schedule,
      'Progress' => $progress,
      'Exemptions' => $exemptions,
      'Groups With Courses' => $groupsWithCourses,
      'Starting Semester' => $startingSemester,
      'Errors' => $errors
    ];
  }

  public function getDegrees($user)
  {
    $degrees = Degree::where('user_id', $user->id)->get();

    return $degrees;
  }

  public function newUserCreateDegree(Request $request)
  {
    $user=Auth::User();

    $this->validate($request, [
      'Faculty'=>'required|digits:1,2',
      'Major'=>'required',
      'Semester'=>'required|digits:1,2',
      'Stream'=>'required',
      'Version'=>'required'
      ]);

    $semesters = $this->generateListOfSemesters(10);
    $faculties = $this->getFaculties();

    $program = DB::table('programs')->where('PROGRAM_ID', $request->Major)->first();

    $degree_id = $this->create_degree(
      $user->id,
      $faculties[$request->Faculty - 1],
      $request->Major,
      $program->PROGRAM_MAJOR,
      $program->PROGRAM_TOTAL_CREDITS,
      1,
      $this->encode_semester($semesters[$request->Semester]),
      -1
    );

    return redirect('flowchart');
  }



  public function getExemptions($degree)
  {
    $exemptions_PDO = Schedule::where('degree_id',$degree->id)
                      ->where('semester', 'exemption')
                      ->get();
    $exemptions = [];
    $sum = 0;

    foreach ($exemptions_PDO as $exemption)
    {
      $status = DB::table('programs')->where('PROGRAM_ID', $degree->program_id)
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
        if(explode(" " , $class->status)[0] != "Internship" && explode(" " , $class->status)[0] != "Internship_holder"  )
        {
          $credits = DB::table('programs')->where('SUBJECT_CODE', $class->SUBJECT_CODE)
                     ->where('COURSE_NUMBER', $class->COURSE_NUMBER)
                     ->first(['COURSE_CREDITS'])->COURSE_CREDITS;
        }
        else
        {
          $credits = 0;
        }
          $class_array[] = [$class->id, $class->SUBJECT_CODE, $class->COURSE_NUMBER, $credits, $class->status];
          $tot_credits+=$credits;

      }
      $the_schedule[$this->get_semester($semester->semester)]=[$tot_credits,$class_array, $semester->semester];
    }
    return $the_schedule;
  }
  public function checkUserSetupStatus($degree)
  {
    $requiredGroups = $this->getRequiredGroups($degree);
    foreach($requiredGroups as $key=>$group)
    {
      $coursesInGroup = $this->getCoursesInGroup($degree, $key, true);
      if(count($coursesInGroup) > 0) return false;
    }
    return true;
  }

  public function getErrors($user)
  {
    $all_errors = Error::where('errors.user_id', $user->id)
              ->join('schedules', 'schedules.id', '=', 'errors.schedule_id')
              ->groupBy('schedules.semester')
              ->get(['schedules.semester']);
    $errors = [];
    foreach($all_errors as $e)
    {
      $errors_in_semester = Error::where('errors.user_id', $user->id)
               ->join('schedules', 'schedules.id', '=', 'errors.schedule_id')
               ->where('schedules.semester', $e->semester)
               ->get(['errors.id', 'errors.type', 'errors.message']);
      $errors[$this->get_semester($e->semester)] = [];
      foreach($errors_in_semester as $error)
      {
        $errors[$this->get_semester($e->semester)][] = [$error->id, $error->type, $error->message];
      }
    }
    return $errors;
  }
}
