<?php

use Illuminate\Database\Seeder;

class NodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nodes')->truncate();
        //node_id=1,node_id=2
        foreach(range(1,2) as $index) {
            DB::table('nodes')->insert([
                'node_type_id' => '1',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ]);          
        }        
        //Content 3
        DB::table('nodes')->insert([
            'node_type_id' => '2',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //Banner node_id=4
        DB::table('nodes')->insert([
            'node_type_id' => '3',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //BannerNoRadious node_id=5
        DB::table('nodes')->insert([
            'node_type_id' => '5',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        //ShopCoverFlow node_id=6
        DB::table('nodes')->insert([
            'node_type_id' => '6',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);        
    }
}
