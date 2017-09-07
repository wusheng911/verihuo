<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use DB;
use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\AdPosition;
use Exception;

class AdPositionController extends Controller
{
    /**
     * Display a listing of the resource. Additional parameter "namelike" can be used to filter resources, for example:
     * http://localhost/api/adpositions?adcode=Mobile|Home|
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adcode = null;
        $allow_empty = false;
        $adpositions = array();
        
        //try {
            if ($request->has('adcode')) {                
                $adcode = $request->input('adcode');
            }
            
            if ($request->has('allowempty')) {
                $allow_empty = $request->input('allowempty');
            }
                        
            if ($adcode) {
                $adpositions = AdPosition::getAdPositions($adcode);
                if ($allow_empty === 'true') {
                    ;
                } else {
                    $adpositions = array_filter($adpositions, function($item){
                        return count($item->nodes) > 0;
                    });
                }
            } else {
                throw new Exception("Please specify a 'adcode' parameter to get a list of adpositions.");
            }
                                                
            $response = ApiResponse::get(false, $adpositions); 
            
        //} catch (Exception $ex) {
        //    $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        //}
  
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
     * @param  mix  $param  Adposition id or name
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        try {
            $adposition = new AdPosition($param);
            
            $response = ApiResponse::get(false, [$adposition]);           
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
