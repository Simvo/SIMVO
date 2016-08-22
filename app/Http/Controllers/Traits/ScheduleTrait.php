<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Stream;
use App\StreamStructure;
use App\Schedule;
use DB;
use Auth;

trait ScheduleTrait
{

  /**
  * Deletes every schedule instance in a degree
  * @param degree containing user and stream information
  * @return void
  **/
  public function emptySchedule($degree)
  {
    $removeSchedule = Schedule::where('degree_id', $degree->id)
                      ->delete();
  }
}
