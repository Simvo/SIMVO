<?php

use Illuminate\Database\Seeder;

class PreRequisites extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get every course in Programs table
        $courses = DB::table('Programs')
                   ->whereNotNull('SUBJECT_CODE')
                   ->whereNotNull('COURSE_NUMBER')
                   ->groupBy('SUBJECT_CODE')
                   ->groupBy('COURSE_NUMBER')
                   ->get(['SUBJECT_CODE', 'COURSE_NUMBER']);
    }
}
