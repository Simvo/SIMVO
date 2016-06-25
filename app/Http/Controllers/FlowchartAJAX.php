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

  public function move_course(Request $request)
  {
    if(!Auth::Check())
      return;

    $semester=$request->semester;
    $sched_id=$request->id;
    $target=Schedule::find($sched_id);
    $target->semester=$semester;
    $target->save();
  }

  public function add_course_to_Schedule(Request $request)
  {
    if(!Auth::Check())
      return;

    $user = Auth::User();
    $courseName = $request->courseName;
    $semester = $request->semester;
    $parts = explode(" ", $courseName);

    $course = DB::table('programs')->where('PROGRAM_ID',$user->programID)
              ->where('SUBJECT_CODE', $parts[0])
              ->where('COURSE_NUMBER', $parts[1])
              ->first(['SUBJECT_CODE', 'COURSE_NUMBER', 'SET_TYPE', 'COURSE_CREDITS','SET_TITLE_ENGLISH']);
    if($course->SET_TITLE_ENGLISH == 'Required Year 0 (Freshman) Courses')
    {
      $new_id = $this->create_schedule($user->id, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, 'Required');
    }
    else
    {
      $new_id = $this->create_schedule($user->id, $semester, $course->SUBJECT_CODE, $course->COURSE_NUMBER, $course->SET_TYPE);
    }

    return json_encode($new_id);
  }
}
