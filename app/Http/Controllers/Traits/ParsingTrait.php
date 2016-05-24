<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use DB;

trait ParsingTrait
{
  public function extractCreditFromDesc($desc)
  {
    //Finds first mention of some number followed by 'credits'
    $pattern = '/[1-9]+ credits/';
    preg_match($pattern, $desc, $matches);

    if($matches != []){
      //extracts the int value from the identified substring
      $pattern = '/[1-9]+/';
      preg_match($pattern, $matches[0], $matches2);
      return (int) $matches2[0];
    }
    else{
      return 'there is no credit value in the given string';
    }

  }
}
