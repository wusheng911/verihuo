<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\NodeAttributeType;
use DB;
use File;
use App\Models\NodeAttribute;

class NodeAttrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function listJson(Request $request){
    	$node_type_id = (int) $request->node_type_id;
    	$nodeAttrTypes = DB::table('node_attr_type')->join('node_type_metadata', 'node_type_metadata.node_attr_id', '=', 'node_attr_type.id')
    	->where('node_type_metadata.node_type_id',$node_type_id)->get();
    	
    	//$nodeAttrTypes = DB::table('node_attr_type')->where()
    	
    	
    	//$nodeAttrTypes = NodeAttributeType::all();
    	 return response()->json($nodeAttrTypes);
    }
 	public function saveAttrJson(Request $request){   	
    	
 		$nodes = $request->nodes;
 		$nodeAttrs = $request->nodeAttrs;
 		$seq_nos = $request->seq_nos;
 		$values = $request->values;
 		foreach ($nodes as $key => $value){
 			$nodeId = $value;
 			$attrAttrTypeId = $nodeAttrs[$key];
 			$val = $values[$key];
 			$attrTypeItem = NodeAttributeType::find($attrAttrTypeId);
 			if($attrTypeItem->value_type == 'filepath'){
 				$tmpval = $this->saveCoverImage($val, $nodeId);
 				if(!empty($tmpval)){
 					$val = $tmpval;
 				}
 			}
 			if(NodeAttribute::hasNodeAttr($nodeId, $attrAttrTypeId,$seq_nos[$key])){
 				NodeAttribute::updateNodeAttr($nodeId, $attrAttrTypeId,$seq_nos[$key], $val);
 			}else{
 				NodeAttribute::createNodeAttr($nodeId, $attrAttrTypeId,$seq_nos[$key], $val);
 			}
 		}
 		
    	 return response()->json($request);
    }
    
 protected function saveCoverImage($imagePath, $id){
        $keys = ['image'];
            foreach($keys as $key){
                if(!empty($imagePath)){
                    $path = urldecode($imagePath);
                    if(strpos($path, '/files/nodes/') !== false){
                        continue;
                    }
                     
                    $ext = File::extension($path);
                    $name = File::name($path);
                    $newName = 'attr_'.$id.'_'.md5($name).date('_Ymd_His').'.'.$ext;
                    $dir = '/files/nodes/'.$id;
                    $imagePath = $dir.'/'.$newName;
                    if(!File::exists(public_path(ltrim($dir, '/')))){
                        File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
                    }
                  // delete old image
                    $match_files = File::glob(public_path('files/nodes/'.$id.'/attr_*'));
                    if($match_files !== false) {
                        foreach($match_files as $file) {
                            if(file_exists($file))
                                File::delete($file);
                        }
                    }
                    File::copy(public_path(ltrim($path,'/')), public_path(ltrim($imagePath,'/')));
                  //Content::where(['id'=>$id])->update([$key=>$content[$key]]);
                  return $imagePath;
                }
            }
    }
}
