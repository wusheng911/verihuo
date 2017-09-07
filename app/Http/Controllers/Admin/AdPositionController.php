<?php

namespace App\Http\Controllers\Admin;

use SleepingOwl\Admin\Display\Column\Email;

use DB;
use Log;
use JavaScript;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\NodeAttributeType;
use App\Models\NodeType;
use App\Models\Node;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\NodeAttribute;
use App\Models\AdPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		//$this->destroy(4);
		//exit;
        $ads = AdPosition::getAll();
        return view('admin.adposition.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $nodeTypes = NodeType::all();
		$ads = AdPosition::getAll();
		$json = [
			'ads' => $ads,
		];
		JavaScript::put($json);
		return view('admin.adposition.edit',['nodeTypes'=>$nodeTypes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $ad = $request->ad;
        $data = array();
        $adposition_node = array();
        foreach ($ad as $key=>$value){
        	if($key !='node_id'){
        		$data[$key] = $value;
        	}else{
        		$adposition_node[$key]=$value;
        	}
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = $data['created_at'];
        $adp = DB::table('adpositions')->insert($data);
        $node = array('node_type_id' => $data['node_type_id'],'created_at'=>$data['created_at'],'updated_at'=>$data['created_at']);
        Node::addNode($node);
        $adposition_node['adposition_id'] =  DB::table('adpositions')->orderBy('created_at','desc')->first()->id;
        $adposition_node['node_id'] =  DB::table('nodes')->orderBy('created_at','desc')->first()->id;
		Log::info($adposition_node);
        $ad_node = DB::table('adposition_node')->insert($adposition_node);
        return redirect()->action('Admin\AdPositionController@edit',$adposition_node['adposition_id']);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
       $ad = AdPosition::getAdPositionById($id);
       $adposition_node = DB::table('adposition_node')->where('adposition_id',$id)->first();
       
       if(empty($adposition_node)){
       		
       		$adposition_node = array();
       		$time = date('Y-m-d H:i:s');
       		$node = array('node_type_id' => $ad->node_type_id,'created_at'=>$time,'updated_at'=>$time);
       		Node::addNode($node);
       		
       		$adposition_node['adposition_id'] =  $id;
	        $adposition_node['node_id'] =  DB::table('nodes')->orderBy('id','desc')->first()->id;
	        DB::table('adposition_node')->insert($adposition_node);
			$adposition_node =  DB::table('adposition_node')->where('adposition_id',$id)->first();   
       		
       	    // return redirect()->action('Admin\AdPositionController@index');
       }
       	$node = new Node($adposition_node->node_id);
       	$nodeAttrs = NodeAttribute::getNodeAllAttrs((int) $adposition_node->node_id);
       	foreach ($nodeAttrs as $key => $value){
       		$node_attr_type = NodeAttributeType::find($value->node_attr_id);
       		$value->node_attr_id = $node_attr_type;
       	}
       	$node_type = NodeType::find($ad->node_type_id);
       	$nodeAttrTypes = NodeAttributeType::all();
        return view('admin.adposition.edit',['ad'=> $ad,'node'=>$node,'nodeAttrs'=>$nodeAttrs,'nodeAttrTypes'=>$nodeAttrTypes,'nodeType'=>$node_type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    $ad = $request->ad;
        $data = array();
        $adposition_node = array();
        foreach ($ad as $key=>$value){
        	if($key !='node_id'){
        		$data[$key] = $value;
        	}else{
        		$adposition_node[$key]=$value;
        	}
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('adpositions')->where('id', (int) $id)->update($data);
        
        $adposition_node['adposition_id'] = $id;
        $ad_node = DB::table('adposition_node')->where('adposition_id',(int) $id)->update($adposition_node);
        
        
        return redirect()->action('Admin\AdPositionController@index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$ad = AdPosition::getAdPositionById($id);
    	$ad_node= DB::table('adposition_node')->where('adposition_id',$id)->first();
    		$node = Node::getNodeById($ad_node->node_id);
    		DB::table('adposition_node')->where('adposition_id',$id)->delete();
    		NodeAttribute::deleteAttrForNode($node->id);
    		Node::deleteNodeById($node->id);
    		AdPosition::deleteAdPositionById($id);
        	return response()->json('删除节点'.$ad->name.'成功');
    }
    
    public function listJson(Request $request){
    	$draw = (int) $request->input('draw');
		Log::info($draw);
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $sid = (int) $request->input('sid');
        $scode = $request->input('scode');
        $sname = $request->input('sname');
        $sdescript = $request->input('sdescript');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $ads = DB::table('adpositions')->where('name', 'LIKE', "%$skey%")->get();
        }elseif(!empty($sid)){
        	$ads = DB::table('adpositions')->where('id',$sid)->get();
        }elseif(!(empty($sname))){
        	$ads = DB::table('adpositions')->where('name', 'LIKE', "%$sname%")->get();
        }elseif(!empty($scode)){
        	$ads = DB::table('adpositions')->where('adposition_code', 'LIKE', "%$scode%")->get();
        }elseif(!empty($sdescript)){
        	$ads = DB::table('adpositions')->where('description', 'LIKE', "%$sdescript%")->get();
        }else{
            $ads = AdPosition::getAll('desc');
        }
        
        $count = count($ads);
       // Log::info('count::'.$count);
       $ads = array_splice($ads,$start,$length);

        foreach ($ads as $key=>$value){
            $nodeType = NodeType::find($value->node_type_id);
            if(!empty($nodeType)){
                //将节点类型ID换位节点类型名称,用于广告位节点名称显示
                $ads[$key]->node_type_id = $nodeType->name;
            }
        }
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $ads];

       // Log::info(response()->json($json));
        return response()->json($json);
    }
    
    //createType为1时是PC端，0为移动端
    public function fillAdpositionForCategory(Request $request){
    	$path = 'config/pc_node_type_list.xml';
    	$createType = $request->type;
    	if(file_exists($path)){
			$xml_arr = simplexml_load_file($path);
			if($createType == 1){
				$tads = $xml_arr->PCADFORCATEGORY;
			}else{
				$tads = $xml_arr->MOBILEADFORCATEGORY;
			}
    		$allCategory = ContentCategory::all();
    		foreach ($allCategory as $category){
    			$tads = $tads[0];
    			foreach ($tads as $tad){
    				$tad = $tad;
    				if($createType==1){
    					$adcode = $tad->DEVICE.'|'.$tad->TYPE.'|'.$category->id.'|'.$tad->ADCODE;
    				}else{
    					$pid = $category->pid;
    					$pcategory = ContentCategory::find($pid);
    					if(empty($pcategory)){
    						continue;
    					}
    					$adcode = $tad->DEVICE.'|'.$tad->TYPE.'|'.$pcategory->id.'-'.$category->id.'|'.$tad->ADCODE;
    				}
    				$ads = DB::table('adpositions')->where('adposition_code', $adcode)->first();
    				if(empty($ads)){
    					$nodeType = DB::table('node_type')->where('template',$tad->TEMPLATE)->first();
    					if(!empty($nodeType)){
    						$nodeTypeId = $nodeType->id;
    						$data = array();
    						if($createType==1){
    							$data['name'] = $tad->NAMELEFT.'|'.$category->title.'|'.$tad->NAMERIGHT;
    						}else{
    							$data['name'] = $tad->NAMELEFT.'|'.$pcategory->title.'-'.$category->title.'|'.$tad->NAMERIGHT;
    						}
    						$data['adposition_code'] = $adcode;
    						$data['created_at'] = date('Y-m-d H:i:s');
		        			$data['updated_at'] = $data['created_at'];
		        			$data['node_type_id'] = $nodeTypeId;
		        			$adp = DB::table('adpositions')->insert($data);
		        			$node = array('node_type_id' => $data['node_type_id'],'created_at'=>$data['created_at'],'updated_at'=>$data['created_at']);
					        Node::addNode($node);
					        $adposition_node['adposition_id'] =  DB::table('adpositions')->orderBy('id','desc')->first()->id;
					        $adposition_node['node_id'] =  DB::table('nodes')->orderBy('id','desc')->first()->id;
					        $ad_node = DB::table('adposition_node')->insert($adposition_node);
    					}
    				}
    			}
    		}
    	}
    	return response()->json('添加成功');
    }
    		
    //重置广告位，type为0 则重置节点属性，不新建节点，否则就不重置节点属性，新建一个节点
    protected function resetNode(Request $request,$type=0){
    	$id = $request->id;
    	$ad = AdPosition::getAdPositionById($id);
    	if($type ==0){
    		$ad_node = DB::table('adposition_node')->where('adposition_id',$id)->first();
    		$nodeId = $ad_node->node_id;
    		NodeAttribute::deleteAttrForNode($nodeId);
    	}else{
	  		$adposition_node = array();
	       	$time = date('Y-m-d H:i:s');
	       	$node = array('node_type_id' => $ad->node_type_id,'created_at'=>$time,'updated_at'=>$time);
	       	Node::addNode($node);
	       	$adposition_node['adposition_id'] =  $id;
	        $adposition_node['node_id'] =  DB::table('nodes')->orderBy('id','desc')->first()->id;
	        DB::table('adposition_node')->insert($adposition_node);
    	}
    	
//    	

		//$adposition_node =  DB::table('adposition_node')->where('adposition_id',$id)->first(); 
    	return response()->json('重置成功');
    	
    }
    protected function editBase(Request $request){
       $ad = $request->input('ad');
       $id = $ad['id'];
       DB::table('adpositions')->where('id',$id)->update($ad);
       return redirect()->action('Admin\AdPositionController@index');
    }
    protected function saveNode(Request $request){
    	
    	$nodetype = $request->input('nodetype');
  		$id = (int) $nodetype['id'];
  		$time = date('Y-m-d H:i:s');
    	if($id){
    		$nodetype['updated_at'] = $time;
    		$nt = DB::table('node_type')->where('id',$id)->update($nodetype);
    	}else{
    		$nodetype['created_at'] = $time;
			$nodetype['updated_at'] = $time;
    		$nt = DB::table('node_type')->insert($nodetype);
    		$id = DB::table('node_type')->orderBy('updated_at','desc')->first()->id;
    	}
    	return redirect()->action('Admin\AdPositionController@createNode',$id);
    }
    public function addNodeTypeAttr(Request $request){
    	$nodeTypeID = $request->nodeTypeID;
    	$nodeAttrTypeID = $request->id;
    	$attrs = DB::table('node_type_metadata')->where('node_type_id',$nodeTypeID)->get();
    	$maxId = 0;
    	foreach ($attrs as $attr){
    		$seq = (int) $attr->seq_no;
     		if($seq >$maxId){
    			$maxId = $seq;
    		}
    	}
    	$maxId++;
    	$data = array();
    	$data['node_type_id'] = $nodeTypeID;
    	$data['seq_no'] = $maxId;
    	$data['node_attr_id'] = $nodeAttrTypeID;
    	$data['cardinality'] = 'one';
    	$data['editable'] = 'default';
    	$time = date('Y-m-d H:i:s');
    	$data['created_at'] = $time;
    	$data['updated_at'] = $time;
    	$nt = DB::table('node_type_metadata')->insert($data);
    	
    	return response()->json('重置成功');
    }
	protected function HomeKVOrder(Request $request){
			
		$order = (int) $request->input('order');
        $ads = AdPosition::getAdPositions("PC|Home|A");
		$ads = array_reverse($ads);
		$cnt = count($ads);
		$codes = array();
		$names = array();
		foreach($ads as $key => $value){
			$adCode = $value->adposition_code;		
			$adName = $value->name;
			$index =(int) substr($adCode,strlen($adCode)-1,strlen($adCode));
			if($order>0){
				if($index >= $cnt){
					$index = 1;	
				}else{
					$index++;
				}
			}else{
				if($index <=1){
					$index = $cnt;
				}else{
					$index--;
				}
			}
			$code = substr($adCode,0,strlen($adCode)-1).(string) $index;
			$name = substr($adName,0,strlen($adName)-1).(string) $index;
			array_push($codes,$code);
			array_push($names,$name);
			$args = array('adposition_code'=>'PC|Home|tmp'.$index,'name'=>$name);
			AdPosition::setAdPositionAttr($value->id,$args);
		}
		foreach($ads as $key => $value){
			$args = array('adposition_code'=>array_shift($codes),'name'=>array_shift($names));
			AdPosition::setAdPositionAttr($value->id,$args);
		}
    	return response()->json('重置成功');
	}
}
