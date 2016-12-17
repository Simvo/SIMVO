<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Schedule;
use DB;
use Auth;
use Debugbar;

trait CurlTrait
{

  public function createVSBSchedule($semester)
  {

  }

  public function checkCourseAvailablity($SUBJECT_CODE, $COURSE_NUMBER, $semester)
  {
    //return []; // turn off vsb until we review new version

    $course = strtolower($SUBJECT_CODE)."+".$COURSE_NUMBER;
    $url = "https://vsb.mcgill.ca/vsb/api/stringToFilter?term=". $semester ."&input=". $course ."&current=&isimport=0&_=1481994433538";

    Debugbar::info($url);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if($httpCode == 404)
        return json_encode("ERROR");

    $previous_value = libxml_use_internal_errors(TRUE);
    $dom_document = new \DOMDocument();
  	$dom_document->loadHTMLFile(mb_convert_encoding($url, 'HTML-ENTITIES', 'UTF-8'));
    Debugbar::info($dom_document);
    libxml_clear_errors();
    libxml_use_internal_errors($previous_value);
  	$dom_xpath = new \DOMXpath($dom_document);

    $elements = $dom_xpath->query("//p");

    $warnings = [];

    foreach ($elements as $i => $element)
  	{
      $message = json_decode($element->nodeValue);
      if($message[0] == "error")
      {
        $warnings[] = $message[1];
      }
  	}

    return $warnings;
  }
}
