<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Schedule;
use DB;
use Auth;

trait CurlTrait
{

  public function createVSBSchedule($semester)
  {

  }

  public function checkCourseAvailablity($SUBJECT_CODE, $COURSE_NUMBER, $semester)
  {
    $course = strtolower($SUBJECT_CODE)."+".$COURSE_NUMBER;
    $url = "https://vsb.mcgill.ca/criteria.jsp?session_" . $semester . "=1&code_number=".$course."&add_course=Add&remove_course=&view_details=&cams=all&tip=1&pins=&sf=ffftimeinclass&bbs=&submit_action=";

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
    libxml_clear_errors();
    libxml_use_internal_errors($previous_value);
  	$dom_xpath = new \DOMXpath($dom_document);

    $elements = $dom_xpath->query("//div[@class='warningNoteBad']");

    $warnings = [];

    foreach ($elements as $i => $element)
  	{
	    $warnings[] = $element->nodeValue;
  	}

    return $warnings;
  }
}
