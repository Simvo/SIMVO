<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Session;
use App\Schedule;
use App\Error;
use App\course;


class FlowchartAJAX extends Controller
{
  use Traits\NewObjectsTrait;
  use Traits\ProgramTrait;
  use Traits\Tools;
  use Traits\CurlTrait;

  public function move_course(Request $request)
  {
    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $degree = Session::get('degree');

    $semester=$request->semester;
    $sched_id=$request->id;
    $target=Schedule::find($sched_id);
    $old_semester = $target->semester;
    $target->semester=$semester;
    $target->save();

    $errors_to_delete = $this->empty_errors($target);

    $old_semeterCredits = $this->getSemesterCredits($old_semester, $degree);
    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);

    $errors = $this->manageFlowchartErrors($target);

    return json_encode([$new_semeterCredits, $old_semeterCredits, $errors_to_delete]);
  }

  public function manageFlowchartErrors($target)
  {
    $error_messages = [];

    $prerequisiteErrors = $this->checkPrerequisites($target);

    $solvedErrorsForward = $this->lookForwards($target);

    $solvedErrorsBackwards = $this->lookBackwards($target);

    $solvedErrors = array_merge($solvedErrorsForward, $solvedErrorsBackwards);

    if(is_array($prerequisiteErrors))
    {

      foreach($prerequisiteErrors as $error)
      {
        $message = "";
        $dependencies = [];

        $i = 0;
        foreach($error as $course)
        {
          if($i == count($error) - 1)
          {
            $message .= $course[0] . " " . $course[1];
          }
          else
          {
            $message .= $course[0] . " " . $course[1] . " or ";
          }

          $dependencies[] = $course[0] . " " . $course[1];
          $i++;
        }

        $error_messages[] = $message;
        $this->create_error($target->user_id, $target->id, $dependencies, $message, "Prerequisite");
      }
    }

    return [$error_messages, $solvedErrors];
  }

  // Find if target course prerequisites are violated when course is moved
  public function checkPrerequisites($target)
  {

    $prereqs = course::where('SUBJECT_CODE', $target->SUBJECT_CODE)
                     ->where('COURSE_NUMBER', $target->COURSE_NUMBER)
                     ->first();
    if($prereqs == null)
    {
      return true;
    }

    $prereqs = $prereqs->prerequisites;

    if($prereqs == "")
    {
      return true;
    }

    $parts = explode("&&", $prereqs);

    $errors = [];
    $missingCourses = [];

    foreach($parts as $part)
    {
      $courses_in_part = explode("||", $part);
      $type = "OR";
      $prereq_satisfied = 0;

      foreach($courses_in_part as $prereq)
      {
        $course = explode(" ", $prereq);
        $sub_code = str_replace("(", "", $course[0]);
        $course_num =str_replace(")", "", $course[1]);

        $check_exemption = Schedule::where('degree_id', $target->degree_id)
                 ->where('SUBJECT_CODE', $sub_code)
                 ->where('Course_Number', $course_num)
                 ->where('semester', 'Exemption');

        $check = Schedule::where('degree_id', $target->degree_id)
                 ->where('SUBJECT_CODE', $sub_code)
                 ->where('Course_Number', $course_num)
                 ->where('semester', '<', $target->semester)
                 ->union($check_exemption)
                 ->first();

        if(count($check))
        {
          $prereq_satisfied++;
          break;
        }

        $missing_courses[] = [$sub_code, $course_num];
      }

      if($prereq_satisfied == 0 )
      {
        $errors[] = $missing_courses;
      }
    }

    if(count($errors) == 0)
    {
      return true;
    }

    else
    {
      return $errors;
    }
  }

  // Search for errors solved by a move or add of a course in front of the semester
  public function lookForwards($sched_id)
  {
    return [];
  }

  // Search for errors solved by a move or an add of course behind the semester
  public function lookBackwards($sched_id)
  {
    return [];
  }

  public function get_errors(Request $request)
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $errorsJSON = [];

    $allScheduleID = $this->getAllSchedId($degree);

    $errors = Error::whereIn('schedule_id' , $allScheduleID)->get();

    foreach($errors as $error)
    {
      $errorsJSON[] = [$error->id, $error->schedule_id, $error->message, $error->type];
    }

    return json_encode($errorsJSON);
  }

  public function add_course_to_Schedule(Request $request)
  {
    $degree = Session::get('degree');

    $courseType = $request->courseType;
    $courseName = $request->courseName;
    $semester = $request->semester;
    $parts = explode(" ", $courseName);

    $course = DB::table('programs')->where('PROGRAM_ID',$degree->program_id)
              ->where('SUBJECT_CODE', $parts[0])
              ->where('COURSE_NUMBER', $parts[1])
              ->first(['SUBJECT_CODE', 'COURSE_NUMBER', 'SET_TYPE', 'COURSE_CREDITS', 'SET_TITLE_ENGLISH']);

    if($course->SET_TITLE_ENGLISH == 'Required Year 0 (Freshman) Courses')
    {
      $new_id = $this->create_schedule($degree, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, 'Required');
    }

    else
    {
      $new_id = $this->create_schedule($degree, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, $courseType);
    }

    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
    $progress = $this->generateProgressBar($degree);

    return json_encode([$new_id,$new_semeterCredits, $progress, $course, $courseType]);
  }

  public function userCreateInternship(Request $request)
  {
    if(!Auth::Check())
      return;

    $courseTypeWidthAndLength = $request->courseTypeWidthAndLength;
    $company = $request->company;
    $position = $request->position;
    $semester = array($request->semester);
    $degree = Session::get('degree');
    $internshipData = explode(" ", $courseTypeWidthAndLength);
    $courseType = $internshipData[0];
    $length = $internshipData[2];
    $new_id = array($this->create_schedule($degree, $request->semester, $company, $position, $courseTypeWidthAndLength));
    $semesterShift = $request->semester;

    for($i = 1; $i < $length; $i++)
    {
        $semesterShift = $this->get_next_semester($semesterShift);
        array_push($semester, $semesterShift);
        array_push($new_id, $this->create_schedule($degree, $semesterShift, $company, $position, 'Internship_holder '.$new_id[0]));
    }

    return json_encode([ $new_id , $courseType, $company, $position, $semester]);
  }

  public function refresh_complementary_courses()
  {

    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $degree = Session::get('degree');


    $groups = $this->getGroupsWithCourses($degree, true);

    $returnGroups['Required'] = $groups[0];
    $returnGroups['Complementary'] = $groups[1];
    $returnGroups['Elective'] = $groups[2];
    $groupCredits = $this->getGroupsWithCredits($degree);

    return json_encode([$returnGroups, $groupCredits]);
  }

  public function edit_internship(Request $request)
  {
    if(!Auth::Check())
      return;

    $course = DB::table('schedules')->where('id', $request->id);
    $course->update(['SUBJECT_CODE' => $request->companyName, 'COURSE_NUMBER' => $request->positionHeld]);
    return json_encode($course);
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

  $courseID = $request->id;

  $course = Schedule::find($courseID);

  $semester = $course->first()->semester;

  $degree = Session::get('degree');

  $course->delete();

  $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
  $progress = $this->generateProgressBar($degree);

  return json_encode([$courseID, $new_semeterCredits, $progress, $semester]);
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
    $target = Schedule::find($targetID);

    $available = $this->checkCourseAvailablity($target->SUBJECT_CODE, $target->COURSE_NUMBER, $semester);

    $error_id = -1;
    if(count($available))
    {
      $message = $available[0];
      $error_id = $this->create_error($user->id, $target->id, [], $message, 'vsb_error');
    }
    return json_encode([$available, $error_id]);
  }

  public function empty_errors($target)
  {
    $errors = Error::where('schedule_id', $target->id)->get();

    $id_array = [];

    foreach($errors as $error)
    {
      $id_array[] = $error->id;
      $error->delete();
    }

    return $id_array;
  }
}
