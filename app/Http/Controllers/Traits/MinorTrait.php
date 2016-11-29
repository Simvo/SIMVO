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
use Session;

trait MinorTrait
{
  /**
  * Function that returns groups and the number of credits in them along
  * with the number of credits the user has completed in each group. This is the version for minors
  * @param Minor object type
  * @return array with key = groupName => [credits taken,total dredits in group]
  **/
  public function generateProgressBarMinor($minor)
  {
    $groups = $this->getGroupsWithCreditsMinor($minor);
    $progress = [];

    foreach ($groups as $key=>$value)
    {
      $courses = $this->getCoursesInGroup($minor, $key, false);

      $totCredits = $value;
      $creditsTaken = 0;
      $check = [];
      foreach($courses as $course)
      {

        $check = Schedule::where('degree_id', $minor->degree_id)
                 ->where('SUBJECT_CODE', $course[0])
                 ->where('COURSE_NUMBER', $course[1])
                 ->get();

        if(count($check)>0)
          $creditsTaken += $this->getCourseCreditsMinor($course[0], $course[1]);
      }

      $progress[$key] = [$creditsTaken,$value];
    }
    return $progress;
  }

  /**
  * Function that returns All Groups In a certain Minor
  * @param int: ProgramID
  * @return String array of all Group names and credit count
  **/
  public function getGroupsWithCreditsMinor($minor)
  {
    $user = Auth::User();

    $groups_PDO = DB::table('programs')
                  ->where('PROGRAM_ID', $minor->program_id)
                  //->whereNotNull('SUBJECT_CODE')
                  //->whereNotNull('COURSE_NUMBER')
                  ->groupBy('SET_TITLE_ENGLISH')
                  ->get(['SET_TITLE_ENGLISH', 'SET_BEGIN_TEXT_ENGLISH']);

    $groups = [];

    foreach($groups_PDO as $group)
    {
      if(!is_null($group->SET_TITLE_ENGLISH) && !is_null($group->SET_BEGIN_TEXT_ENGLISH))
      {
        $creditsInGroup = $this->extractCreditFromDesc($group->SET_BEGIN_TEXT_ENGLISH);
        if($creditsInGroup > 0)
        {
          $groups[$group->SET_TITLE_ENGLISH] = $creditsInGroup;
        }
      }
    }

    arsort($groups);
    return $groups;
  }

  /**
  * Returns credits in course
  * @param User: Subject code and course number
  * @return int: number of credits in course
  **/
  public function getCourseCreditsMinor($sub_code, $course_num)
  {
    return DB::table('programs')->where('SUBJECT_CODE', $sub_code)
               ->where('COURSE_NUMBER', $course_num)
               ->First(['COURSE_CREDITS'])->COURSE_CREDITS;
  }

  public function getMinorGroupsWithCourses($minor, $filter)
  {
    $groups = [];
    $i;
    $courseCount;
    $complementaries = $this->getMinorComplementaryGroups($minor);
    //dd($complementaries);
    $groups[0] = $this->getMinorRequiredGroups($minor);
    $groups[1] = $complementaries[0]; //complementaries
    $groups[2] = $complementaries[1]; //electives

    for($i = 0; $i < 3; $i++)
    {
      $courseCount = 0;
      foreach($groups[$i] as $key=>$value)
      {
        $groups[$i][$key] = $this->getMinorCoursesInGroup($minor, $key, $filter);
        $courseCount += count($groups[$i][$key]);
      }
      if($courseCount == 0)
      {
        $groups[$i] = [];
      }
    }

    return $groups;
  }

  /**
  * Function that returns All Groups In a certain minor
  * @param int: ProgramID
  * @return String array of all Group names
  **/
  public function getMinorRequiredGroups($minor)
  {
    $user = Auth::User();

    $groups_PDO = DB::table('programs')
                  ->where('PROGRAM_ID', $minor->program_id)
                  ->where('SET_TYPE', 'Required')
                   ->whereNotNull('SUBJECT_CODE')
                   ->whereNotNull('COURSE_NUMBER')
                  ->groupBy('SET_TITLE_ENGLISH')
                  ->get(['SET_TITLE_ENGLISH', 'SET_BEGIN_TEXT_ENGLISH']);

    $groups = [];

    foreach($groups_PDO as $group)
    {
      if(trim($group->SET_TITLE_ENGLISH) != "")
      {
        $groups[$group->SET_TITLE_ENGLISH] = [];
      }
    }

    return $groups;
  }

  /**
  * Function that returns All Complementary Groups In a certain Minor
  * @param int: ProgramID
  * @return String array of all Group names
  **/
  public function getMinorComplementaryGroups($minor)
  {
    $user = Auth::User();

    $groups_PDO = DB::table('programs')
                  ->where('PROGRAM_ID', $minor->program_id)
                  ->where('SET_TYPE', 'Complementary')
                  // ->whereNotNull('SUBJECT_CODE')
                  // ->whereNotNull('COURSE_NUMBER')
                  ->groupBy('SET_TITLE_ENGLISH')
                  ->get(['SET_TITLE_ENGLISH', 'SET_BEGIN_TEXT_ENGLISH']);

    $groups = [[],[]];

    foreach($groups_PDO as $group)
    {
      //Index 0 is complementaries ------- Index 1 is electives
      if((trim($group->SET_TITLE_ENGLISH) != "" || is_null($group->SET_TITLE_ENGLISH)) && strpos($group->SET_TITLE_ENGLISH, "Group", 0 ) === false )
      {
        $groups[0][$group->SET_TITLE_ENGLISH] = [];
      }
      else {
        $groups[1][$group->SET_TITLE_ENGLISH] = [];
      }
    }

    return $groups;
  }

  /**
  * Function that returns all courses in a group
  * @param int: ProgramID, group, filter(if you want to exclude courses already in schedule)
  * @return array of arrays containing course information
  **/
  public function getMinorCoursesInGroup($minor, $group, $filter)
  {
    $user = Auth::User();
    $degree = Session::get('degree');

    $courses_PDO = DB::table('programs')
                  ->where('PROGRAM_ID', $minor->program_id)
                  ->where('SET_TITLE_ENGLISH', $group)
                  ->whereNotNull('SUBJECT_CODE')
                  ->whereNotNull('COURSE_NUMBER')
                  ->orderBy('COURSE_NUMBER', 'asc')
                  ->get(['SUBJECT_CODE', 'COURSE_NUMBER', 'COURSE_CREDITS','SET_TYPE','COURSE_TITLE']);
    if(count($courses_PDO) < 2)
    {
      $param = str_replace(" Courses", "", $group);
      $courses_PDO = DB::table('programs')
                    ->where('PROGRAM_ID', $minor->program_id)
                    ->where('SET_TYPE', $param)
                    ->whereNotNull('SUBJECT_CODE')
                    ->whereNotNull('COURSE_NUMBER')
                    ->orderBy('COURSE_NUMBER', 'asc')
                    ->get(['SUBJECT_CODE', 'COURSE_NUMBER', 'COURSE_CREDITS','SET_TYPE','COURSE_TITLE']);
    }

    $coursesInGroup = [];

    foreach($courses_PDO as $course)
    {

      if($filter)
      {
        $checkIfInSchedule = Schedule::where('degree_id', $degree->id)
                             ->where('SUBJECT_CODE', $course->SUBJECT_CODE)
                             ->where('COURSE_NUMBER', $course->COURSE_NUMBER)
                             ->get();
        if(count($checkIfInSchedule) > 0)
          continue;
      }

      $coursesInGroup[] = [$course->SUBJECT_CODE, $course->COURSE_NUMBER, $course->COURSE_CREDITS, $course->SET_TYPE, $course->COURSE_TITLE];

    }

    return $coursesInGroup;
  }

}
