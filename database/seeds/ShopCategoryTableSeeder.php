<?php

use Illuminate\Database\Seeder;

class ShopCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shop_categories')->truncate();
                
        DB::table('shop_categories')->insert([
            'id' => '1',
            'pid' => '0',
            'pos' => '1',
            'name' => '服装',
            'description' => '服装',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);        
        DB::table('shop_categories')->insert([
            'id' => '2',
            'pid' => '1',
            'pos' => '1',
            'name' => '男装',
            'description' => '男装',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_categories')->insert([
            'id' => '3',
            'pid' => '0',
            'pos' => '0',
            'name' => '婚礼食品',
            'description' => '婚礼食品',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_categories')->insert([
            'id' => '4',
            'pid' => '3',
            'pos' => '0',
            'name' => '婚宴食品',
            'description' => '婚宴食品',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_categories')->insert([
            'id' => '5',
            'pid' => '4',
            'pos' => '0',
            'name' => '甜品桌',
            'description' => '甜品桌',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_categories')->insert([
            'id' => '6',
            'pid' => '0',
            'pos' => '0',
            'name' => '婚礼饰品',
            'description' => '婚礼饰品',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_categories')->insert([
            'id' => '7',
            'pid' => '6',
            'pos' => '0',
            'name' => '展厅装饰',
            'description' => '展厅装饰',
            'image' => '',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
    }
}