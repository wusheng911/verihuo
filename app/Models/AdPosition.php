<?php

namespace App\Models;

use DB;
use App\Models\Node;
use App\Models\NodeType;
use Exception;
use App\Models\AdPositionBase;

class AdPosition
{
    /**
     * The name of table.
     * 
     * @var string
     */
    protected $table = 'adpositions';
    
    const table_str = 'adpositions';
    
    public $id;
    
    public $adposition_code;
    
    public $name;
    
    public $nodeType;
    
    public $description;
    
    public $nodes = array();
    
    
    public function __construct($param = null) 
    {
        if ($param) {
            
            $adposition = null;
          
            if (intval($param) > 0) {
                $adposition = DB::table(self::table_str)->where('id', $param)->first();
            } elseif (is_string($param)) {
                $adposition = DB::table(self::table_str)->where('adposition_code', $param)->first();
            }
        
            if ($adposition) {
                $this->id = $adposition->id;
                $this->adposition_code = $adposition->adposition_code;
                $this->name = $adposition->name;
                $this->description = $adposition->description;
                $this->nodeType = NodeType::find($adposition->node_type_id);
                
                $adposition_node = DB::table('adposition_node')->where('adposition_id', $adposition->id)->first();
                if ($adposition_node) {
                    array_push($this->nodes, new Node($adposition_node->node_id));
                }
                
            } else {
                throw new Exception("Adposition ID/Name $param not found.");
            }            
        } 
    }    
    
    
    /**
     * Get related nodes by partial string of adposition_code
     */
    public static function getAdPositions($adCode) 
    {
        $adpositions = array();
      
        $ads = DB::table(self::table_str)->where('adposition_code', 'like', "$adCode%")->get();
        
        foreach($ads as $ad) {
          array_push($adpositions, new AdPosition($ad->id));
        }
        return $adpositions;
    }
    public static function getAll($sort='asc'){
    	if($sort=='asc'){
    		$rt = DB::table(self::table_str)->where('id','>',0)->get();
    	}else{
    		$rt = DB::table(self::table_str)->where('id','>',0)->orderBy('id','desc')->get();
    	}
    	
    	return $rt;
    }
    public static function addAdPosition($args){
    	if(!empty($args)){
    		$ad = DB::table(self::table_str)->insert($args);
    		return $ad;
    	}
    }
    public static function getAdPositionByName($name){
    	$adposition = DB::table(self::table_str)->where('name', $name)->first();
    	return $adposition;
    }
    public static function getAdPositionById($id){
    	$adposition = DB::table(self::table_str)->where('id', $id)->first();
    	return $adposition;
    }
    public static function setAdPositionAttr($id,$args){
    	
    	if(gettype($args)=='object'){
    		$tmpAd = array();
    		foreach ($args as $key=>$value){
    			$tmpAd[$key] = $value;
    		}
    		$args = $tmpAd;
    	}
    	DB::table(self::table_str)->where('id', $id)->update($args);
    	
    	return self::getAdPositionById($id);
    }
    public static function deleteAdPositionById($id){
    	
    	 if (intval($id) > 0) {
    	   	$adposition = DB::table(self::table_str)->where('id', $id)->delete();
    		$adposition_node =  DB::table('adposition_node')->where('adposition_id',$id)->delete();
    	 }else{
                throw new Exception("Adposition ID $param not found.");
         } 
    }
}
