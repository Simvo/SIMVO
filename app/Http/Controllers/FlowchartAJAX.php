<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Schedule;

class FlowchartAJAX extends Controller
{
  public function move_course(Request $request)
  {
    if(!Auth::Check())
      return;

    $semester=$request->semester;
    $sched_id=$request->id;
    var_dump($semester);
    var_dump($sched_id);
    $target=Schedule::find($sched_id);
    $target->semester=$semester;
    $target->save();

    var_dump($semester);
  }
}
