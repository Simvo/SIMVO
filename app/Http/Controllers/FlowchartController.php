<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Schedule;
use App\UICourse;
use App\Degree;
use App\FlowchartError;
use App\Minor;
use DB;
use Debugbar;
use App\Http\Requests;
use App\Internship;
use App\Custom;

class FlowchartController extends Controller
{
  use Traits\NewObjectsTrait;
  use Traits\StreamTrait;
  use Traits\ProgramTrait;
  use Traits\Tools;
  use Traits\MinorTrait;
    /**
    * Function called upon GET request. Will determine if schedule needs to be generated or simply displayed
    * Consists of generating four main parts.
    * 1) Progress Bar 2)Course Schedule 3) Complementary Courses 4) Elective Courses
    **/
    public function generateFlowChart()
    {
      $user=Auth::User();
      $groupsWithCourses = null;
      $complementaryCourses = null;

      $schedule = [];

      $schedule_check = Schedule::where('user_id', $user->id)
                        ->where('semester', "<>", 'exemption')
                        ->count();

      $userSetupComplete = $this->checkUserSetupStatus($user);

      $degree = null;
      $degrees = $this->getDegrees($user);
      $minors = $this->getMinors();

      $new_user = false;

      if(count($degrees) == 0)
      {
        $faculties = [
          "Faculty of Engineering"
        ];

        $semesters = $this->generateListOfSemesters(10);

        $new_user = true;

        return view('flowchart', [
          'user'=>$user,
          'newUser' => $new_user,
          'minors' => $minors,
          'degreeLoaded' => false,
          'schedule'=> [],
          'progress' => [],
          'progress_minor' => null,
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
        $credits_taken = $this->getMajorStatus();
        $credits_taken_minor = $this->getMinorStatus();
        $minor = Minor::where("degree_id", $degree->id)->first();

        return view('flowchart', [
          'user'=>$user,
          'degree'=>$degree,
          'remainingCredits' => $credits_taken,
          'remainingCreditsMinor' => $credits_taken_minor,
          'newUser' => $new_user,
          'degreeLoaded' => true,
          'schedule'=> $flowchart['Schedule'],
          'minors' => $minors,          
          'minor_object' => $minor,
          'progress' => $flowchart['Progress'],
          'progress_minor' => $flowchart['Progress Minor'],
          'groupsWithCourses' => $flowchart['Groups With Courses'],
          'course_errors' => $flowchart['Errors'],
          'exemptions' => $flowchart['Exemptions'],
          'startingSemester' => $flowchart['Starting Semester'],
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

    // Check and get Minor
    $minor = Minor::where('degree_id', $degree->id)->first();

    $userSetupComplete = $this->checkUserSetupStatus($degree);

    //all courses in the users program.
    $courses = $this->getGroupsWithCourses($degree, true);
    $groupsWithCourses['Required'] = $courses[0];
    $groupsWithCourses['Complementary'] = $courses[1];
    $groupsWithCourses['Elective'] = $courses[2];


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

    if($degree->stream_version != -1 && $schedule_check == 0)
    {
      //initiate stream,
      $this->initiateStreamGenerator($degree);
    }
    else if($schedule_check == 0)
    {
      $schedule[$this->get_semester($degree->enteringSemester)] = [0,[],$startingSemester];
    }
    //if user has not completed initial setup: ie, some courses are in the schedule but some remain in the setup area
    else if($schedule_check > 0)
    {
      $schedule = $this->generateSchedule($degree);
    }

    $progress = $this->generateProgressBar($degree);
    if($minor)
    {
      $progress_minor = $this->generateProgressBarMinor($minor);
    }
    else
    {
      $progress_minor = null;
    }
    $startingSemester = $this->get_semester($startingSemester);
    $errors = $this->getErrors($user);


    return [
      'Schedule'=> $schedule,
      'Progress' => $progress,
      'Progress Minor' => $progress_minor,
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
      'Semester'=>'required',
      'Stream'=>'required',
      'Version'=>'required'
      ]);

    $faculties = $this->getFaculties();

    $program = DB::table('programs')->where('PROGRAM_ID', $request->Major)->first();

    $degree_id = $this->create_degree(
      $user->id,
      $faculties[$request->Faculty],
      $request->Major,
      $program->PROGRAM_MAJOR,
      $program->PROGRAM_TOTAL_CREDITS,
      $request->Version,
      $request->Semester,
      $request->Stream
    );

    $degree = Degree::find($degree_id);

    $schedule_check = Schedule::where('degree_id', $degree->id)
                      ->where('semester', "<>", 'exemption')
                      ->count();

    if($degree->stream_version != -1 && $schedule_check == 0)
    {
      //initiate stream,
      $this->initiateStreamGenerator($degree);
    }

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

  public function generateSchedule($degree)
  {
    $the_schedule = [];
    //Always have their starting semester available -- therefore if they accidentally remove all classes from it and refresh, it will remain.
    $the_schedule[$this->get_semester($degree->enteringSemester)] = [0,[],$degree->enteringSemester];
    $user_schedule=Schedule::where('degree_id', $degree->id)
    ->where('semester' ,"<>", 'Exemption')
    ->groupBy('semester')
    ->get();
    $user_schedule2 = Internship::where('degree_id', $degree->id)
    ->groupBy('semester')
    ->get();
    $user_schedule3 = Custom::where('degree_id', $degree->id)
    ->where('semester' ,"<>", 'Exemption')
    ->groupBy('semester')
    ->get();

    $user_schedule_final = [];
    foreach($user_schedule as $semester)
    {
      $user_schedule_final[] = $semester->semester;
    }
    foreach($user_schedule2 as $semester)
    {
      $sem = $semester->semester;
      for($i = 0; $i < $semester->duration; $i++){
        $user_schedule_final[] = $sem;
        $sem = $this->get_next_semester($sem);
      }

    }
    foreach($user_schedule3 as $semester)
    {
      $user_schedule_final[] = $semester->semester;
    }

    $user_schedule_final = array_unique($user_schedule_final);
    sort($user_schedule_final);



    foreach($user_schedule_final as $semester)
    {
      $new_semester=[];
      $class_array=[];
      $tot_credits=0;
      $classes=DB::table('schedules')
      ->where('degree_id', $degree->id)
      ->where('semester', $semester)
      ->get(['schedules.id', 'schedules.status','schedules.SUBJECT_CODE', 'schedules.COURSE_NUMBER']);
      $customs=DB::table('customs')
      ->where('degree_id', $degree->id)
      ->where('semester', $semester)
      ->get(['customs.id', 'customs.title', 'customs.description', 'customs.focus', 'customs.credits']);
      $internships=DB::table('internships')
      ->where('degree_id', $degree->id)
      ->where('semester', $semester)
      ->get(['internships.id', 'internships.company', 'internships.position', 'internships.duration', 'internships.width']);

      foreach($classes as $class)
      {
          $credits = DB::table('programs')->where('SUBJECT_CODE', $class->SUBJECT_CODE)
                     ->where('COURSE_NUMBER', $class->COURSE_NUMBER)
                     ->first(['COURSE_CREDITS'])->COURSE_CREDITS;
          $class_array[] = [$class->id, $class->SUBJECT_CODE, $class->COURSE_NUMBER, $credits, $class->status];
          $tot_credits+=$credits;

      }
      foreach($customs as $custom)
      {
        $credits = (int) $custom->credits;
        $class_array[] = [$custom->id, $custom->title, $custom->description, $credits, 'Custom', $custom->focus];
        $tot_credits+=$credits;
      }
      foreach($internships as $internship)
      {
        $class_array[] = [$internship->id, $internship->company, $internship->position, $internship->duration,'Internship', $internship->width];
      }

      $the_schedule[$this->get_semester($semester)]=[$tot_credits,$class_array, $semester];
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
    $all_errors = FlowchartError::where('flowchart_errors.user_id', $user->id)
              ->join('schedules', 'schedules.id', '=', 'flowchart_errors.schedule_id')
              ->groupBy('schedules.semester')
              ->get(['schedules.semester']);

    $errors = [];
    foreach($all_errors as $e)
    {
      $errors_in_semester = FlowchartError::where('flowchart_errors.user_id', $user->id)
               ->join('schedules', 'schedules.id', '=', 'flowchart_errors.schedule_id')
               ->where('schedules.semester', $e->semester)
               ->get(['flowchart_errors.id', 'flowchart_errors.type', 'flowchart_errors.message']);

      $errors[$this->get_semester($e->semester)] = [];

      foreach($errors_in_semester as $error)
      {
        $errors[$this->get_semester($e->semester)][] = [$error->id, $error->type, $error->message];
      }
    }
    return $errors;
  }
}
