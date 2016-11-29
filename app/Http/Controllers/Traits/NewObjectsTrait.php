<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;
use App\Schedule;
use App\FlowchartError;
use App\Degree;
use App\Internship;
use App\Custom;
use App\Minor;

trait NewObjectsTrait
{

  public function create_internship($degree, $semester, $company, $position, $duration, $width)
  {
    $internship = new Internship();
    $internship->user_id = $degree->user_id;
    $internship->degree_id = $degree->id;
    $internship->semester = $semester;
    $internship->company = $company;
    $internship->position = $position;
    $internship->duration = $duration;
    $internship->width = $width;
    $internship->save();

    return $internship->id;
  }

  public function create_custom_course($degree, $semester, $title, $description, $focus, $credits)
  {
    $custom = new Custom();

    $custom->user_id = $degree->user_id;
    $custom->degree_id = $degree->id;
    $custom->semester = $semester;
    $custom->title = $title;
    $custom->description = $description;
    $custom->focus = $focus;
    $custom->credits = $credits;
    $custom->save();

    return $custom->id;
  }

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
     return $error->id;
  }

  public function create_minor($degree_id, $program_id, $minor_name, $minor_credits, $version_id)
  {
    $minor = new Minor();
    $minor->degree_id = $degree_id;
    $minor->program_id = $program_id;
    $minor->minor_name = $minor_name;
    $minor->minor_credits = $minor_credits;
    $minor->version_id = $version_id;
    $minor->save();

    return $minor;
  }
}
