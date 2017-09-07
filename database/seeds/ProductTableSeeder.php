<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $str = <<<HTML
            <div class="m-detail-info">
                <div class="info-container J_Node">
                    <p>作为向1965年第一只Henry手表的致敬，以及对传统制表业工艺的继承与沿袭，
                    这款直径41mm的里士满手表如今被设计师重新演绎。表盘的计时码表功能区域采用最先进的技术完成，
                    经典比例的表盘与表带在边缘处自然交汇衔接，半圆弧形的双层材质表面把深黑色的表盘映衬得气度非凡。
                    细长的分针指针运转精准，如同脉搏一般，生生不息。时分秒针日复一日地运转走动，而每一天，
                    每分每刻都与众不同。与此同时，表盘六点钟位置配有一块行事日历区域，每夜零点更替日期。
                    此款手表表盘的三块计时码表区域分别被赋予不同的功能：六点钟位置的区域承载秒针计时功能，
                    九点钟位置的区域用于分针计时，第三块区域则用于显示24小时制的时间格式。
                    柔软的真皮表带与佩戴者手腕弧度完美贴合，不仅更加凸显出此款手表的沉稳气质，
                    也为佩戴者营造出自然、舒适的佩戴体验。</p><p></p>
                </div>
            </div>
HTML;
        
        
        DB::table('products')->truncate();
        DB::table('product_images')->truncate();
        //product 1
        DB::table('products')->insert([
            'id' => '1',
            'merchant_id' => "1",
            'shop_category_id' => '3',
            'name' => 'Candy Master澳洲手工糖果',
            'sn' => '0',
            'info' => '美味糖果',
            'html_info' => $str,
            'show_price' => '59',
            'status' => '2',
            'is_available' => '1'
        ]);        
        DB::table('product_images')->insert([
            'id' => '1',
            'product_id' => '1',
            'image' => '/assets/img/goods-1.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 2
        DB::table('products')->insert([
            'id' => '2',
            'merchant_id' => "1",
            'shop_category_id' => '3',
            'name' => '维雅系列-波尔多杯',
            'sn' => '0',
            'info' => '精致酒杯',
            'html_info' => $str,
            'show_price' => '70',
            'status' => '2',
            'is_available' => '1'
        ]);        
        DB::table('product_images')->insert([
            'id' => '2',
            'product_id' => '2',
            'image' => '/assets/img/goods-2.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 3
        DB::table('products')->insert([
            'id' => '3',
            'merchant_id' => "1",
            'shop_category_id' => '3',
            'name' => 'Godiva典藏金装礼盒',
            'sn' => '0',
            'info' => '浓香巧克力',
            'html_info' => $str,
            'show_price' => '19.8',
            'status' => '2',
            'is_available' => '1'
        ]);        
        DB::table('product_images')->insert([
            'id' => '3',
            'product_id' => '3',
            'image' => '/assets/img/goods-3.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        DB::table('product_images')->insert([
            'id' => '12',
            'product_id' => '3',
            'image' => '/assets/img/goods-4.jpg',
            'pos' => '1',
            'is_first' => '0',
            'device' => '1'
        ]); 
        DB::table('product_images')->insert([
            'id' => '13',
            'product_id' => '3',
            'image' => '/assets/img/goods-5.jpg',
            'pos' => '2',
            'is_first' => '0',
            'device' => '1'
        ]); 
        DB::table('product_images')->insert([
            'id' => '14',
            'product_id' => '3',
            'image' => '/assets/img/goods-3.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '2'
        ]);
        DB::table('product_images')->insert([
            'id' => '15',
            'product_id' => '3',
            'image' => '/assets/img/goods-4.jpg',
            'pos' => '1',
            'is_first' => '0',
            'device' => '2'
        ]); 
        DB::table('product_images')->insert([
            'id' => '16',
            'product_id' => '3',
            'image' => '/assets/img/goods-5.jpg',
            'pos' => '2',
            'is_first' => '0',
            'device' => '2'
        ]);    
        DB::table('product_images')->insert([
            'id' => '17',
            'product_id' => '3',
            'image' => '/assets/img/goods-1.jpg',
            'pos' => '2',
            'is_first' => '0',
            'device' => '2'
        ]);  
        DB::table('product_images')->insert([
            'id' => '18',
            'product_id' => '3',
            'image' => '/assets/img/goods-2.jpg',
            'pos' => '2',
            'is_first' => '0',
            'device' => '2'
        ]);  
        DB::table('product_images')->insert([
            'id' => '19',
            'product_id' => '3',
            'image' => '/assets/img/goods-6.jpg',
            'pos' => '2',
            'is_first' => '0',
            'device' => '2'
        ]);         
        //product 4
        DB::table('products')->insert([
            'id' => '4',
            'merchant_id' => "1",
            'shop_category_id' => '3',
            'name' => 'Baci意大利进口巧克力',
            'sn' => '0',
            'info' => '精美礼盒',
            'html_info' => $str,
            'show_price' => '128',
            'status' => '2',
            'is_available' => '1'
        ]);       
        DB::table('product_images')->insert([
            'id' => '4',
            'product_id' => '4',
            'image' => '/assets/img/goods-4.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 5
        DB::table('products')->insert([
            'id' => '5',
            'merchant_id' => "1",
            'shop_category_id' => '3',
            'name' => '安德瑞 佳美娜红葡萄酒',
            'sn' => '0',
            'info' => '品质之选',
            'html_info' => $str,
            'show_price' => '200',
            'status' => '2',
            'is_available' => '1'
        ]);       
        DB::table('product_images')->insert([
            'id' => '5',
            'product_id' => '5',
            'image' => '/assets/img/goods-5.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 6
        DB::table('products')->insert([
            'id' => '6',
            'merchant_id' => "2",
            'shop_category_id' => '3',
            'name' => '精美沐浴套盒1v4',
            'sn' => '0',
            'info' => '奢华生活',
            'html_info' => $str,
            'show_price' => '220',
            'status' => '2',
            'is_available' => '1'
        ]);        
        DB::table('product_images')->insert([
            'id' => '6',
            'product_id' => '6',
            'image' => '/assets/img/goods-6.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 7
        DB::table('products')->insert([
            'id' => '7',
            'merchant_id' => "2",
            'shop_category_id' => '3',
            'name' => '艺术LED台灯',
            'sn' => '0',
            'info' => '温馨的夜晚',
            'html_info' => $str,
            'show_price' => '230',
            'status' => '2',
            'is_available' => '1'
        ]);        
        DB::table('product_images')->insert([
            'id' => '7',
            'product_id' => '7',
            'image' => '/assets/img/goods-7.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 8
        DB::table('products')->insert([
            'id' => '8',
            'merchant_id' => "2",
            'shop_category_id' => '3',
            'name' => '情侣工艺桌伞',
            'sn' => '0',
            'info' => '情侣工艺品',
            'html_info' => $str,
            'show_price' => '250',
            'status' => '2',
            'is_available' => '1'
        ]);        
        DB::table('product_images')->insert([
            'id' => '8',
            'product_id' => '8',
            'image' => '/assets/img/goods-8.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 9
        DB::table('products')->insert([
            'id' => '9',
            'merchant_id' => "2",
            'shop_category_id' => '3',
            'name' => '进口葡萄酒杯',
            'sn' => '0',
            'info' => '精美酒杯',
            'html_info' => $str,
            'show_price' => '260',
            'status' => '2',
            'is_available' => '1'
        ]);       
        DB::table('product_images')->insert([
            'id' => '9',
            'product_id' => '9',
            'image' => '/assets/img/goods-9.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 10
        DB::table('products')->insert([
            'id' => '10',
            'merchant_id' => "2",
            'shop_category_id' => '3',
            'name' => '精美香熏蜡烛',
            'sn' => '0',
            'info' => '点亮黑暗',
            'html_info' => $str,
            'show_price' => '280',
            'status' => '2',
            'is_available' => '1'
        ]);       
        DB::table('product_images')->insert([
            'id' => '10',
            'product_id' => '10',
            'image' => '/assets/img/goods-10.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
        //product 11
        DB::table('products')->insert([
            'id' => '11',
            'merchant_id' => "2",
            'shop_category_id' => '3',
            'name' => '彩色气球',
            'sn' => '0',
            'info' => '气球',
            'html_info' => $str,
            'show_price' => '300',
            'status' => '2',
            'is_available' => '1'
        ]);       
        DB::table('product_images')->insert([
            'id' => '11',
            'product_id' => '11',
            'image' => '/assets/img/goods-11.jpg',
            'pos' => '0',
            'is_first' => '1',
            'device' => '1'
        ]);
    }
}