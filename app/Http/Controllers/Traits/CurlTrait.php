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

  public function createVSBSchedule($courses, $semester)
  {
    $base_url = "https://vsb.mcgill.ca/vsb/criteria.jsp?access=0&lang=en&tip=0&page=results&scratch=0&term=201701&sort=none&filters=iiiiiiiii&bbs=&ds=&cams=Distance_Downtown_Macdonald_Off-Campus&locs=any&isrts=&";
    foreach($courses as $course)
    {
      $course_name = strtolower($SUBJECT_CODE)."+".$COURSE_NUMBER;
      $base_url .= "course_0_0=". $course_name . "&sa_0_0=&cs_0_0=--".$semester ."_9182--&cpn_0_0=&csn_0_0=&ca_0_0=&dropdown_0_0=al&ig_0_0=0&rq_0_0=&";
    }
    //https://vsb.mcgill.ca/vsb/criteria.jsp?access=0&lang=en&tip=0&page=results&scratch=0&term=201701&sort=none&filters=iiiiiiiii&bbs=&ds=&cams=Distance_Downtown_Macdonald_Off-Campus&locs=any&isrts=&course_0_0=COMP-202&sa_0_0=&cs_0_0=--201701_9182--&cpn_0_0=&csn_0_0=&ca_0_0=&dropdown_0_0=al&//ig_0_0=0&rq_0_0=&course_1_0=ECSE-305&sa_1_0=&cs_1_0=--201701_324-325-&cpn_1_0=&csn_1_0=&ca_1_0=&dropdown_1_0=al&ig_1_0=0&rq_1_0=&course_2_0=ECSE-428&sa_2_0=&cs_2_0=--201701_351--&cpn_2_0=&csn_2_0=&ca_2_0=&dropdown_2_0=al&ig_2_0=0&rq_2_0=
  }

  public function checkCourseAvailablity($SUBJECT_CODE, $COURSE_NUMBER, $semester)
  {

    $course = strtolower($SUBJECT_CODE)."+".$COURSE_NUMBER;
    $url = "https://vsb.mcgill.ca/vsb/api/stringToFilter?term=". $semester ."&input=". $course ."&current=&isimport=0&_=1481994433538";

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
      Debugbar::info($message);
      if($message[0])
      {
        $warnings[] = $message[0]->error;
        Debugbar::info($message[0]->error);
      }
  	}

    return $warnings;
  }
}
