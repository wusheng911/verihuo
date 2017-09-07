<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Request;
use DB;
use App\Services\ApiResponse;
use App\Models\Node;
use Exception;


class NodeController extends Controller
{
    /**
     * Display a listing of the resource. Additional parameter "ids" can be used to filter resources, for example:
     * http://localhost/api/nodes?ids=1,2,3
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ids = array();
        $nodes = array();
        
        try {
            if (Request::has('ids')) {                
                $ids = explode(',', Request::input('ids'));
            }
            
            if (count($ids) == 0) {
              $ids = DB::table('nodes')->lists('id');
            }
                       
            foreach($ids as $id) {
                $nodes[intval($id)] = new Node($id);
            }   
                        
            $response = ApiResponse::get(false, $nodes); 
            
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
  
        return $response;
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
        try {
            $node = new Node($id);            
            $response = ApiResponse::get(false, [$node]);           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
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
}
