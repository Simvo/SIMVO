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
    var_dump($desc);
    $regexPattern = "/[0-9]/";

    $seach = preg_match($regexPattern, $desc, $matches,  PREG_OFFSET_CAPTURE);

    var_dump($matches);
  }
}
