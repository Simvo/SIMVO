<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Upload programs table into memory_get_usage
        $sql = file_get_contents(__DIR__ . "/../../SQLFiles/programs.sql");
        DB::raw($sql);
    }
}
