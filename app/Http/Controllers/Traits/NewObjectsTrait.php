<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use App\Schedule;

trait NewObjectsTrait
{
  public function create_schedule($id, $semester, $SUBJECT_CODE, $COURSE_NUMBER, $SET_TYPE)
  {
    $sched = new Schedule();
    $sched->user_id = $id;
    $sched->semester = $semester;
    $sched->SUBJECT_CODE = $SUBJECT_CODE;
    $sched->COURSE_NUMBER = $COURSE_NUMBER;
    $sched->status = $SET_TYPE;
    $sched->save();

    return $sched->id;
  }
}
