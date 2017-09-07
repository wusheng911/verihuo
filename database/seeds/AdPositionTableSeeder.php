<?php

use Illuminate\Database\Seeder;

class AdPositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adpositions')->truncate();
    }
}
