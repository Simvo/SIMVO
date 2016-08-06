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

trait StreamTrait
{
  // dependencies
  use ScheduleTrait;

  /**
  * Starts Stream generation process and handles errors
  * with the number of credits the user has completed in each group
  * @param degree containing user ans stream information
  * @return void
  **/
  public function initiateStreamGenerator($degree)
  {
    $streamStructure = null;
    try{
      $streamStructure = $this->getStreamStructure($degree);
    } catch(Exception $e) {
      return;
    }

    // empty current degree schedule
    $this->emptySchedule($degree);

    // Generate exemptions based on difference of program and stream chosen
    $generateExemptions = $this->generateExemptions($degree, $streamStructure);

    // apply exemptions to schedule
    $this->applyExemptions($degree, $generateExemptions);

    // apply stream to schedule
    $this->applyCoursesInStream($degree, $streamStructure);
  }

  /**
  * Returns StreamStructure object associated with Degree
  * @param degree
  * @return StreamStructure Object or throws an exception if not present
  **/
  public function getStreamStructure($degree)
  {
    $streamStructure = StreamStructure::find($degree->stream_version);

    if(count($streamStructure) == 0)
    {
      throw new \Exception("Stream Not Found");
    }

    return $streamStructure;
  }

  /**
  * Returns courses in stream in simple list form
  * with the number of credits the user has completed in each group
  * @param current logged in user
  * @return array with key = groupName => [credits taken,total dredits in group]
  **/
  public function getCoursesInStream_list($StreamStructure)
  {
    $coursesInStream = Stream::where('structure_id', $StreamStructure->id)
                       ->get(['course']);
    $courses = [];
    foreach($coursesInStream as $courseInStream)
    {
      //Ensuring only course codes are added => required courses
      if(strlen($courseInStream->course) == 8)
      {
          $courses[] = $courseInStream->course;
      }
    }
    return $courses;
  }

  /**
  * returns courses in array structure containig academic year, semester, and list of courses in each semester
  * @param StreamStructure
  * @return ['U0_Fall' => [list of courses]]
  **/
  public function getCoursesInStream_array($StreamStructure)
  {
    $terms = Stream::where('structure_id', $StreamStructure->id)
             ->groupBy('term')
             ->get();

    $courses = [];
    foreach($terms as $term)
    {
      $coursesInTerm = Stream::where('structure_id', $StreamStructure->id)
                       ->where('term', $term->term)
                       ->get();

      $courses[$term->term] = [];
      foreach($coursesInTerm as $courseInTerm)
      {
        if($courseInTerm->course != null)
        {
          // for now, exclude elecitve spots. Will implement if we have time
          if(strlen($courseInTerm->course) == 8)
          {
            $courses[$term->term][] = $courseInTerm->course;
          }
        }
      }
    }
    return $courses;
  }

  /**
  * Calculates what courses are exemptions based on stream choice
  * @param StreamStructure, Degree
  * @return void. Creates instances of schedule (only exemption semester)
  **/
  public function generateExemptions($degree, $StreamStructure)
  {
    $exemptions = [];
    $requiredCoursesFromProgram = $this->getRequiredCourses($degree);
    $requiredCoursesFromStream = $this->getCoursesInStream_list($StreamStructure);

    foreach($requiredCoursesFromProgram as $programCourse)
    {
      $courseString = $programCourse[0] . " " . $programCourse[1];

      // if not in stream, course must be an exemption
      if(!in_array($courseString,$requiredCoursesFromStream))
      {
        $exemptions[] = [$programCourse[0], $programCourse[1]];
      }
    }
    return $exemptions;
  }

  public function applyExemptions($degree, $exemptions)
  {
    foreach($exemptions as $exemption)
    {
      $this->create_schedule(
        $degree,
        "Exemption",
        $exemption[0],
        $exemption[1],
        'Required'
      );
    }
  }

  public function applyCoursesInStream($degree, $StreamStructure)
  {
    $coursesInStream = $this->getCoursesInStream_array($StreamStructure);

    $currentSemester = $degree->enteringSemester;

    foreach($coursesInStream as $term=>$list)
    {
      $semester = explode("_", $term)[1];

      // Make sure we are adding into the correct semester
      $semCode = $this->convert_term_to_code($semester);

      if($semCode != substr($currentSemester, 4, 2))
      {
        $currentSemester = $this->get_next_semester($currentSemester);
      }

      foreach($list as $course)
      {
        $splitCourse = explode(" ", $course);
        $SUBJECT_CODE = $splitCourse[0];
        $COURSE_NUMBER = $splitCourse[1];

        $this->create_schedule(
          $degree,
          $currentSemester,
          $SUBJECT_CODE,
          $COURSE_NUMBER,
          'Required'
        );
      }
      $currentSemester = $this->get_next_semester($currentSemester);
    }
  }
}
