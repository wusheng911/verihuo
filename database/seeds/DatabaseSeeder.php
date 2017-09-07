<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        if ((config('app.env') != 'production') || (config('app.env') != 'staging')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->call(NodeTypeTableSeeder::class);
            $this->call(NodeTableSeeder::class);
            $this->call(NodeAttrTypeTableSeeder::class);
            $this->call(NodeAttrTableSeeder::class);
            $this->call(NodeTypeMetadataTableSeeder::class);
            $this->call(AdPositionTableSeeder::class);
            $this->call(AdPositionNodeTableSeeder::class);
            $this->call(ContentSeeder::class);
            //$this->call(EntrustSeeder::class);
            $this->call(ContentCommentTableSeeder::class);
            $this->call(ProductTableSeeder::class);
            $this->call(SkuTableSeeder::class);
            $this->call(ShopCategoryTableSeeder::class);
            $this->call(MerchantTableSeeder::class);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            echo "Please do not run seed command on production/staging eniornment!\n";
        }
    }
}
