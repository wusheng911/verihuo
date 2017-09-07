<?php

namespace App\Models;

use Illuminate\Support\Collection;
use App\Models\NodeAttributeType;
use DB;
use Exception;
use Log;

class NodeAttribute 
{
    /**
     * The name of table.
     * 
     * @var string
     */
    protected $table = 'node_attr';
    
    const table_str = 'node_attr';
    
    public $nodeId;
   
    public $nodeAttrId;
    
    public $nodeAttrName;
    
    public $values = array();
    
    /**
     * Hode the node attribute type definition (node_attr_type) of the node_attr record
     * @var type 
     */
    protected $type;
    
    /**
     * Holds general attribute information
     * 
     * @var type 
     */
    protected static $nodeAttrTypes = null;
    
    protected $cache = null;
    
    public function __construct($nodeId = null, $nodeAttrId = null, array $cacheData)
    { 
        $this->cache = $cacheData; //take any pre-loaded data
      
        if (!isset(self::$nodeAttrTypes)) {
            self::$nodeAttrTypes = NodeAttributeType::all();
        }
                
        if ($nodeAttrId) {
            $this->nodeAttrId = intval($nodeAttrId);
            
            //$this->type = self::$nodeAttrTypes->where('id', $nodeAttrId)->first(); 
            //use foreach instead of above line to avoid php7 bug
            foreach(self::$nodeAttrTypes as $node_attr_type) {
                if ($node_attr_type->id == $nodeAttrId) {
                    $this->type = $node_attr_type;
                }
            }
            
            if ($this->type instanceof NodeAttributeType) {          
                $this->nodeAttrName = $this->type->name;
            } else {
                throw new Exception("Node Attribute Type with Id $nodeAttrId not found.");
            }
        }
        
        if ($nodeId) {
            $this->nodeId = intval($nodeId);
            $this->load();
        }
    }
    
    protected function load() 
    {
		$this->values = array ();
        
		if (!isset ($this->cache)) {
			//if cache not pre-populated, get all attrs for this entity (same format cache'd have if pre-populated)
			// cache is good but when user click save button, this will have to update mambo search table, if we use cached data, the data is out of date
			// so we have to retrieve the latest data from the table.
			$resultset = DB::table($this->table)
                ->where('node_id', $this->nodeId)
                ->where('node_attr_id', $this->nodeAttrId)
                ->orderBy('seq_no')
                ->get();
              
			if ($resultset) {
				$this->cache = $resultset;
			}
		}
        
        
        if (isset($this->cache) && is_array($this->cache)) {
            //为了兼容在一个值中使用逗号分割模拟多值的情况，如: 1,2,3,4
            if (count($this->cache) == 1) {
                $data = array_shift($this->cache);
                if (stripos($data->value, "|") !== false) {
                    $values = explode("|", $data->value);
                    
                    $i=0;
                    foreach ($values as $temp_value) {
                        if ($data->node_attr_id == $this->nodeAttrId) {
                            $value_type = NodeValueTypes\Base::factory($this->type->value_type);
                            try {
                                $value_type->load($temp_value); 
                                
                                $this->values[$i++] = [
                                    'value' => $value_type->getValue(),
                                    'created_at' => $data->created_at,
                                    'updated_at' => $data->updated_at
                                ];
                            } catch (Exception $ex) {
                                Log::error("NodeAttribute::load() error: value_type: {$this->type->value_type}, value: {$temp_value}" . $ex->getMessage());
                                continue;
                            }
                        }                         
                    }                    
                } else {
                    if ($data->node_attr_id == $this->nodeAttrId) {
                        $value_type = NodeValueTypes\Base::factory($this->type->value_type);
                        try {
                            $value_type->load($data->value);
                            
                            $this->values[$data->seq_no] = [
                                'value' => $value_type->getValue(),
                                'created_at' => $data->created_at,
                                'updated_at' => $data->updated_at
                            ];
                        } catch (Exception $ex) {
                            Log::error("NodeAttribute::load() error: value_type: {$this->type->value_type}, value: {$temp_value}" . $ex->getMessage());
                        }

                    }                    
                }             
            } else {  //正常多值属性的情况，这样处理
                foreach($this->cache as $data) {
                    if ($data->node_attr_id == $this->nodeAttrId) {
                        $value_type = NodeValueTypes\Base::factory($this->type->value_type);
                        try {
                            $value_type->load($data->value);

                            $this->values[$data->seq_no] = [
                                'value' => $value_type->getValue(),
                                'created_at' => $data->created_at,
                                'updated_at' => $data->updated_at
                            ];
                        } catch (Exception $ex) {
                            Log::error("NodeAttribute::load() error: value_type: {$this->type->value_type}, value: {$temp_value}" . $ex->getMessage());
                            continue;
                        }
                    }
                } 
            }
        }
    }
   
    public static function getNodeAttrTypes() 
    {
        if (!isset(self::$nodeAttrTypes)) {
            self::$nodeAttrTypes = NodeAttributeType::all();
        }
        
        return self::$nodeAttrTypes;
    }
    public static function hasNodeAttr($nodeId,$nodeAttrId,$seqNoId=1){
    	$item  = DB::table(self::table_str)->where('node_id',$nodeId)->where('node_attr_id',$nodeAttrId)->where('seq_no',$seqNoId)->get();
    	
    	return !empty($item);
    }
    public static function getNextSeqNoId($nodeId,$nodeAttrId,$seqNoId=1){
    	$lists = DB::table(self::table_str)->where('node_id',$nodeId)->where('node_attr_id',$nodeAttrId)->get();
    	if(empty($lists)){
    		return 1;
    	}else{
    		$count = count($lists);
    	}
    	return $count++;
    	
    }
    public static function updateNodeAttr($nodeId,$nodeAttrId,$seqNoId,$value){
    	$updateDate = date('Y-m-d H:i:s');
    	$data = array('node_id'=>$nodeId,'node_attr_id'=>$nodeAttrId,'seq_no'=>$seqNoId,'value'=>$value,'updated_at'=>$updateDate);
    	DB::table(self::table_str)->where('node_id',$nodeId)->where('node_attr_id',$nodeAttrId)->where('seq_no',$seqNoId)->update($data);
    }
    public static function createNodeAttr($nodeId,$nodeAttrId,$seqNoId,$value){
    	if(empty($seqNoId)){
    		$seqNoId =1;
    	}
    	$createDate = date('Y-m-d H:i:s');
    	$data = array('node_id'=>$nodeId,'node_attr_id'=>$nodeAttrId,'seq_no'=>$seqNoId,'value'=>$value,'created_at'=>$createDate,'updated_at'=>$createDate);
    	$item  = DB::table(self::table_str)->insert($data);
    	return $item;
    }
    public static function addAttrForNode($args){
    	if(gettype($args)=='array'){
    		$node = Node::getNodeById($args['node_id']);
    		$nodeAttrType = NodeAttributeType::find($args['node_attr_id']);
    	}else{
    		$node = Node::getNodeById($args->node_id);
    		$nodeAttrType = NodeAttributeType::find($args->node_attr_id);
    	}
    	if(!empty($node) && !empty($nodeAttrType)){
    		$nodeAttr = DB::table(self::table_str)->insert($args);
    		return $nodeAttr;
    	}else{
    		throw new Exception("node_id or node_attr_id not found.");
    	}
    }
    public static function getNodeAllAttrs($nodeId){
    	
    	$rt = DB::table(self::table_str)->where('node_id', $nodeId)->get();
    	return $rt;
    	
    }
   	public static function deleteAttrForNode($nodeId){
   		DB::table(self::table_str)->where('node_id', $nodeId)->delete();
   	}
    
}
