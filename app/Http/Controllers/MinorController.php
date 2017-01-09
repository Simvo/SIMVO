<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Session;
use App\Schedule;
use App\UICourse;
use App\Degree;
use App\FlowchartError;
use App\Minor;
use DB;

class MinorController extends Controller
{
  use Traits\NewObjectsTrait;
  use Traits\Tools;
  use Traits\MinorTrait;

  public function getMinorGroupsWithCourses($minor, $filter)
  {
    $groups = [];
    $i;
    $courseCount;
    $complementaries = $this->getMinorComplementaryGroups($minor);
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
        $groups[$i] = null;
      }
    }

    return $groups;
  }

  /**
  * Function thats adds a minor to a degree or changes one if one is already present
  * @param: program id of minor
  * @return: void. redirects back to flochart
  **/
  public function addMinor(Request $request)
  {
    $degree = Session::get('degree');
    $program_id = $request->minor_chosen;
    $this->validate($request, [
    'minor_chosen'=>'required'
    ]);

    if(is_null($program_id) || is_null($degree)) return redirect('/flowchart');

    $check = Minor::where('degree_id', $degree->id)->get();
    $minor = DB::table('programs')->where('PROGRAM_ID', $program_id)->first();
    $minor_name = $minor->PROGRAM_MAJOR;
    $minor_credits = $minor->PROGRAM_TOTAL_CREDITS;
    $version_id = $minor->VERSION;

    if(count($check) > 0) // Change minor
    {
      if($check[0]->program_id != $program_id) // make sure they are not the same
      {
        $check[0]->program_id = $program_id;
        $check[0]->minor_name = $minor_name;
        $check[0]->minor_credits = $minor_credits;
        $check[0]->save();
      }
    }
    else // create new minor
    {
      $version_id = $minor->VERSION;$this->create_minor($degree->id, $program_id, $minor_name, $minor_credits, $version_id);
    }

    return redirect('/flowchart');
  }

  /**
  * Functions that removes instance of minor and all courses that belong to it. (but not ones that also belong to the major)
  * @param: program id of minor
  * @return: void. redirects back to flochart
  **/
  public function removeMinor()
  {
    $degree = Session::get('degree');
    if($degree == null)
    {
      return;
    }

    $minor = Minor::where("degree_id", $degree->id)->first();
    if(!$minor) return redirect('/flowchart');
    
    $this->removeMinorCourses($minor, $degree);
    $minor->delete();

    return redirect('/flowchart');
  }
}
