<?php

use Illuminate\Database\Seeder;

class AdPositionNodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adposition_node')->truncate();
        DB::table('adposition_node')->insert([
            'adposition_id' => '1',
            'node_id' => '1'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '2',
            'node_id' => '2'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '3',
            'node_id' => '4'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '4',
            'node_id' => '3'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '5',
            'node_id' => '3'
        ]);  
        //我-瘦美人
        DB::table('adposition_node')->insert([
            'adposition_id' => '6',
            'node_id' => '4'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '7',
            'node_id' => '3'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '8',
            'node_id' => '3'
        ]);        
        //我-桃花运
        DB::table('adposition_node')->insert([
            'adposition_id' => '9',
            'node_id' => '4'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '10',
            'node_id' => '3'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '11',
            'node_id' => '3'
        ]);
        //要-玩星座
        DB::table('adposition_node')->insert([
            'adposition_id' => '12',
            'node_id' => '4'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '13',
            'node_id' => '3'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '14',
            'node_id' => '3'
        ]);
        //要-最佳餐厅
        DB::table('adposition_node')->insert([
            'adposition_id' => '15',
            'node_id' => '4'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '16',
            'node_id' => '3'
        ]);
        DB::table('adposition_node')->insert([
            'adposition_id' => '17',
            'node_id' => '3'
        ]);    
        //首页广告C区C1
        DB::table('adposition_node')->insert([
            'adposition_id' => '18',
            'node_id' => '5'
        ]);
        //首页广告C区C2
        DB::table('adposition_node')->insert([
            'adposition_id' => '19',
            'node_id' => '5'
        ]);  
        
        //手机端商城首页轮播
        DB::table('adposition_node')->insert([
            'adposition_id' => '20',
            'node_id' => '6'
        ]);         
    }
}
