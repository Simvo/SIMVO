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

    $software_cegep = [
      '1_FALL' => ['ECSE 202', 'ECSE 205', 'MATH 262','MATH 263', 'Complementary Studies Group B (HSSML)**'],
      '1_WINTER' => ['COMP 250', 'ECSE 200', 'ECSE 222', 'ECSE 223', 'FACC 100', 'Complementary Studies Group B (HSSML)**'],
      '2_FALL' => ['COMP 206', 'ECSE 211', 'ECSE 321', 'ECSE 324', 'Natural Science Complementary 1'],
      '2_WINTER' => ['CCOM 206', 'COMP 251', 'ECSE 310', 'ECSE 316', 'MATH 363'],
      '3_FALL' => ['COMP 302', 'COMP 360', 'ECSE 326', 'ECSE 427', 'ECSE 429', 'FACC 300'],
      '3_WINTER' => ['COMP 421', 'COMP 529', 'ECSE 428', 'ECSE 456', 'FACC 400', 'Technical Complementary'],
      '4_FALL' => ['ECSE 420', 'Technical Complementary', 'Technical Complementary', 'Technical Complementary', 'Natural Science Complementary 2']
    ];

    $Engineering['100533'] = ['1'=>['Software Engineering Curriculum'=>$software_cegep]];

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
