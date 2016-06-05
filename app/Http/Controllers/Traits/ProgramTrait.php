<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;

trait ProgramTrait
{
  use ParsingTrait;
  /**
  * Function that returns All Faculties in University
  * @param void
  * @return String array: All faculties in University
  **/
  public function getFaculties()
  {
    $faculties_PDO = DB::table('programs')
                  ->groupBy('PROGRAM_TEACHING_FACULTY')
                  ->get(['PROGRAM_TEACHING_FACULTY']);
    $faculties = [];

    foreach($faculties_PDO as $fac)
    {
      $faculties[] = $fac->PROGRAM_TEACHING_FACULTY;
    }

    return $faculties;
  }

  /**
  * Function that returns All Majors In a certain Faculty
  * @param String: Faculty name
  * @return String array of all faculties
  **/
  public function getMajors($faculty)
  {
    $majors_PDO = DB::table('Programs')
                  ->where('PROGRAM_TEACHING_FACULTY', $faculty)
                  ->where('FIELD_OF_STUDY', 'MAJOR')
                  ->groupBy('PROGRAM_MAJOR')
                  ->get(['PROGRAM_MAJOR', 'PROGRAM_ID']);

    $majors = [];

    foreach($majors_PDO as $major)
    {
      $majors[] = [$major->PROGRAM_MAJOR,$major->PROGRAM_ID];
    }

    return $majors;
  }

  /**
  * Function that returns All Groups In a certain Major
  * @param int: ProgramID
  * @return String array of all Group names
  **/
  public function getGroups($programID)
  {
    $groups_PDO = DB::table('Programs')
                  ->where('PROGRAM_ID', $programID)
                  ->whereNotNull('SUBJECT_CODE')
                  ->whereNotNull('COURSE_NUMBER')
                  ->groupBy('SET_TITLE_ENGLISH')
                  ->get(['SET_TITLE_ENGLISH', 'SET_BEGIN_TEXT_ENGLISH']);

    $groups = [];

    foreach($groups_PDO as $group)
    {
      $this->extractCreditFromDesc($group->SET_BEGIN_TEXT_ENGLISH);
      if(!is_null($group->SET_TITLE_ENGLISH) && !is_null($group->SET_BEGIN_TEXT_ENGLISH))
      {
        $groups[$group->SET_TITLE_ENGLISH] = $this->extractCreditFromDesc($group->SET_BEGIN_TEXT_ENGLISH);
      }
    }
    arsort($groups);
    return $groups;
  }

  /**
  *
  **/
  public function getCoursesInGroup($programID, $group)
  {
    $courses_PDO = DB::table('Programs')
                  ->where('PROGRAM_ID', $programID)
                  ->where('SET_TITLE_ENGLISH', $group)
                  ->get(['SUBJECT_CODE', 'COURSE_NUMBER', 'COURSE_CREDITS']);

    $coursesInGroup = [];

    foreach($courses_PDO as $course)
    {

      $coursesInGroup[] = [$course->SUBJECT_CODE, $course->COURSE_NUMBER, $course->COURSE_CREDITS];
    }

    return $coursesInGroup;
  }
}
