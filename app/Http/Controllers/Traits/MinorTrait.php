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
  public function generateProgressBar($degree)
  {
    $groups = $this->getGroupsWithCreditsMinor($degree);

    $progress = [];

    foreach ($groups as $key=>$value)
    {
      $courses = $this->getCoursesInGroup($degree, $key, false);

      $totCredits = $value;
      $creditsTaken = 0;
      foreach($courses as $course)
      {
        $check = Schedule::where('degree_id', $degree->id)
                 ->where('SUBJECT_CODE', $course[0])
                 ->where('COURSE_NUMBER', $course[1])
                 ->get();

        if(count($check)>0)
          $creditsTaken += $this->getCourseCredits($course[0], $course[1]);
      }

      $progress[$key] = [$creditsTaken,$value];
    }
    return $progress;
  }


}
