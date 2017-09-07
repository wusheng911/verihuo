<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class ContentTag extends Model
{

    /*
     * 查找标签所在标签组的所有标签
     *
     * @param string $s_tag 搜索的标签
     *
     * return array
     */
    public static function getGroupTags($s_tag){
        $addTagName = [];
        $group_id = DB::table('content_category_tag')
                            ->leftJoin('content_tags','content_tags.id','=','content_category_tag.content_tag_id')
                            ->where('content_tags.name','=',$s_tag)
                            ->lists('content_category_id');
        if(empty($group_id)) {
            $tagNames = DB::table('content_tags')
                                ->where('content_tags.name','!=',$s_tag)
                                ->lists('name','id');
        } else {
            $tagName = DB::table('content_tags')
                                ->leftJoin('content_category_tag','content_tags.id','=','content_category_tag.content_tag_id')
                                ->whereIn('content_category_tag.content_category_id',$group_id)
                                ->where('content_tags.name','!=',$s_tag)
                                ->lists('name','id');
            if(count($tagName)<10) {
                $addTagName = DB::table('content_tags')
                    ->leftJoin('content_category_tag','content_tags.id','=','content_category_tag.content_tag_id')
                    ->whereNotIn('content_category_tag.content_category_id',$group_id)
                    ->where('content_tags.name','!=',$s_tag)
                    ->lists('name','id');
            }
            $tagNames = $tagName+$addTagName;
        }
        $tagNames = array_slice(array_unique($tagNames),0,8,true);//最多显示8个相关的关键字
        return $tagNames;
    }

    /*
     * 查找所有包含标签的文章
     *
     * @param string $s_tag 搜索的标签
     *
     * return collection
     */
    public static function getArticles($s_tag){
        
        $content_id = DB::table('content_content_tag')
                                ->leftJoin('content_tags','content_content_tag.content_tag_id','=','content_tags.id')
                                ->where('content_tags.name','=',$s_tag)
                                ->lists('content_id');

        if(empty($content_id)) {
            $content = DB::table('contents')
                                ->where('content','like',"%$s_tag%")
                                ->where('type',ContentType::Article)
                                ->whereNull('deleted_at')
                                ->orderBy('post_at', 'desc')
                                ->paginate(14);
            if($content->total() == 0) {
                //当没有关键字搜索结果时，则显示全部文章
                $content = DB::table('contents')
                                ->where('type',ContentType::Article)
                                ->whereNull('deleted_at')
                                ->orderBy('post_at', 'desc')
                                ->paginate(14);
                $content['search_total'] = 0;
            }
        } else {
            $content = DB::table('contents')
                                ->whereIn('id',$content_id)
                                ->where('type',ContentType::Article)
                                ->whereNull('deleted_at')
                                ->orderBy('post_at', 'desc')
                                ->paginate(14);
        }
        return $content;
    }
}