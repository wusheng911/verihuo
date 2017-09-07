<?php

use Illuminate\Database\Seeder;

class SkuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skus')->truncate();
        DB::table('sku_attributes')->truncate();
        DB::table('shop_attributes')->truncate();
        DB::table('shop_attribute_values')->truncate();
                
        //shop attributes
        DB::table('shop_attributes')->insert([
            'id' => '1',
            'pid' => '0',
            'shop_category_id' => '7',
            'name' => '品牌',
            'show_name' => '品牌',
            'required' => '1',
            'is_base' => '1',
            'is_sell' => '0',
            'is_optional' => '0',
            'status' => '1',
            'pos' => '0',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);        
        DB::table('shop_attributes')->insert([
            'id' => '2',
            'pid' => '1',
            'level' => 1,
            'shop_category_id' => '7',
            'name' => 'Fur 花艺',
            'show_name' => '',
            'required' => '1',
            'is_base' => '1',
            'is_sell' => '0',
            'is_optional' => '0',
            'status' => '1',
            'pos' => '0',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attributes')->insert([
            'id' => '3',
            'pid' => '0',
            'shop_category_id' => '7',
            'name' => '尺寸',
            'show_name' => '尺寸',
            'required' => '1',
            'is_base' => '0',
            'is_sell' => '1',
            'is_optional' => '0',
            'status' => '1',
            'pos' => '2',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attributes')->insert([
            'id' => '4',
            'pid' => '0',
            'shop_category_id' => '7',
            'name' => '颜色',
            'show_name' => '颜色',
            'required' => '1',
            'is_base' => '0',
            'is_sell' => '1',
            'is_optional' => '0',
            'status' => '1',
            'pos' => '3',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attributes')->insert([
            'id' => '5',
            'pid' => '0',
            'shop_category_id' => '7',
            'name' => '使用方法',
            'show_name' => '使用方法',
            'required' => '0',
            'is_base' => '0',
            'is_sell' => '0',
            'is_optional' => '1',
            'status' => '1',
            'pos' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attributes')->insert([
            'id' => '6',
            'pid' => '1',
            'level' => 1,
            'shop_category_id' => '7',
            'name' => 'Fur 婚纱',
            'show_name' => '',
            'required' => '1',
            'is_base' => '1',
            'is_sell' => '0',
            'is_optional' => '0',
            'status' => '1',
            'pos' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        //shop attribute values
        DB::table('shop_attribute_values')->insert([
            'id' => '1',
            'shop_attribute_id' => '1',
            'value' => '真爱婚庆',
            'status' => '1',
            'pos' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '2',
            'shop_attribute_id' => '3',
            'value' => '大号心形',
            'status' => '1',
            'pos' => '2',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '3',
            'shop_attribute_id' => '3',
            'value' => '小号心形',
            'status' => '1',
            'pos' => '3',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '4',
            'shop_attribute_id' => '4',
            'value' => '紫色高贵',
            'status' => '1',
            'pos' => '4',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '5',
            'shop_attribute_id' => '4',
            'value' => '粉色浪漫',
            'status' => '1',
            'pos' => '5',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '6',
            'shop_attribute_id' => '4',
            'value' => '蓝色真情',
            'status' => '1',
            'pos' => '6',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '7',
            'shop_attribute_id' => '5',
            'value' => '请勿使用氢气，使用专用充气设备充气',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '8',
            'shop_attribute_id' => '1',
            'value' => 'Fur 花艺',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '9',
            'shop_attribute_id' => '2',
            'value' => '节日系列1',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '10',
            'shop_attribute_id' => '2',
            'value' => '节日系列2',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '11',
            'shop_attribute_id' => '1',
            'value' => 'Fur 婚纱',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '12',
            'shop_attribute_id' => '6',
            'value' => '瘦人系列1',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('shop_attribute_values')->insert([
            'id' => '13',
            'shop_attribute_id' => '6',
            'value' => '大众系列2',
            'status' => '1',
            'pos' => '7',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        
        //skus
        DB::table('skus')->insert([
            'id' => '1',
            'product_id' => '3',
            'product_name' => 'Godiva典藏金装礼盒',
            'merchant_id' => "1",
            'sn' => '',
            'barcode' => '',
            'quantity' => '20',
            'price' => '19.80',
            'prime_price' => '10.00',
            'sale_attribute_values' => '2,4',
            'attribute_values' => '2,4,8,10',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);   
        DB::table('skus')->insert([
            'id' => '2',
            'product_id' => '3',
            'product_name' => 'Godiva典藏金装礼盒',
            'merchant_id' => "1",
            'sn' => '',
            'barcode' => '',
            'quantity' => '9',
            'price' => '49.80',
            'prime_price' => '20.00',
            'sale_attribute_values' => '2,5',
            'attribute_values' => '2,5,8,10',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        
        //sku_attributes
        DB::table('sku_attributes')->insert([
            'id' => '1',
            'sku_id' => '1',
            'product_id' => '3',
            'attribute_value_id' => '2',
            'attribute_id' => '3',
            'is_sell' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('sku_attributes')->insert([
            'id' => '2',
            'sku_id' => '1',
            'product_id' => '3',
            'attribute_value_id' => '4',
            'attribute_id' => '4',
            'is_sell' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('sku_attributes')->insert([
            'id' => '3',
            'sku_id' => '2',
            'product_id' => '3',
            'attribute_value_id' => '2',
            'attribute_id' => '3',
            'is_sell' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('sku_attributes')->insert([
            'id' => '4',
            'sku_id' => '2',
            'product_id' => '3',
            'attribute_value_id' => '5',
            'attribute_id' => '4',
            'is_sell' => '1',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('sku_attributes')->insert([
            'id' => '5',
            'sku_id' => '0',
            'product_id' => '3',
            'attribute_value_id' => '8',
            'attribute_id' => '1',
            'is_sell' => '0',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]); 
        DB::table('sku_attributes')->insert([
            'id' => '6',
            'sku_id' => '0',
            'product_id' => '3',
            'attribute_value_id' => '10',
            'attribute_id' => '2',
            'is_sell' => '0',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);         
    }
}