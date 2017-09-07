<?php

namespace App\Models\NodeValueTypes;


use DB;
use Exception;

/**
 * Description of String
 *
 * @author root
 */
class Article extends Base 
{
  
     /**
	* Constructor. Attempts to load existing or create new node
	* @param mixed $param existing node ID or string of node type
	*/
    public function __construct() 
    {

    }
    
    public function load($param)
    {
        $content = DB::table('contents')
            ->select(['id', 'content_category_id', 'subtitle', 'title', 'description', 'image', 'image_4_3','seo_keywords'])
            ->where('id', '=', $param)
            ->first();
        
        if ($content) {
            $this->value = $content;
        } else {
            throw new Exception("Could not found content by Id: $param");
        }    
    }
  
    public function getValue() 
    {      
        return $this->value;
    }
}
