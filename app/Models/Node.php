<?php

namespace App\Models;


use Exception;
use DB;
use App\Models\NodeType;
use App\Models\NodeAttribute;
use Log;

class Node
{
    /**
     * The name of table.
     * 
     * @var string
     */
    protected $table = 'nodes';
    
    const table_str = 'nodes';
    
    public $nodeId;
    
    public $nodeTypeId;
    
    public $nodeTypeName;
    
    public $template;
    
    public $localAttributes = array();
    
    protected $cache = null;
    
    /**
	* Constructor. Attempts to load existing or create new node
	* @param mixed $param existing node ID or string of node type
	*/
    public function __construct($param = null) {
        if (intval($param) > 0) {
            $node = DB::table('nodes')->where('id', $param)->first();
                        
            if ($node) {            
                $this->load($node->id, $node->node_type_id);
            } else {
              throw new Exception("Load node failed, Node with ID $param not found.");
            }
        } elseif (is_string($param)) {
            $node_type = NodeType::where('name', $param)->first();
            
            if ($node_type) {
                $this->nodeTypeName = $node_type->name;
                $this->nodeTypeId = $node_type->id;
                $this->template = $node_type->template;
            } else {
              throw new Exception("Create node failed, invalid node type $param");
            }
          
        } else {
            throw new Exception("Create node failed, unexpected parameter $param");
        }
    }
    
    
    protected function load($nodeId, $nodeTypeId) {      
        $this->nodeId = $nodeId;
        $this->nodeTypeId = $nodeTypeId;
                
        $node_type = NodeType::find($nodeTypeId);
                
        $this->nodeTypeName = $node_type->name;
        $this->template = $node_type->template;     
        
        $node_attrs = DB::table('node_attr')
            ->where('node_id', $nodeId)
            ->orderBy('node_attr_id')
            ->orderBy('seq_no')
            ->get();
                
        if ($node_attrs) {
            $this->cache = $node_attrs;
                      
            $attr_ids = array();
            foreach($node_attrs as $node_attr) {
                array_push($attr_ids, $node_attr->node_attr_id);
            }
            
            $attr_ids = array_unique($attr_ids);                        
            foreach($attr_ids as $attr_id) {
                try {
                    $node_attribute = new NodeAttribute($nodeId, $attr_id, $this->cache);
                    $this->setAttribute($node_attribute);
                } catch (Exception $ex) {
                    Log::error("Node::load() error: nodeId: $nodeId, attr_id: $attr_id" . $ex->getMessage());
                    continue;
                }                
            }    
        }
    }
    
    /**
	* Sets an attribute, indexed by attribute type name, overwriting any existing attribute
	* @param an NodeAttribute object
	*/
	public function setAttribute(NodeAttribute $attr) {
      if ($attr) {
		$this->localAttributes[$attr->nodeAttrName] = $attr;
      }
	}
    
    public function getAttributes() {
		return $this->localAttributes;
	}
    
    /***
     * Convert an array of adpositions to an list of nodes with 'adposition_code' as key
     */
    public static function adPositionsToNodes(array $adpositions) {
        $nodes = array();
        
        foreach($adpositions as $adposition) {
            $nodes[$adposition['adposition_code']] = array_shift($adposition['nodes']);
        }
        
        return $nodes;
    }
    
    public static function addNode($args){
    	if(!empty($args)){
    		$node = DB::table(self::table_str)->insert($args);
    		return $node;
    	}
    }
    public static function deleteNodeById($id){
    	if (intval($id) > 0) {
    	   	$node = DB::table(self::table_str)->where('id', $id)->delete();
    	 }else{
                throw new Exception("Adposition ID $param not found.");
         } 
    }
	public static function getNodeByName($name){
    	$node = DB::table(self::table_str)->where('name', $name)->first();
    	return $node;
    }
    public static function getNodeById($id){
    	$node = DB::table(self::table_str)->where('id', $id)->first();
    	return $node;
    }
	public static function setNodeAttr($id,$args){
    	
    	if(gettype($args)=='object'){
    		$tmpAd = array();
    		foreach ($args as $key=>$value){
    			$tmpAd[$key] = $value;
    		}
    		$args = $tmpAd;
    	}
    	DB::table(self::table_str)->where('id', $id)->update($args);
    	
    	return $this->getAdPositionById($id);
    }
}
