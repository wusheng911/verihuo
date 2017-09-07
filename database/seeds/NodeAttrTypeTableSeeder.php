<?php

use Illuminate\Database\Seeder;

class NodeAttrTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('node_attr_type')->truncate();
        //1
        DB::table('node_attr_type')->insert([
            'name' => 'Image Path',
            'display_label' => '图片路径',
            'value_type' => 'filepath',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //2
        DB::table('node_attr_type')->insert([
            'name' => 'Image Link',
            'display_label' => '图片链接',
            'value_type' => 'strval',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //3
        DB::table('node_attr_type')->insert([
            'name' => 'Content Id',
            'display_label' => '文章',
            'value_type' => 'article',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //4
        DB::table('node_attr_type')->insert([
            'name' => 'Image Title',
            'display_label' => '图片描述',
            'value_type' => 'strval',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //5
        DB::table('node_attr_type')->insert([
            'name' => 'Product Id',
            'display_label' => '商品',
            'value_type' => 'product',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        
    }
}
