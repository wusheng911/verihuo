<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Redirect;

use App\Models\NodeAttributeType;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class NodeAttrTypeController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.nodeattrtype.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.nodeattrtype.edit');
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
        $nodeAttrType = $request->input('nodeAttrType');
        DB::table('node_attr_type')->insert($nodeAttrType);
        return redirect()->action('Admin\NodeAttrTypeController@index');
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
        $nodeAttrType = NodeAttributeType::find($id);
         return view('admin.nodeattrtype.edit',['nodeAttrType'=>$nodeAttrType]);
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
        NodeAttributeType::where('id', (int) $id)->update($request->input('nodeAttrType'));
        return Redirect::to(action('Admin\NodeAttrTypeController@index'));
        
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
        $nodeAttrType = NodeAttributeType::find($id);
    	$nodeAttrType->delete();
        return response()->json('删除成功');
    }
	public function listJson(Request $request){
    	$draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        
      //    Log::info('draw'.$draw.' '.$start.' '.$length);

    	 $types = NodeAttributeType::all();
        $count = count($types);
       // Log::info('count::'.$count);
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $types];

       // Log::info(response()->json($json));
        return response()->json($json);
    }
}
