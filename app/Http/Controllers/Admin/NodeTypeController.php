<?php

namespace App\Http\Controllers\Admin;

use App\Models\NodeAttributeType;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Redirect;

use App\Models\NodeType;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class NodeTypeController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.nodetype.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.nodetype.edit');
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
        $nodeType = $request->input('nodeType');
        DB::table('node_type')->insert($nodeType);
        return redirect()->action('Admin\NodeTypeController@index');
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
        	$nodeType = NodeType::find($id);
    		$nodeAttrTypes = NodeAttributeType::all();
            $allNodeAttrTypes = array();
    		$haveAttrs =  DB::table('node_type_metadata')->where('node_type_id',$id)->get();
    		$hasAttrArr = array();
    		foreach ($haveAttrs as $key=>$value){
    			$hasAttrArr[] = $value->node_attr_id;
    		}
    		$addAttrArr = array();
    		foreach ($nodeAttrTypes as $key=>$value){
    		    $allNodeAttrTypes[$value->id] = array('id'=>$value->id,'name'=>$value->display_label,'has'=>true);
    			if(!in_array($value->id, $hasAttrArr)){
    				$addAttrArr[] = $value;
                    $allNodeAttrTypes[$value->id]['has']=false;
    			}
    		}
         return view('admin.nodetype.edit',['nodeType'=>$nodeType,'addAttrs'=>$addAttrArr,'allAttrs'=>$allNodeAttrTypes]);
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
        NodeType::where('id', (int) $id)->update($request->input('nodeType'));
        return Redirect::to(action('Admin\NodeTypeController@index'));
        
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
        $nodeAttrType = NodeType::find($id);
    	$nodeAttrType->delete();
        return response()->json('删除成功');
    }
	public function listJson(Request $request){
    	$draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        
      //    Log::info('draw'.$draw.' '.$start.' '.$length);

    	 $types = NodeType::all();
        $count = count($types);
       // Log::info('count::'.$count);
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $types];

       // Log::info(response()->json($json));
        return response()->json($json);
    }
    public function setNodeTypeAttr(Request $request){
        $nodeTypeID = $request->nodeTypeID;
        $nodeAttrTypeIds = $request->ids;
        if(!empty($nodeTypeID) && !empty($nodeAttrTypeIds)){
            DB::table('node_type_metadata')->where('node_type_id',$nodeTypeID)->delete();
            $maxId = 1;
            foreach ($nodeAttrTypeIds as $key=>$value){
                $data = array();
                $data['node_type_id'] = $nodeTypeID;
                $data['seq_no'] = $maxId;
                $data['node_attr_id'] = $value;
                $data['cardinality'] = 'one';
                $data['editable'] = 'default';
                $time = date('Y-m-d H:i:s');
                $data['created_at'] = $time;
                $data['updated_at'] = $time;
                $nt = DB::table('node_type_metadata')->insert($data);
                $maxId++;
            }
        }

        return response()->json('重置成功');
    }
}
