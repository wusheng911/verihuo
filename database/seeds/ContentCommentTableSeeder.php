<?php

use Illuminate\Database\Seeder;

class ContentCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_comments')->truncate();
              
        DB::table('content_comments')->insert([
            'id' => '1',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '很好的文章',
            'votes' => '3',
            'created_at' => Carbon\Carbon::now()->subMinute(180),
            'updated_at' => Carbon\Carbon::now()->subMinute(180)
        ]);
        DB::table('content_comments')->insert([
            'id' => '2',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '赞赞赞',
            'votes' => '3',
            'created_at' => Carbon\Carbon::now()->subMinute(160),
            'updated_at' => Carbon\Carbon::now()->subMinute(160)
        ]);
        DB::table('content_comments')->insert([
            'id' => '3',
            'pid' => '1',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '回复就哦分阶段哦啊附近哦发动机奥反对, 积分大家哦放大镜哦发, ' .
                      '附近哦打飞机哦阿飞的, 就哦发动机哦阿飞觉得哦啊, 就哦分阶段哦阿' .
                      '飞觉得哦阿飞. 就哦分阶段哦阿飞就哦的 附近哦发动机哦阿飞的',
            'votes' => '32',
            'created_at' => Carbon\Carbon::now()->subMinute(130),
            'updated_at' => Carbon\Carbon::now()->subMinute(130)
        ]);
        DB::table('content_comments')->insert([
            'id' => '4',
            'pid' => '3',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '回复就哦分阶段哦啊附近哦发动机奥反对, 积分大家哦放大镜哦发, ' .
                      '附近哦打飞机哦阿飞的, 就哦发动机哦阿飞觉得哦啊, 就哦分阶段哦阿' .
                      '飞觉得哦阿飞. 就哦分阶段哦阿飞就哦的 附近哦发动机哦阿飞的',
            'votes' => '30',
            'created_at' => Carbon\Carbon::now()->subMinute(120),
            'updated_at' => Carbon\Carbon::now()->subMinute(120)
        ]);
        DB::table('content_comments')->insert([
            'id' => '5',
            'pid' => '4',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '回复就哦分阶段哦啊附近哦发动机奥反对, 积分大家哦放大镜哦发, ' .
                      '附近哦打飞机哦阿飞的, 就哦发动机哦阿飞觉得哦啊, 就哦分阶段哦阿' .
                      '飞觉得哦阿飞. 就哦分阶段哦阿飞就哦的 附近哦发动机哦阿飞的',
            'votes' => '31',
            'created_at' => Carbon\Carbon::now()->subMinute(100),
            'updated_at' => Carbon\Carbon::now()->subMinute(100)
        ]);
        DB::table('content_comments')->insert([
            'id' => '6',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '回复就哦分阶段哦啊附近哦发动机奥反对, 积分大家哦放大镜哦发',
            'votes' => '31',
            'created_at' => Carbon\Carbon::now()->subMinute(90),
            'updated_at' => Carbon\Carbon::now()->subMinute(90)
        ]);
        DB::table('content_comments')->insert([
            'id' => '7',
            'user_id' => '1',
            'content_id' => '2',
            'body' => '回复就哦分阶段哦啊附近哦发动机奥反对',
            'votes' => '31',
            'created_at' => Carbon\Carbon::now()->subMinute(80),
            'updated_at' => Carbon\Carbon::now()->subMinute(80)
        ]);
    }
}
