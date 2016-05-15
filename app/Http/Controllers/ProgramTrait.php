<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;

trait ProgramTrait
{
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
                  ->groupBy('PROGRAM_MAJOR')
                  ->get(['PROGRAM_MAJOR', 'PROGRAM_ID']);

    $majors = [];

    foreach($majors_PDO as $major)
    {
      $majors[] = [$major->PROGRAM_MAJOR,$major->PROGRAM_ID];
    }

    return $majors;
  }

}
