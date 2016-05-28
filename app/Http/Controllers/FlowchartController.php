<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;


use App\Http\Requests;

class FlowchartController extends Controller
{

  use Traits\ProgramTrait;
  use Traits\tools;

  /*TEST VIEW*/
  /* THIS view is purely for testing the regex on getting group credits*/
  //just copied and pasted the following options to test
  use Traits\ParsingTrait;
  public function test(){
    /*possible options for input from the database:
      "3 credits from the following:"
      "3 credits (6 credits for students from Quebec CEGE..."
      "24 credits"
      "9 creditsThe purpose of this requirement is to pro..."
      "List B0-6 credits from the following:"
      "29 creditsGenerally, students admitted to Engineer..."
      "If you have successfully completed a course at CEG..."
      "If you are not proficient in a certain language, n..."
      "74 credits"
      "A Chemical Engineering student may complete the Bi..."
      NULL
      "Note: Engineering students may not use these cours..."
      "12 credits"
      "12 credits selected from courses outside the Depar..."
      "15 credits from the following lists, two courses o..."
      */
    return $this->extractCreditFromDesc("29 creditsGenerally, students admitted to Engineer...");
  }

    /**
    * Function called upon GET request. Will determine if shedule needs to be generated or simply displayed
    * Consists of generating four main parts.
    * 1) Progress Bar 2)Course Schedule 3) Complementary Courses 4) Elecitive Courses
    **/
    public function generateFlowChart()
    {
      $user=Auth::User();

      $progress = $this->generateProgressBar($user->programID);

      return view('flowchart', [
        'user'=>$user,
        'progress' => $progress
      ]);
    }

    public function generateProgressBar($programID)
    {
      $groups = $this->getGroups($programID);

      $progress = [];

      foreach ($groups as $key=>$value)
      {
        $courses = $this->getCoursesInGroup($programID, $key);

        $totCredits = $value;
        $creditsTaken = 0;

        foreach ($courses as $course)
        {
        }

        $progress[$key] = [0,$value];
      }

      return $progress;
    }


}
