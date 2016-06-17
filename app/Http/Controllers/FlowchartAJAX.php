<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class FlowchartAJAX extends Controller
{
  public function move_course(Request $request)
  {
    $semester=$request->semester;
    $sched_id=$request->id;

    $target=Schedule::find($sched_id);

    $old_semester=$target->semester;


    $target->semester=$semester;
    $target->save();


    $old_semester_credits=$this->get_num_credits($old_semester);

    $new_semester_credits=$this->get_num_credits($semester);

    $check_pre_req=$this->check_Pre_req($sched_id);
    $forward=$this->look_forward($sched_id);
    $backwards=$this->look_backwards($sched_id);

    if(is_array($check_pre_req))
    {
      $check = Error::where('user_ID', Auth::User()->id)
      ->where('course', $target->course)
      ->delete();

      foreach($check_pre_req as $missing_prereq)
      {
        $error = new Error;
        $error->user_ID = Auth::User()->id;
        $error->course = $target->course;
        $error->semester = $target->semester;
        $error->schedule_id = $sched_id;
        $error->missing_courses = $target->course . " is missing Pre-Req: " . $missing_prereq;
        $error->class = "missing_prereq_" . $missing_prereq;
        $error->save();
      }
    }

    else
    {
      $check = Error::where('course', $target->course)->delete();
    }


    $update_progress=$this->get_progress();

    return json_encode([$target->course, $check_pre_req, $forward, $old_semester_credits, $new_semester_credits, $update_progress, $backwards]);
  }
}
