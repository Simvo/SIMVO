<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use App\Schedule;
use App\FlowchartError;
use App\Degree;

trait NewObjectsTrait
{
  public function create_schedule($degree, $semester, $SUBJECT_CODE, $COURSE_NUMBER, $SET_TYPE)
  {
    $sched = new Schedule();
    $sched->user_id = $degree->user_id;
    $sched->degree_id = $degree->id;
    $sched->semester = $semester;
    $sched->SUBJECT_CODE = $SUBJECT_CODE;
    $sched->COURSE_NUMBER = $COURSE_NUMBER;
    $sched->status = $SET_TYPE;
    $sched->save();

    return $sched->id;
  }

  public function create_degree($user_id, $faculty, $program_id, $program_name, $program_credits, $version_id, $enteringSemester, $stream_version)
  {
    $degree = new Degree();
    $degree->user_id = $user_id;
    $degree->faculty = $faculty;
    $degree->program_id = $program_id;
    $degree->program_name = $program_name;
    $degree->program_credits = $program_credits;
    $degree->version_id = $version_id;
    $degree->enteringSemester = $enteringSemester;
    $degree->stream_version = $stream_version;
    $degree->save();

    return $degree->id;
  }

  public function create_error($user_id, $sched_id, $dependencies, $message, $type)
  {
    $error = new FlowchartError();
    $error->user_id = $user_id;
    $error->schedule_id = $sched_id;
    $error->dependencies = json_encode($dependencies);
    $error->message = $message;
    $error->type = $type;
    $error->save();

    $sql = "INSERT into `flowchart_errors` (`user_id`, `schedule_id`, `message`, `dependencies`, `type`)
     VALUES (". $user_id .", ". $sched_id . ", '". $message ."', '". json_encode($dependencies) ."', '". $type ."')";

     DB::raw($sql);
    //
    //  var_dump(Error::where('message', $message)->get());

    return $error->id;
  }
}
