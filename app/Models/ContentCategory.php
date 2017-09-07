<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentCategory extends Model
{
    //
    use SoftDeletes;
    protected $guarded = ['id'];
    
    /**
     * Get Category code "wo" "yao" "chao" "hun" by category_id
     * 
     * @param $id The category id
     * @param $isParent Specify whether it's a parent category or uncertain, 
     *                  in isParent mode it will not perform DB query to save time.
     * @return string "wo" "yao" "chao" "hun"
     */
    public static function getCategoryCodeById($id, $isParent = false) 
    {
        $code = '';
        $category_id = '';
        if ($isParent) {
            $category_id = $id;
        } else {
            $category = ContentCategory::find($id);
            if ($category) {
                $category_id = (intval($category->pid) > 0)?$category->pid:$category->id;
            }
        }
            
        switch ($category_id) {
            case config('chaohun.art_parent_category_1'):
                $code = 'wo';
                break;
            case config('chaohun.art_parent_category_2'):
                $code = 'yao';
                break;
            case config('chaohun.art_parent_category_3'):
                $code = 'chao';
                break;
            case config('chaohun.art_parent_category_4'):
                $code = 'hun';
                break;
            case config('chaohun.art_parent_category_5'):
                $code = 'queen';
                break;
        }
        
        return $code;
    }

    public function getParent(){
        $parent = $this->find($this->pid);
        if(!empty($parent->id) && ($parent->id != $this->id)){
            return $parent;
        }else{
            return null;
        }
    }

    public function getAncestors(){
        $ancestors = [];
        if($this->pid != 0){
            $parent = $this->getParent();
            while(true){
                if($parent){
                    $ancestors[] = $parent;
                    $parent = $parent->getParent();
                }else{
                    break;
                }
            }
        }
        return collect($ancestors);
    }

    public function tags(){
        return $this->belongsToMany('App\Models\ContentTag', 'content_category_tag');
    }
}
