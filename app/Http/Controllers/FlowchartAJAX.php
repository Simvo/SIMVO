<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Session;
use App\Schedule;
use App\FlowchartError;
use App\course;
use App\Internship;
use App\Custom;
use App\Minor;
use Debugbar;

class FlowchartAJAX extends Controller
{
  use Traits\NewObjectsTrait;
  use Traits\ProgramTrait;
  use Traits\Tools;
  use Traits\CurlTrait;
  use Traits\ErrorsTrait;
  use Traits\MinorTrait;

  public function move_course(Request $request)
  {

    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $degree = Session::get('degree');

    $semester = $request->semester;
    $full_course_id = $request->id;


    if(substr($request->id,0,1) == "c")
    {
      $courseID = substr($request->id, 4);
      $target = Custom::find($courseID);
    }
    else
    {
      $courseID = $request->id;
      $target = Schedule::find($courseID);
    }

    $old_semester = $target->semester;
    $target->semester=$semester;
    $target->save();

    $old_semeterCredits = $this->getSemesterCredits($old_semester, $degree);
    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);

    $errors_to_delete = $this->manageFlowchartErrors($target);


    return json_encode([$new_semeterCredits, $old_semeterCredits, $errors_to_delete]);
  }

  public function add_course_to_Schedule(Request $request)
  {
    $degree = Session::get('degree');

    $courseType = $request->courseType;
    $courseName = $request->courseName;
    $semester = $request->semester;
    $parts = explode(" ", $courseName);

    $minor = Minor::where('degree_id', $degree->id)->first();
    if($minor)
    {
      $minor_id = $minor->program_id;
    }

    $course = DB::table('programs')->where('PROGRAM_ID',$degree->program_id)
              ->where('SUBJECT_CODE', $parts[0])
              ->where('COURSE_NUMBER', $parts[1])
              ->first(['SUBJECT_CODE', 'COURSE_NUMBER', 'SET_TYPE', 'COURSE_CREDITS', 'SET_TITLE_ENGLISH']);
    // check if course belongs in minor if above is null
    if(is_null($course) && $minor)
    {
      $course = DB::table('programs')->where('PROGRAM_ID', $minor_id)
                ->where('SUBJECT_CODE', $parts[0])
                ->where('COURSE_NUMBER', $parts[1])
                ->first(['SUBJECT_CODE', 'COURSE_NUMBER', 'SET_TYPE', 'COURSE_CREDITS', 'SET_TITLE_ENGLISH']);
    }

    if($course->SET_TITLE_ENGLISH == 'Required Year 0 (Freshman) Courses')
    {
      $new_id = $this->create_schedule($degree, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, 'Required');
    }

    else
    {
      $new_id = $this->create_schedule($degree, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, $courseType);
    }

    $target = Schedule::find($new_id);

    $errors_to_delete = $this->manageFlowchartErrors($target);

    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
    $progress = $this->generateProgressBar($degree);
    if($minor)
      $minor_progress = $this->generateProgressBarMinor($minor);
    else
      $minor_progress = [];

    return json_encode([$new_id,$new_semeterCredits, $progress, $course, $courseType, $errors_to_delete, $minor_progress]);
  }

  public function userCreateCourse(Request $request)
  {
    if(!Auth::Check())
      return;

    $degree = Session::get('degree');
    $courseType = $request->details;

    if($courseType == "Internship")
    {

      $width = $request->width;
      $duration = $request->duration;
      $company = $request->company;
      $position = $request->position;
      $semester = $request->semester;
      $new_id = $this->create_internship($degree, $request->semester, $company, $position, $duration, $width);

      return json_encode([$new_id , 'Internship', $request->company, $request->position, $semester]);

    }
    else if($courseType == "Custom")
    {
      $title = $request->title;
      $description = $request->description;
      $focus = $request->focus;
      $semester = $request->semester;
      $credits = $request->credits;
      $new_id = $this->create_custom_course($degree, $semester, $title, $description, $focus, $credits);

      $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
      $progress = $this->generateProgressBar($degree);

      return json_encode([$new_id, $courseType, $title, $credits, $semester, $new_semeterCredits, $progress, $focus, $description ]);
    }

  }

  public function getElectiveGroups()
  {
    if(!Auth::Check())
      return;

    $degree = Session::get("degree");

    $groups_PDO = DB::table('Programs')
                  ->where('PROGRAM_ID', $degree->program_id)
                  ->groupBy('SET_TITLE_ENGLISH')
                  ->get(['SET_TITLE_ENGLISH', 'SET_BEGIN_TEXT_ENGLISH']);

    $groups = [];

    foreach($groups_PDO as $group)
    {
      if(trim($group->SET_TITLE_ENGLISH) != "")
      {
        $groups[$group->SET_TITLE_ENGLISH] = [];
      }
    }

    return json_encode($groups);
  }

  public function refresh_complementary_courses()
  {

    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $minor_present = false;
    $degree = Session::get('degree');
    $minor = Minor::where("degree_id", $degree->id)->first();


    $groups = $this->getGroupsWithCourses($degree, true);
    $minor_groups = [[], [], []];
    if($minor)
    {
      $minor_groups = $this->getMinorGroupsWithCourses($minor, true);
      $minor_present = true;
    }

    $returnGroups = [];
    $minorGroups = [];

    $returnGroups['Required'] = array_merge($groups[0],  $minor_groups[0]);
    $returnGroups['Complementary'] = array_merge($groups[1],  $minor_groups[1]);
    $returnGroups['Elective'] = array_merge($groups[2],  $minor_groups[2]);
    // Next groups are for minors
    if($minor)
    {
      $minorGroups['Required'] = $minor_groups[0];
      $minorGroups['Complementary'] = $minor_groups[1];
      $minorGroups['Elective'] = $minor_groups[2];
    }

    $groupCredits = $this->getGroupsWithCredits($degree);
    if($minor)
    {
      $minor_credits = $this->getGroupsWithCreditsMinor($minor);
      $groupCredits = array_merge($groupCredits, $minor_credits);
    }


    return json_encode([$returnGroups, $groupCredits, $minorGroups]);
  }

  public function edit_internship(Request $request)
  {
    if(!Auth::Check())
      return;

    $course = DB::table('Internships')->where('id', $request->id);
    $course->update(['company' => $request->companyName, 'position' => $request->positionHeld]);
    return json_encode($course);
  }

  public function edit_custom( Request $request )
  {

    if(!Auth::Check())
      return;

    $degree = Session::get('degree');

    $course = DB::table('customs')->where('id', $request->id);
    $course->update(['title' => $request->title, 'focus' => $request->group, 'credits' => $request->credits, 'description' => $request->description]);

    $semester = $course->first()->semester;
    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
    $progress = $this->generateProgressBar($degree);
    return json_encode([$new_semeterCredits, $progress, $semester]);
  }

  public function add_complementary_course_to_Flowchart(Request $request)
  {
    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $degree = Session::get('degree');

    $courseName = $request->courseName;
    $semester = $request->semester;
    $parts = explode(" ", $courseName);

    $course = DB::table('programs')->where('PROGRAM_ID',$degree->program_id)
              ->where('SUBJECT_CODE', $parts[0])
              ->where('COURSE_NUMBER', $parts[1])
              ->first(['SUBJECT_CODE', 'COURSE_NUMBER', 'SET_TYPE', 'COURSE_CREDITS', 'SET_TITLE_ENGLISH']);

    return json_encode($course);
  }

  public function delete_course_from_schedule(Request $request)
  {
    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $minor = Minor::where('degree_id', $degree->id)->first();
    if($minor)
    {
      $minor_id = $minor->program_id;
    }

    $courseID = $request->id;

    if(substr($request->id,0,1) == "i")
    {
      $courseID = substr($request->id,3);
      $course = Internship::find($courseID);
      $type = 'int';
    }
    else if(substr($request->id,0,1) == "c")
    {
      $courseID = substr($request->id, 4);
      $course = Custom::find($courseID);
      $type = 'cust';
    }
    else
    {
      $courseID = $request->id;
      $course = Schedule::find($courseID);
      $type = '';
    }

    if($type == '')
    {
      $errors_to_delete = $this->empty_errors($course);

      $semester = $course->first()->semester;

      $likeString = $course->SUBJECT_CODE . ' ' . $course->COURSE_NUMBER;
      $likeString = '%'.strtolower($likeString) .'%';

      $course->delete();

      $degree = Session::get('degree');

      // check pre requisites of every course in front that depends on the deleted course

      $prereqs = course::where('prerequisites', 'like', $likeString)->get();

      foreach($prereqs as $prereq)
      {
        if($semester === "Exemption")
        {
          $course = Schedule::where('degree_id', $degree->id)
                          ->where('SUBJECT_CODE', $prereq->SUBJECT_CODE)
                          ->where('COURSE_NUMBER', $prereq->COURSE_NUMBER)
                          ->where('semester', '<>', 'Exemption')
                          ->get();
        }
        else
        {
          $course = Schedule::where('degree_id', $degree->id)
                          ->where('SUBJECT_CODE', $prereq->SUBJECT_CODE)
                          ->where('COURSE_NUMBER', $prereq->COURSE_NUMBER)
                          ->where('semester', '>', $semester)
                          ->get();
        }

        if(count($course) > 0)
        {
          $this->checkPrerequisites($course[0]);
        }
      }
    }
    else{
      $errors_to_delete = [];

      $semester = $course->semester;

      $degree = Session::get('degree');

      $course->delete();
    }



    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
    $progress = $this->generateProgressBar($degree);
    if($minor)
      $minor_progress = $this->generateProgressBarMinor($minor);
    else
      $minor_progress = [];

    return json_encode([$courseID, $new_semeterCredits, $progress, $semester, $errors_to_delete, $type, $minor_progress]);
  }

  public function getSemesterCredits($semester, $degree)
  {

    $courses = Schedule::where('degree_id', $degree->id)
               ->where('semester', $semester)
               ->get(['SUBJECT_CODE', 'COURSE_NUMBER']);

    $sum = 0;
    foreach($courses as $course)
    {
      $courseCredits = DB::table('programs')
                       ->where('SUBJECT_CODE', $course->SUBJECT_CODE)
                       ->where('COURSE_NUMBER', $course->COURSE_NUMBER)
                       ->first(['COURSE_CREDITS'])
                       ->COURSE_CREDITS;
      $sum += $courseCredits;
    }
    $customs = Custom::where('degree_id', $degree->id)
               ->where('semester', $semester)
               ->get(['credits']);
    foreach($customs as $course)
    {
      $sum += $course->credits;
    }

    return $sum;
  }

  public function vsb_checkCourseAvailablity(Request $request)
  {
    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $semester = $request->semester;
    $targetID = $request->scheduleID;

    if(substr($targetID, 0, 4) == "cust")
    {
      $id = substr($targetID, 4, 1);

      $target = Custom::find($id);

      $parts = explode(" ", $target->title);

      if(count($parts) != 2)
        return;

      $subcode = $parts[0];
      $coursenum = $parts[1];
      $available = $this->checkCourseAvailablity($parts[0], $parts[1], $semester);
    }
    else
    {
      $target = Schedule::find($targetID);

      $available = $this->checkCourseAvailablity($target->SUBJECT_CODE, $target->COURSE_NUMBER, $semester);
    }

    $error_id = -1;
    if(count($available))
    {
      $message = $available[0];
      $error_id = $this->create_error($user->id, $target->id, [], $message, 'vsb_error');
    }
    return json_encode([$available, $error_id]);
  }

  public function getMajorStatus_ajax()
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $minor = Minor::where('degree_id', $degree->id)->first(["minor_credits"]);

    $minor_credits = ($minor)? $minor->minor_credits : 0;

    return json_encode([$this->getMajorStatus(), $degree->program_credits, $this->getMinorStatus(), $minor_credits]);
  }

  public function ignore_error(Request $request)
  {
    $error = FlowchartError::find($request->id);
    $error->hidden = 1;
    $error->save();
  }

  public function check_for_ignored_errors(Request $request)
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $errors = [];

    $semesters = Schedule::where('degree_id', $degree->id)
              ->groupBy("semester")
              ->get(['semester', 'id']);

    foreach($semesters as $sem)
    {
      $classes_in_sem = Schedule::where('degree_id', $degree->id)
                        ->where('semester', $sem->semester)
                        ->get(['id']);
      $errors[$sem->semester] = 0;
      foreach($classes_in_sem as $class)
      {
        $errors[$sem->semester]  += FlowchartError::where('schedule_id', $class->id)
                  ->where('hidden', 1)
                  ->count();
      }
    }

    return json_encode($errors);
  }

  public function reveal_errors(Request $request)
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $courses = Schedule::where('degree_id', $degree->id)
              ->where('semester', $request->semester)
              ->get();

    foreach($courses as $course)
    {
      $errors = FlowchartError::where('schedule_id', $course->id)
                              ->where('hidden', 1)
                              ->get();
      foreach($errors as $error)
      {
        $error->hidden = 0;
        $error->save();
      }
    }
  }
  
  
  public function get_courses_in_semester(Request $request)
  {
    $response = [];
    
    $courses = Schedule::where('degree_id', $degree->id)
               ->where('semester', $request->semester)
               ->get(["SUBJECT_CODE", "COURSE_NUMBER"]);

    $customCourses = Custom::where('degree_id', $degree->id)
                            ->where('semester', $request->semester)
                            ->get(['title']);

    foreach($courses as $course)
    {
      $response[] = [$course->SUBJECT_CODE, $course->COURSE_NUMBER];
    }

    foreach($customCourses as $course)
    {
      $parts = explode(" ", $course->title);

      if(count($parts) != 2) continue;

      $response[] = [$parts[0], $parts[1]];
    }

    return json_encode($response);
  }
}
