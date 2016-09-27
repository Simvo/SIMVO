<?php

use Illuminate\Database\Seeder;
use App\Stream;
use App\StreamStructure;

class StreamSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    Stream::truncate();
    StreamStructure::truncate();


    $V1_software_cegep = [
      '1_FALL' => ['ECSE 202', 'ECSE 205', 'MATH 262','MATH 263', 'Complementary Studies Group B (HSSML)**'],
      '1_WINTER' => ['COMP 250', 'ECSE 200', 'ECSE 222', 'ECSE 223', 'FACC 100', 'Complementary Studies Group B (HSSML)**'],
      '2_FALL' => ['COMP 206', 'ECSE 211', 'ECSE 321', 'ECSE 324', 'Natural Science Complementary 1'],
      '2_WINTER' => ['CCOM 206', 'COMP 251', 'ECSE 310', 'ECSE 316', 'MATH 363'],
      '3_FALL' => ['COMP 302', 'COMP 360', 'ECSE 326', 'ECSE 427', 'ECSE 429', 'FACC 300'],
      '3_WINTER' => ['COMP 421', 'COMP 529', 'ECSE 428', 'ECSE 456', 'FACC 400', 'Technical Complementary'],
    ];

    $V1_software_u0 = [
      '1_FALL' => ['CHEM 110', 'MATH 133', 'MATH 140','PHYS 131', 'FACC 100'],
      '1_WINTER' => ['CHEM 120', 'MATH 141', 'PHYS 142', 'Humanities and Social Sciences 1', 'Impact of Technology on Society'],
      '2_FALL' => ['COMP 202', 'ECSE 200', 'CCOM 206', 'MATH 262', 'MATH 263', 'Humanities and Social Sciences 2'],
      '2_WINTER' => ['COMP 206', 'COMP 250', 'ECSE 211', 'ECSE 222', 'ECSE 223', 'MATH 363'],
      '3_FALL' => ['COMP 251', 'ECSE 321', 'ECSE 324', 'ECSE 326', 'ECSE 429', 'FACC 400'],
      '3_WINTER' => ['COMP 302', 'COMP 529', 'ECSE 310', 'ECSE 316', 'ECSE 427'],
      '4_FALL' => ['COMP 360', 'ECSE 420', 'ECSE 456', 'FACC 300', 'Technical Complementary', 'Technical Complementary'],
      '4_WINTER' => ['COMP 421', 'ECSE 428', 'ECSE 457', 'Technical Complementary', 'Technical Complementary', 'Natural Science Complementary']
    ];


    $V2_software_cegep = [
      '1_FALL' => ['ECSE 202', 'ECSE 205', 'MATH 262','MATH 263', 'Complementary Studies Group B (HSSML)**'],
      '1_WINTER' => ['COMP 250', 'ECSE 200', 'ECSE 222', 'ECSE 223', 'FACC 100', 'Complementary Studies Group B (HSSML)**'],
      '2_FALL' => ['COMP 206', 'ECSE 211', 'ECSE 321', 'ECSE 324', 'Natural Science Complementary 1'],
      '2_WINTER' => ['CCOM 206', 'COMP 251', 'ECSE 310', 'ECSE 316', 'MATH 363'],
      '3_FALL' => ['COMP 302', 'COMP 360', 'ECSE 326', 'ECSE 427', 'ECSE 429', 'FACC 300'],
      '3_WINTER' => ['COMP 421', 'COMP 529', 'ECSE 428', 'ECSE 456', 'FACC 400', 'Technical Complementary'],
    ];

    $V2_software_u0 = [
      '1_FALL' => ['FACC 100', 'MATH 133', 'MATH 140','PHYS 131', 'Complementary Studies Group B (HSSML) - 1'],
      '1_WINTER' => ['CHEM 120', 'ECSE 202', 'MATH 141', 'PHYS 142', 'Complementary Studies Group A (Impact)'],
      '2_FALL' => ['CCOM 206', 'ECSE 200', 'ECSE 205', 'MATH 262', 'MATH 263', 'Complementary Studies Group B (HSSML) - 2'],
      '2_WINTER' => ['COMP 206', 'COMP 250', 'ECSE 211', 'ECSE 222', 'ECSE 223', 'MATH 363'],
      '3_FALL' => ['COMP 251', 'ECSE 321', 'ECSE 324', 'ECSE 326', 'ECSE 429', 'FACC 400'],
      '3_WINTER' => ['COMP 302', 'COMP 529', 'ECSE 310', 'ECSE 316', 'ECSE 427'],
      '4_FALL' => ['COMP 360', 'ECSE 420', 'ECSE 456', 'FACC 300', 'Technical Complementary', 'Technical Complementary'],
      '4_WINTER' => ['COMP 421', 'ECSE 428', 'ECSE 457', 'Technical Complementary', 'Technical Complementary', 'Natural Science Complementary']
    ];


    $Engineering['100533'] = [
      '1'=>[],
      '2'=>[
        'Software Engineering Curriculum Gegep Entry'=>$V2_software_cegep,
        'Software Engineering Curriculum Non-Gegep Entry'=>$V2_software_u0
        ]
      ];

    $software_cegep = [];

    $electrical_cegep = [];

    $computer_cegep = [];

    foreach($Engineering as $program_id=>$versions)
    {
      foreach($versions as $version=>$stream)
      {
        foreach($stream as $stream_name=>$semesters)
        {
          $structure = new StreamStructure;
          $structure->stream_name = $stream_name;
          $structure->program_id = $program_id;
          $structure->version = $version;
          $structure->save();

          $i = 0;
          foreach($semesters as $term=>$courses)
          {
            $i++;
            foreach($courses as $course)
            {
              $stream = new Stream;
              $stream->structure_id = $structure->id;
              $stream->semester_index = $i;
              $stream->term = $term;
              $stream->course = $course;
              $stream->status = 'required';
              $stream->save();
            }
          }
        }
      }
    }
  }
}
