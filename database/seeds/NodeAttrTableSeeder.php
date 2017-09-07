<?php

use Illuminate\Database\Seeder;

class NodeAttrTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('node_attr')->truncate();
        DB::table('node_attr')->insert([
            'node_id' => '1',
            'node_attr_id' => '1',
            'value' => '/assets/img/kv1.jpg',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '2',
            'node_attr_id' => '1',
            'value' => '/assets/img/kv2.jpg',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        
        DB::table('node_attr')->insert([
            'node_id' => '1',
            'node_attr_id' => '2',
            'value' => '/view/article/1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '2',
            'node_attr_id' => '2',
            'value' => '/view/article/1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '3',
            'node_attr_id' => '3',
            'value' => '10000',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '4',
            'node_attr_id' => '1',
            'value' => '/assets/img/queen.jpg',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '4',
            'node_attr_id' => '2',
            'value' => '/view/article/1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '5',
            'node_attr_id' => '1',
            'value' => '/assets/img/kv1.jpg',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_attr')->insert([
            'node_id' => '5',
            'node_attr_id' => '2',
            'value' => '/view/article/1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);     
        //Shop Cover Flow 商城首页轮播
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '1',
            'value' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '2',
            'value' => '2',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '3',
            'value' => '3',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '4',
            'value' => '4',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '5',
            'value' => '5',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);       
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '6',
            'value' => '6',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '7',
            'value' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '8',
            'value' => '8',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '9',
            'value' => '9',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '10',
            'value' => '10',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);   
        DB::table('node_attr')->insert([
            'node_id' => '6',
            'node_attr_id' => '5',
            'seq_no' => '11',
            'value' => '11',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
    }
}
