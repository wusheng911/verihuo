<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use App\Services\ApiResponse;
use App\Models\Shop\Order;
use Exception;
use Log;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        try {            
            $customer_id = $request->has('user_id')?$request->input('user_id'):null;
            $ids = $request->has('ids')?explode(",", $request->input('ids')):[];
                                  
            if (empty($customer_id)) {
                throw new Exception("用户不存在！");
            }
            
            $query_builder = Order::where("customer_id", "=", $customer_id);
            
            if (count($ids) > 0) {
                $query_builder = $query_builder->wherein("id", $ids);
            }
            
            $orders = $query_builder->orderBy("created_at", "desc")->get();                     
            
            $response = ApiResponse::get(false, $orders->toArray());           
        } catch (\Exception $ex) {
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
    
}
