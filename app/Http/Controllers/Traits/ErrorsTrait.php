<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Schedule;
use App\course;
use App\FlowchartError;
use DB;
use Auth;
use Session;

trait ErrorsTrait
{

  // Will handle all 3 cases for prerequisite checking and return list of errors to delete
  public function manageFlowchartErrors($target)
  {
    $errors_to_delete = $this->empty_errors($target);

    $prerequisiteErrors = $this->checkPrerequisites($target);

    $errorsBackwards = $this->lookBackwards($target);

    $solvedErrorsForward = $this->lookForwards($target);

    $solvedErrors = array_merge($solvedErrorsForward, $errors_to_delete);

    return $solvedErrors;
  }

  // Find if target course prerequisites are violated when course is moved
  public function checkPrerequisites($target)
  {
    if($target == null)
    {
      return;
    }

    $prereqs = course::where('SUBJECT_CODE', $target->SUBJECT_CODE)
                     ->where('COURSE_NUMBER', $target->COURSE_NUMBER)
                     ->first();
    if($prereqs == null)
    {
      return [];
    }

    $prereqs = $prereqs->prerequisites;

    if($prereqs == "")
    {
      return [];
    }

    $parts = explode("&&", $prereqs);

    $errors = [];

    foreach($parts as $part)
    {
      $courses_in_part = explode("||", $part);
      $type = "OR";
      $prereq_satisfied = 0;
      $missing_courses = [];

      foreach($courses_in_part as $prereq)
      {
        $course = explode(" ", $prereq);

        if(count($course) != 2)
        {
          continue;
        }

        $sub_code = str_replace("(", "", $course[0]);
        $course_num =str_replace(")", "", $course[1]);

        $check_exemption = Schedule::where('degree_id', $target->degree_id)
                 ->where('SUBJECT_CODE', $sub_code)
                 ->where('Course_Number', $course_num)
                 ->where('semester', 'Exemption');

        $check = Schedule::where('degree_id', $target->degree_id)
                 ->where('SUBJECT_CODE', $sub_code)
                 ->where('Course_Number', $course_num)
                 ->where('semester', '<', $target->semester)
                 ->union($check_exemption)
                 ->first();

        if(count($check))
        {
          $prereq_satisfied++;
          break;
        }

        $missing_courses[] = [$sub_code, $course_num];
      }

      if($prereq_satisfied == 0 )
      {
        $this->createErrorWithMessage($missing_courses, $target);
      }
    }

    return $errors;
  }

  /**
  * Function that checks which Errors have been solved by the new schedule
  * @param Instance of Schedule: id
  * @return Courses present in future semesters that have the course as a pre requisite
  **/
  public function lookForwards($target)
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $errors_deleted = [];

    $allScheduleID = $this->getAllSchedId($degree);

    if($target->semester === "Exemption")
    {
      $errors = FlowchartError::whereIn('schedule_id' , $allScheduleID)
                ->join('schedules', 'schedules.id', '=', 'flowchart_errors.schedule_id')
                ->where('schedules.semester', '<>', "Exemption")
                ->get(['flowchart_errors.id', 'flowchart_errors.dependencies', 'schedules.SUBJECT_CODE', 'schedules.COURSE_NUMBER']);
    }

    else
    {
      $errors = FlowchartError::whereIn('schedule_id' , $allScheduleID)
                ->join('schedules', 'schedules.id', '=', 'flowchart_errors.schedule_id')
                ->where('schedules.semester', '>', $target->semester)
                ->get(['flowchart_errors.id', 'flowchart_errors.dependencies', 'schedules.SUBJECT_CODE', 'schedules.COURSE_NUMBER']);
    }

    foreach($errors as $check)
    {

      $dependencies = json_decode($check->dependencies);
      $course = $target->SUBJECT_CODE . " " . $target->COURSE_NUMBER;
      $course = strtolower($course);

      if(in_array($course, $dependencies))
      {
        $errors_deleted[] = $check->id;
        $delete = FlowchartError::find($check->id)->delete();
      }
    }

    return $errors_deleted;
  }

  /**
   * Function that checks pre requisites of courses behind moved course
   * @param Instance of Schedule: id
   * @return Courses present in future semesters that have the course as a pre requisite
   **/
  public function lookBackwards($target)
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $errors = [];

    $sched_behind = Schedule::where('degree_id', $degree->id)
                    ->where('semester', '<=', $target->semester)
                    ->get();

    foreach($sched_behind as $sched)
    {

      $prereqs = course::where('SUBJECT_CODE', $sched->SUBJECT_CODE)
                ->where('COURSE_NUMBER', $sched->COURSE_NUMBER)
                ->first(['prerequisites']);

      if($prereqs == null)
      {
         continue;
      }

      $prereqs = $prereqs->prerequisites;


      $course = $target->SUBJECT_CODE . " " . $target->COURSE_NUMBER;
      $course = strtolower($course);

      if($prereqs !== "" && strpos($prereqs, $course))
      {
        $check_course = $this->checkPrerequisites($sched);
        $errors = array_merge($errors, $check_course);
      }
    }
    return $errors;
  }

  /**
  * Creates List of errors with dependencies
  * @param List of courses: [['SUB_CODE', 'COURSE_NUMBER']]; Instance of Schedule:
  * @return void
  **/
  public function createErrorWithMessage($courseList, $target)
  {
    $message = $target->SUBJECT_CODE . " " . $target->COURSE_NUMBER . " is missing prerequisite: ";
    $dependencies = [];

    $i = 0;
    foreach($courseList as $course)
    {
      if($i == count($courseList) - 1)
      {
        $message .= $course[0] . " " . $course[1];
      }
      else
      {
        $message .= $course[0] . " " . $course[1] . " or ";
      }

      $dependencies[] = $course[0] . " " . $course[1];
      $i++;
    }
    $error_messages[] = $message;

    //making sure the error message doesn't already exist in the DB before adding the error!
    $newError = true;
    $errorAlreadyExistsCheck = FlowchartError::where('schedule_id', $target->id)->get();
    foreach($errorAlreadyExistsCheck as $error)
    {
      if($error->message == $message)
      {
          $newError = false;
          break;
      }
    }

    if($newError)
    {
      $id = $this->create_error($target->user_id, $target->id, $dependencies, $message, "prereq__error");
    }
  }

  public function get_errors(Request $request)
  {
    $degree = Session::get('degree');

    if($degree == null)
    {
      return;
    }

    $errorsJSON = [];

    $allScheduleID = $this->getAllSchedId($degree);

    $errors = FlowchartError::whereIn('schedule_id' , $allScheduleID)->get();

    foreach($errors as $error)
    {
      $errorsJSON[] = [$error->id, $error->schedule_id, $error->message, $error->type];
    }

    return json_encode($errorsJSON);
  }

  public function empty_errors($target)
  {
    $errors = FlowchartError::where('schedule_id', $target->id)->get();

    $id_array = [];

    foreach($errors as $error)
    {
      $id_array[] = $error->id;
      $error->delete();
    }

    return $id_array;
  }
}
