<?php

namespace App\Models\NodeValueTypes;

/**
 * Description of Base
 *
 * @author root
 */
Class Base 
{
    protected $value;
  
    public function __construct() 
    {
    }
    
    public function load($param) 
    {
        $this->value = $param;
    }
    
    /**
     * Return values in array mode.
     */
    public function getValue() 
    {
        return $this->value;
    }
    
    public static function factory($valueType) 
    {
        if (class_exists('\\App\\Models\\NodeValueTypes\\' . ucfirst($valueType))) {          
            $full_value_type = '\\App\\Models\\NodeValueTypes\\' . ucfirst($valueType);
            return new $full_value_type();
        } else {
            return new Base();
        }
    }
}
