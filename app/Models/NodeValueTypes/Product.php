<?php

namespace App\Models\NodeValueTypes;

use Exception;

/**
 * Description of String
 *
 * @author jiajun
 */
class Product extends Base 
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
        $product = \App\Models\Shop\Product::where('id', '=', $param)->select('id','shop_category_id','name',
                    'sn','info','area','package','show_price','show_min_price',
                    'show_max_price','status','is_available','created_at','updated_at')
                    ->with('images')->first(); 
               
        if ($product) {
            $this->value = $product;
        } else {
            throw new Exception("Could not found product by Id: $param");
        }    
    }
  
    public function getValue() 
    {      
        return $this->value;
    }
}
