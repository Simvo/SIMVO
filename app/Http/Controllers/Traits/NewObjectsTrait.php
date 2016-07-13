<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use App\Schedule;
use App\Error;

trait NewObjectsTrait
{
  public function create_schedule($user_id, $semester, $SUBJECT_CODE, $COURSE_NUMBER, $SET_TYPE)
  {
    $sched = new Schedule();
    $sched->user_id = $user_id;
    $sched->semester = $semester;
    $sched->SUBJECT_CODE = $SUBJECT_CODE;
    $sched->COURSE_NUMBER = $COURSE_NUMBER;
    $sched->status = $SET_TYPE;
    $sched->save();

    return $sched->id;
  }

  public function create_error($user_id, $sched_id, $message, $type)
  {
    $error = new Error();
    $error->user_id = $user_id;
    $error->schedule_id = $sched_id;
    $error->message = $message;
    $error->type = $type;
    $error->save();

    return $error->id;
  }
}
