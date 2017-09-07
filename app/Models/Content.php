<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ContentTag;
use App\Models\ContentCategory;

class Content extends Model
{
    //
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    
    /**
     * Get an array of contents by category id
     * 
     * @param string $categoryId content_category_id
     * @param string $orderByField the field to order by
     * 
     * @return collection
     */
    public static function getContentsByCategory($categoryId, $limit = 25, $offset = 0, $orderByField = 'post_at', $order = 'desc') 
    {      
        //check if the categoryId is category or subcategory
        $subcategory_ids = ContentCategory::where('pid', '=', $categoryId)->get()->lists('id');
      
        if (!$subcategory_ids) {
            $subcategory_ids = [$categoryId];
        }
              
        $contents = DB::table('contents')
            ->select(['id', 'content_category_id', 'subtitle', 'title', 'description', 'image', 'image_4_3'])
            ->wherein('content_category_id', $subcategory_ids)
            ->orderBy($orderByField, $order)
            ->skip($offset)
            ->take($limit)
            ->get();
        
        return $contents;
    }

    public function syncTags() {
        $tags = ContentTag::all();
        $inTags = [];
        foreach ($tags as $tag) {
            if (strpos($this->description, $tag->name)  !== false) {
                $inTags[] = $tag->id;
            }
        }
        $this->tags()->sync($inTags);
    }

    public function comments(){
        return $this->hasMany('App\Models\ContentComment');
    }

    public function contentCategory(){
        return $this->belongsTo('App\Models\ContentCategory');
    }
    
    public function tags(){
        return $this->belongsToMany('App\Models\ContentTag');
    }
}
