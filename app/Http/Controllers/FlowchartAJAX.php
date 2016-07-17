<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Session;
use App\Schedule;
use App\Error;


class FlowchartAJAX extends Controller
{
  use Traits\NewObjectsTrait;
  use Traits\ProgramTrait;
  use Traits\tools;
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

    return json_encode([$new_semeterCredits, $old_semeterCredits, $errors_to_delete]);
  }

  public function add_course_to_Schedule(Request $request)
  {
    $degree = Session::get('degree');

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
      $new_id = $this->create_schedule($degree, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, $course->SET_TYPE);

    $new_semeterCredits = $this->getSemesterCredits($semester, $degree);
    $progress = $this->generateProgressBar($degree);

    return json_encode([$new_id,$new_semeterCredits, $progress]);
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
      $error_id = $this->create_error($user->id, $target->id, $message, 'vsb_error');
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
