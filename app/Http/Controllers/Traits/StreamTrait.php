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

    // Generate exemptions based on difference of program and stream chosen
    $generateExemptions = $this->generateExemptions($degree, $streamStructure);

    $coursesInStream = $this->getCoursesInStream_array($streamStructure);
    $coursesInStream = $this->getCoursesInStream_list($streamStructure);
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
      throw exception("Stream Not Found");
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
          $courses[$term->term][] = $courseInTerm->course;
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

  }
}
