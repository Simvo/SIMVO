<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;

trait ParsingTrait
{
  //Returns int value of credits for the group or 0 on failure
  public function extractCreditFromDesc($desc)
  {
    //Finds first mention of some number followed by 'credits'
    $pattern = '/[0-9]+ credits/';
    preg_match($pattern, $desc, $matches);

    if($matches != [])
    {
      //extracts the int value from the identified substring
      $pattern = '/[0-9]+/';
      preg_match($pattern, $matches[0], $matches2);
      return (int) $matches2[0];
    }
    else
    {
      return 0;
    }
  }
}
