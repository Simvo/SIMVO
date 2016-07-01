<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use App\Schedule;

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

    $semester=$request->semester;
    $sched_id=$request->id;
    $target=Schedule::find($sched_id);
    $old_semester = $target->semester;
    $target->semester=$semester;
    $target->save();

    $old_semeterCredits = $this->getSemeterCredits($old_semester, $user);
    $new_semeterCredits = $this->getSemeterCredits($semester, $user);

    return json_encode([$new_semeterCredits, $old_semeterCredits]);
  }

  public function add_course_to_Schedule(Request $request)
  {
    if(!Auth::Check())
      return;
    else
      $user = Auth::User();

    $courseName = $request->courseName;
    $semester = $request->semester;
    $parts = explode(" ", $courseName);

    $course = DB::table('programs')->where('PROGRAM_ID',$user->programID)
              ->where('SUBJECT_CODE', $parts[0])
              ->where('COURSE_NUMBER', $parts[1])
              ->first(['SUBJECT_CODE', 'COURSE_NUMBER', 'SET_TYPE', 'COURSE_CREDITS', 'SET_TITLE_ENGLISH']);

    if($course->SET_TITLE_ENGLISH == 'Required Year 0 (Freshman) Courses')
    {
      $new_id = $this->create_schedule($user->id, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, 'Required');
    }
    else
      $new_id = $this->create_schedule($user->id, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, $course->SET_TYPE);

    $new_semeterCredits = $this->getSemeterCredits($semester, $user);
    $progress = $this->generateProgressBar($user);

    return json_encode([$new_id,$new_semeterCredits, $progress]);
  }

  public function getSemeterCredits($semester, $user)
  {
    $courses = Schedule::where('user_id', $user->id)
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
    $semester = $request->semester;
    $targetID = $request->scheduleID;
    $target=Schedule::find($targetID);

    return $this->checkCourseAvailablity($target->SUBJECT_CODE, $target->COURSE_NUMBER, $semester);
  }
}
