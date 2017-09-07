<?php

use Illuminate\Database\Seeder;

class NodeTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('node_type')->truncate();
        //1
        DB::table('node_type')->insert([
            'name' => 'Slide Item',
            'template' => 'nodes.mobile.slideitem',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //2
        DB::table('node_type')->insert([
            'name' => 'Content Item',
            'template' => 'nodes.mobile.contentitem',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //3
        DB::table('node_type')->insert([
            'name' => 'Banner',
            'template' => 'nodes.mobile.banner',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //4
        DB::table('node_type')->insert([
            'name' => 'pc slideitem',
            'template' => 'nodes.pc.slideitem',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //5
        DB::table('node_type')->insert([
            'name' => 'BannerNoRadius',
            'template' => 'nodes.mobile.bannernoradius',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //6
        DB::table('node_type')->insert([
            'name' => 'ShopCoverFlow',
            'template' => 'nodes.mobile.shopcoverflow',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
    }
}
