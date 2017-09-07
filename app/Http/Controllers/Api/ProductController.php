<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use App\Services\ApiResponse;
use App\Models\Shop\Product;
use Exception;
use DB;
use Log;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource. Request parameters of 
     * $offset:         index start
     * $count:          count
     * $ids:            or specify a list of ids     
     * $category_id:    optional specify the product category
     * $order:          display order by "show_price asc" or "sale_count desc"
     * take effect to limit the count and order of goods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $offset = $request->has('offset')?$request->input('offset'):0;
            $count = $request->has('count')?$request->input('count'):0;
            $ids = $request->has('ids')?$request->input('ids'):'';
            $category_id = $request->has('category_id')?$request->input('category_id'):-1;
            
            $order = ['show_price', 'asc'];
            if ($request->has('order')) {
                $request_order = explode(' ', $request->input('order'));
                if (count($request_order) < 2) {
                    array_push($request_order, 'asc');
                }
                
                $field = array_first($request_order);
                if (strtolower($field) == 'price') {
                    $order[1] = strtolower(end($request_order));
                }
                
                if (strtolower($field) == 'sn') {
                    $order[0] = strtolower(array_first($request_order));
                    $order[1] = strtolower(end($request_order));
                }
            }
            
            $query_build = null;
            if (!empty($ids)) {
                $query_build = Product::wherein('id', explode(',', $ids));
            } else {
                $query_build = Product::take($count)->offset($offset);
            }
             
            if (intval($category_id) >= 0) {
                $query_build = $query_build->where('shop_category_id', '=', $category_id);
            }           
                        
            $goods = $query_build->select('id','shop_category_id','name',
                    'sn','info','area','package','show_price','show_min_price',
                    'show_max_price','status','is_available','created_at','updated_at')
                    ->where("is_availabe", "=", 1)
                    ->where("status", "=", 2)
                    ->with('images')->orderBy(array_first($order), end($order))->get();                

            $response = ApiResponse::get(false, json_decode(json_encode($goods), True));           
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
        //
    }

    /**
     * Display the specified resource. To save request time, parameter: 
     * 
     * $no_details: indicates not return the html_info field which contains a lot of characters.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {     
        try {
            $good = null;
            
            if ($request->has('no_details')) {
                $good = Product::where('id','=', $id)  
                    ->select('id','shop_category_id','name',
                    'sn','info','area','package','show_price','show_min_price',
                    'show_max_price','status','is_available','created_at','updated_at')
                    ->with(['images' => function ($query) {$query->orderBy('pos', 'asc');}])->first();
            } else {
                $good = Product::where('id','=', $id)->with('images')->first();
            }
                                    
            if ($good === false) {                
                $response = ApiResponse::get(true, ['message' => "Good $id Not Found."]);                  
            } else {                 
                $good->brand_name = $good->getBrandName();
                $response = ApiResponse::get(false, $good->toArray());
            }           
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
     * Update the specified resource in storage. Example:
     * curl -X PUT -d 'view_count=449' http://localhost/api/products/474
     *
     * @param \Illuminate\Http\Request  $request
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
        //
    }
    
    /**
     * Get the related Goods
     * 
     * @param \Illuminate\Http\Request  $request
     * @param string $id good id
     */
    public function related(Request $request, $id) 
    {

    }    
}
