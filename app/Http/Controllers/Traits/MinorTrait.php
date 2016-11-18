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
}
